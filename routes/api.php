<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes for Payment Application
|--------------------------------------------------------------------------
|
| These routes are used by the external payment app (Lovable/Flutter)
| to communicate with this Laravel application.
|
*/

Route::prefix('v1')->group(function () {
    
    // Health check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'message' => 'API PerpustakaanKu is running',
            'timestamp' => now()->toIso8601String()
        ]);
    });

    // Auth - Login
    Route::post('/auth/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Generate simple API token
        $token = bin2hex(random_bytes(32));
        
        // Save token to user
        DB::table('api_tokens')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => now(),
            'expires_at' => now()->addDays(30)
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->name ?? 'member'
                ],
                'token' => $token
            ]
        ]);
    });

    // Auth - Logout
    Route::post('/auth/logout', function (Request $request) {
        $token = $request->header('Authorization');
        if ($token) {
            $token = str_replace('Bearer ', '', $token);
            DB::table('api_tokens')->where('token', $token)->delete();
        }
        return response()->json(['success' => true, 'message' => 'Logout berhasil']);
    });

    // Check token validity
    function validateToken($token) {
        if (!$token) return null;
        $token = str_replace('Bearer ', '', $token);
        $apiToken = DB::table('api_tokens')
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();
        if ($apiToken) {
            return User::find($apiToken->user_id);
        }
        return null;
    }

    // Get fines/borrowings with pending payments
    Route::get('/fines', function (Request $request) {
        $user = validateToken($request->header('Authorization'));
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $borrowings = Borrowing::with(['book'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['borrowed', 'return_requested', 'returned'])
            ->where(function($query) {
                $query->where('fine_status', 'unpaid')
                    ->orWhere(function($q) {
                        $q->where('fine', '>', 0)
                            ->whereNull('fine_status');
                    });
            })
            ->get()
            ->map(function ($borrowing) {
                $dueDate = \Carbon\Carbon::parse($borrowing->due_date);
                $isOverdue = \Carbon\Carbon::now()->greaterThan($dueDate);
                
                $fineAmount = $borrowing->fine;
                if ($isOverdue && $fineAmount == 0) {
                    $daysLate = (int)\Carbon\Carbon::now()->diffInDays($dueDate);
                    $finePerDay = $borrowing->book->fine_per_day ?? 5000;
                    $fineAmount = $daysLate * $finePerDay;
                }
                
                return [
                    'id' => $borrowing->id,
                    'book_title' => $borrowing->book->title ?? 'Unknown',
                    'book_cover' => $borrowing->book->cover_image 
                        ? url('storage/' . $borrowing->book->cover_image) 
                        : null,
                    'fine_amount' => $fineAmount,
                    'fine_status' => $borrowing->fine_status ?? ($fineAmount > 0 ? 'unpaid' : 'paid'),
                    'due_date' => $borrowing->due_date,
                    'is_overdue' => $isOverdue,
                    'status' => $borrowing->status,
                    'created_at' => $borrowing->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $borrowings
        ]);
    });

    // Get single borrowing/fine details
    Route::get('/fines/{id}', function (Request $request, $id) {
        $user = validateToken($request->header('Authorization'));
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $borrowing = Borrowing::with(['book'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$borrowing) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $dueDate = \Carbon\Carbon::parse($borrowing->due_date);
        $isOverdue = \Carbon\Carbon::now()->greaterThan($dueDate);
        $fineAmount = $borrowing->fine;
        
        if ($isOverdue && $fineAmount == 0) {
            $daysLate = (int)\Carbon\Carbon::now()->diffInDays($dueDate);
            $finePerDay = $borrowing->book->fine_per_day ?? 5000;
            $fineAmount = $daysLate * $finePerDay;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $borrowing->id,
                'book_title' => $borrowing->book->title,
                'book_cover' => $borrowing->book->cover_image 
                    ? url('storage/' . $borrowing->book->cover_image) 
                    : null,
                'fine_amount' => $fineAmount,
                'fine_status' => $borrowing->fine_status ?? ($fineAmount > 0 ? 'unpaid' : 'paid'),
                'due_date' => $borrowing->due_date,
                'is_overdue' => $isOverdue,
                'status' => $borrowing->status
            ]
        ]);
    });

    // Pay/settle fine
    Route::post('/fines/{id}/pay', function (Request $request, $id) {
        $user = validateToken($request->header('Authorization'));
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $borrowing = Borrowing::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$borrowing) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        if ($borrowing->fine_status === 'paid') {
            return response()->json(['success' => false, 'message' => 'Denda sudah lunas'], 400);
        }

        // Update fine status to paid
        $borrowing->update([
            'fine_status' => 'paid',
            'paid_at' => now(),
            'notes' => ($borrowing->notes ? $borrowing->notes . '; ' : '') . 'Dibayar via app mobile'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil!',
            'data' => [
                'fine_amount' => $borrowing->fine,
                'paid_at' => $borrowing->paid_at
            ]
        ]);
    });

    // Get payment QRIS code (simulation)
    Route::get('/fines/{id}/qris', function (Request $request, $id) {
        $user = validateToken($request->header('Authorization'));
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $borrowing = Borrowing::with(['book'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$borrowing) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $dueDate = \Carbon\Carbon::parse($borrowing->due_date);
        $isOverdue = \Carbon\Carbon::now()->greaterThan($dueDate);
        $fineAmount = $borrowing->fine;
        
        if ($isOverdue && $fineAmount == 0) {
            $daysLate = (int)\Carbon\Carbon::now()->diffInDays($dueDate);
            $finePerDay = $borrowing->book->fine_per_day ?? 5000;
            $fineAmount = $daysLate * $finePerDay;
        }

        // Generate QRIS code
        $qrisData = 'ID10PERPUS' . str_pad($borrowing->id, 6, '0', STR_PAD_LEFT) . 
                   str_pad($fineAmount, 10, '0', STR_PAD_LEFT) . 'QRIS';

        return response()->json([
            'success' => true,
            'data' => [
                'qris_code' => $qrisData,
                'amount' => $fineAmount,
                'merchant' => 'PerpustakaanKu',
                'valid_until' => now()->addMinutes(5)->toIso8601String()
            ]
        ]);
    });

    // Get user profile
    Route::get('/profile', function (Request $request) {
        $user = validateToken($request->header('Authorization'));
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo ? url('storage/' . $user->photo) : null,
                'role' => $user->role->name ?? 'member'
            ]
        ]);
    });
});
