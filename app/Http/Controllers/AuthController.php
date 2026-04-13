<?php

// Namespace untuk controller
namespace App\Http\Controllers;

// Import model dan facade yang diperlukan
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Controller untuk menangani autentikasi pengguna
class AuthController extends Controller
{
    // Fungsi untuk menangani login pengguna
    public function login(Request $request)
    {
        // Validasi input email dan password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
        ]);

        // Ambil kredensial dari request
        $credentials = $request->only('email', 'password');

        // Coba autentikasi pengguna
        if (Auth::attempt($credentials)) {
            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Tentukan route berdasarkan role pengguna
            $roleName = strtolower(optional(Auth::user()->role)->name ?? 'guest');
            
            $homeRoute = match($roleName) {
                'admin' => 'dashboard',                  // Admin ke dashboard admin (ecommerce)
                'petugas' => 'dashboard_petugas',        // Petugas ke dashboard petugas
                'member' => 'welcome',                   // Member ke halaman welcome
                default => 'dashboard',
            };

            return redirect()->route($homeRoute)->with('success', 'Berhasil login!');
        }

        // Jika gagal, kembali dengan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Fungsi untuk menangani registrasi pengguna baru
    public function register(Request $request)
    {
        // Validasi input registrasi
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:8|confirmed',
            'terms' => 'accepted',
        ], [
            'fname.required' => 'Nama depan harus diisi',
            'lname.required' => 'Nama belakang harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.max' => 'Password maksimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        // Buat pengguna baru
        $user = User::create([
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password untuk keamanan
        ]);

        // Login pengguna secara otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat!');
    }

    // Fungsi untuk menangani logout pengguna
    public function logout(Request $request)
    {
        // Logout pengguna
        Auth::logout();

        // Invalidate dan regenerasi session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman utama dengan pesan sukses
        return redirect('/')->with('success', 'Berhasil logout!');
    }

    // Fungsi untuk menampilkan form lupa password
    public function showForgotPasswordForm()
    {
        // Return view form lupa password
        return view('pages.auth.forgot-password', ['title' => 'Forgot Password']);
    }

    // Fungsi untuk mengirim link reset password
    public function sendPasswordResetLink(Request $request)
    {
        // Validasi email
        $request->validate(
            ['email' => 'required|email|exists:users,email'],
            [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.exists' => 'Email tidak ditemukan dalam sistem',
            ]
        );

        // Cari pengguna berdasarkan email
        $user = User::where('email', $request->email)->first();
        
        // Generate token reset password
        $token = Str::random(60);
        // Simpan token di database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Untuk demo, simpan token di session (di production kirim email)
        session(['reset_token_' . $request->email => $token]);

        // Redirect ke form reset dengan token
        return redirect()->route('password.reset.form', ['email' => $request->email, 'token' => $token])
            ->with('success', 'Link reset password telah dikirim!');
    }

    // Fungsi untuk menampilkan form reset password
    public function showResetPasswordForm(Request $request)
    {
        // Ambil email dan token dari query
        $email = $request->query('email');
        $token = $request->query('token');

        // Return view form reset password
        return view('pages.auth.reset-password', [
            'title' => 'Reset Password',
            'email' => $email,
            'token' => $token,
        ]);
    }

    // Fungsi untuk mereset password
    public function resetPassword(Request $request)
    {
        // Validasi input reset password
        $request->validate(
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:6|max:8|confirmed',
                'token' => 'required',
            ],
            [
                'email.required' => 'Email harus diisi',
                'email.exists' => 'Email tidak ditemukan',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 6 karakter',
                'password.max' => 'Password maksimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
            ]
        );

        // Verifikasi token reset
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        // Jika token tidak valid atau tidak ada
        if (!$resetToken || !Hash::check($request->token, $resetToken->token)) {
            return back()->withErrors(['token' => 'Token tidak valid atau sudah kadaluarsa']);
        }

        // Update password pengguna
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        // Hapus token reset dari database
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Redirect ke login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}