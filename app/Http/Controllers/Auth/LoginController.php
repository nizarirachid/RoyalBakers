<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $key = 'login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => $seconds]),
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if ($user && $user->isLocked()) {
            throw ValidationException::withMessages([
                'email' => __('auth.account_locked'),
            ]);
        }

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($key, 300);

            if ($user) {
                $user->increment('login_attempts');
                if ($user->login_attempts >= 5) {
                    $user->update(['locked_until' => now()->addMinutes(15)]);
                }
            }

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($key);
        $user = Auth::user();
        $user->update(['login_attempts' => 0]);

        // Super-admin requires OTP verification
        if ($user->hasRole('super-admin')) {
            Auth::logout();
            $request->session()->regenerate();

            $otp = (string) random_int(100000, 999999);
            Cache::put("otp_{$user->id}", $otp, now()->addMinutes(10));

            try {
                Mail::to($user->email)->send(new AdminOtpMail($otp, $user->name));
            } catch (\Exception $e) {
                // Log failure but don't expose to user
                \Log::error('OTP mail failed: ' . $e->getMessage());
            }

            session([
                'otp_user_id' => $user->id,
                'otp_email'   => $user->email,
                'otp_remember' => $request->boolean('remember'),
            ]);

            return redirect()->route('otp.show')
                ->with('info', __('auth.otp_sent'));
        }

        $request->session()->regenerate();

        if ($user->force_password_change) {
            return redirect()->route('password.change')
                ->with('warning', __('auth.change_password_required'));
        }

        if ($user->hasAnyRole(['admin', 'editor', 'teacher'])) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('home'));
    }

    // ── OTP ───────────────────────────────────────────────────────────────────

    public function showOtp()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $storedOtp = Cache::get("otp_{$userId}");

        if (!$storedOtp || $storedOtp !== $request->otp) {
            return back()->withErrors(['otp' => __('auth.otp_invalid')]);
        }

        Cache::forget("otp_{$userId}");

        $user = User::findOrFail($userId);
        Auth::login($user, session('otp_remember', false));
        $request->session()->forget(['otp_user_id', 'otp_email', 'otp_remember']);
        $request->session()->regenerate();

        if ($user->force_password_change) {
            return redirect()->route('password.change')
                ->with('warning', __('auth.change_password_required'));
        }

        return redirect()->route('admin.dashboard');
    }

    public function resendOtp(Request $request)
    {
        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($userId);
        $otp  = (string) random_int(100000, 999999);
        Cache::put("otp_{$userId}", $otp, now()->addMinutes(10));

        try {
            Mail::to($user->email)->send(new AdminOtpMail($otp, $user->name));
        } catch (\Exception $e) {
            \Log::error('OTP resend failed: ' . $e->getMessage());
        }

        return back()->with('info', __('auth.otp_sent'));
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // ── Password Change ───────────────────────────────────────────────────────

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
        ]);

        $request->user()->update([
            'password'              => Hash::make($request->password),
            'force_password_change' => false,
        ]);

        return redirect()->route('home')->with('success', __('auth.password_changed'));
    }
}
