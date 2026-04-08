<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Berhasil login!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'terms' => 'accepted',
        ], [
            'fname.required' => 'Nama depan harus diisi',
            'lname.required' => 'Nama belakang harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        $user = User::create([
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil logout!');
    }

    public function showForgotPasswordForm()
    {
        return view('pages.auth.forgot-password', ['title' => 'Forgot Password']);
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate(
            ['email' => 'required|email|exists:users,email'],
            [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.exists' => 'Email tidak ditemukan dalam sistem',
            ]
        );

        $user = User::where('email', $request->email)->first();
        
        // Generate a simple reset token and store it
        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // In production, send email here
        // For demo, we'll store the token in session
        session(['reset_token_' . $request->email => $token]);

        return redirect()->route('password.reset.form', ['email' => $request->email, 'token' => $token])
            ->with('success', 'Link reset password telah dikirim!');
    }

    public function showResetPasswordForm(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');

        return view('pages.auth.reset-password', [
            'title' => 'Reset Password',
            'email' => $email,
            'token' => $token,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:8|confirmed',
                'token' => 'required',
            ],
            [
                'email.required' => 'Email harus diisi',
                'email.exists' => 'Email tidak ditemukan',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
            ]
        );

        // Verify token
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetToken || !Hash::check($request->token, $resetToken->token)) {
            return back()->withErrors(['token' => 'Token tidak valid atau sudah kadaluarsa']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        // Delete reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}