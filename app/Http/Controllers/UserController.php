<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('pages.users.index', [
            'title' => 'Kelola Data Users',
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('pages.users.create.index', [
            'title' => 'Tambah User',
            'roles' => $roles,
        ]);
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        return view('pages.users.edit.index', [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['password'] = bcrypt($validated['password']);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('users', 'public');
        }

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('users', 'public');
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function status()
    {
        $activeUserIds = [];
        if (Schema::hasTable('sessions')) {
            $activeUserIds = $this->getOnlineUserIdsFromSessions(5);
        }

$users = User::with('role')->select('id', 'name', 'email', 'role_id')
            ->get()
            ->map(function ($user) use ($activeUserIds) {
                $isOnline = in_array($user->id, $activeUserIds, true);

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => strtolower($user->role?->name ?? ''),
            'status' => $isOnline ? 'Online' : 'Offline',
            'is_online' => $isOnline,
        ];
    })
    ->sortByDesc('is_online') // 🔥 INI PENTING
    ->values();

        return response()->json($users);
    }

    private function getOnlineUserIdsFromSessions(int $minutes = 5): array
    {
        $threshold = Carbon::now()->subMinutes($minutes)->timestamp;
        $sessionRows = DB::table('sessions')
            ->where('last_activity', '>=', $threshold)
            ->get();

        $onlineIds = [];
        foreach ($sessionRows as $session) {
            $userId = $this->extractUserIdFromPayload($session->payload);
            if ($userId) {
                $onlineIds[] = (int) $userId;
            }
        }

        return array_unique($onlineIds);
    }

    private function extractUserIdFromPayload(string $payload): ?int
    {
        $decoded = @base64_decode($payload, true);
        $data = null;

        if ($decoded !== false && $decoded !== $payload) {
            $data = @unserialize($decoded);
        }

        if ($data === false) {
            $data = @unserialize($payload);
        }

        if (!is_array($data)) {
            return null;
        }

        foreach ($data as $key => $value) {
            if (!preg_match('/^login_/', $key)) {
                continue;
            }

            if (is_int($value) || ctype_digit((string) $value)) {
                return (int) $value;
            }

            if (is_array($value) && isset($value['id'])) {
                return (int) $value['id'];
            }

            if (is_object($value) && property_exists($value, 'id')) {
                return (int) $value->id;
            }
        }

        return null;
    }
}
