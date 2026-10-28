<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Throwable;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request, OtpService $otpService): RedirectResponse
    {
        session()->forget(['pending_user_id', 'otp_expiry']);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            [$user, $otp] = DB::transaction(function () use ($request, $otpService) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                $otp = $otpService->generate();

                DB::table('user_otps')->insert([
                    'user_id' => $user->id,
                    'code' => $otp,
                    'expires_at' => now()->addHours(24),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return [$user, $otp];
            });
        } catch (Throwable $e) {
            Log::error('Unable to create user registration OTP.', ['exception' => $e]);
            return back()->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['registration' => 'We could not complete your registration right now. Please try again.']);
        }

        try {
            $otpService->send($user->email, $otp);
        } catch (\Throwable $e) {
            Log::warning('OTP email failed: ' . $e->getMessage());
        }

        session([
            'pending_user_id' => $user->id,
            'otp_expiry' => now()->addHours(24),
        ]);

        event(new Registered($user));
        return redirect()->route('verify-otp')->with('success', 'Registration successful!');
    }

    public function showOtpVerification(): View|RedirectResponse
    {
        $pendingUserId = session('pending_user_id');

        if (!$pendingUserId) {
            return redirect()->route('register')->withErrors(['otp' => 'No pending verification found. Please register again.']);
        }

        try {
            // FIX: Use whereRaw for PostgreSQL boolean compatibility
            $dbOtp = DB::table('user_otps')
                ->where('user_id', $pendingUserId)
                ->whereRaw('"used" = false')
                ->orderBy('created_at', 'desc')
                ->value('code');
        } catch (Throwable $e) {
            Log::error('Unable to load pending OTP.', ['pending_user_id' => $pendingUserId, 'exception' => $e]);
            $dbOtp = null;
        }

        // DEBUG: Show OTP code only when APP_DEBUG is true
        $debugCode = config('app.debug') ? $dbOtp : null;

        return view('auth.verify-otp', compact('dbOtp', 'debugCode'));
    }

    public function confirmOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'numeric', 'digits:6'],
        ], [
            'otp.required' => 'Please enter the OTP.',
            'otp.numeric' => 'The OTP must be a valid number.',
            'otp.digits' => 'The OTP must be exactly 6 digits.',
        ]);

        $userOtp = $request->input('otp');
        $pendingUserId = session('pending_user_id');

        try {
            // SINGLE ATOMIC QUERY - FIX: Use whereRaw for PostgreSQL boolean compatibility
            $validOtp = DB::table('user_otps')
                ->where('user_id', $pendingUserId)
                ->where('code', $userOtp)
                ->whereRaw('"used" = false')
                ->where('expires_at', '>', now())
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$validOtp) {
                // Determine specific failure reason
                $anyOtp = DB::table('user_otps')
                    ->where('user_id', $pendingUserId)
                    ->where('code', $userOtp)
                    ->first();

                if (!$anyOtp) {
                    return back()->withErrors(['otp' => 'Incorrect OTP code.'])->withInput();
                } elseif ($anyOtp->used) {
                    return back()->withErrors(['otp' => 'This OTP has already been used.'])->withInput();
                } else {
                    return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.'])->withInput();
                }
            }

            // Mark as used - FIX: Array with DB::raw('true') for PG boolean compatibility
            DB::table('user_otps')
                ->where('id', $validOtp->id)
                ->update(['used' => DB::raw('true')]);

        } catch (Throwable $e) {
            Log::error('OTP verification exception.', [
                'pending_user_id' => $pendingUserId,
                'exception_message' => $e->getMessage(),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine()
            ]);

            // TEMPORARY: Show exact error for debugging
            return back()->withErrors(['otp' => 'ERROR: ' . $e->getMessage()])->withInput();
        }

        session()->forget(['pending_user_id', 'otp_expiry']);

        $user = User::find($pendingUserId);
        Auth::login($user);
        event(new Registered($user));

        return redirect()->route('dashboard')->with('success', 'OTP verified successfully! Welcome to Credit Management System.');
    }
}