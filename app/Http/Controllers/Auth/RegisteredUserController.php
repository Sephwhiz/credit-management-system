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
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request, OtpService $otpService): RedirectResponse
    {
        // Module 9: reCAPTCHA Validation (Temporarily Disabled)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // 'g-recaptcha-response' => ['required', 'captcha'],
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
                    'expires_at' => now()->addHours(24), // OTP valid for 24 hours
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return [$user, $otp];
            });
        } catch (Throwable $e) {
            Log::error('Unable to create user registration OTP.', ['exception' => $e]);

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['registration' => 'We could not complete your registration right now. Please try again.']);
        }

        // Try to send email but never let it block registration
        try {
            $otpService->send($user->email, $otp);
        } catch (\Throwable $e) {
            Log::warning('OTP email failed: ' . $e->getMessage());
        }

        // Store only user reference in session (OTP retrieved from DB during verification)
        session([
            'pending_user_id' => $user->id,
            'otp_expiry' => now()->addHours(24),
        ]);

        event(new Registered($user));

        return redirect()->route('verify-otp')->with('success', 'Registration successful!');
    }

    /**
     * Show the OTP verification form.
     * Module 10 Step 5: Verify OTP and Handle Verification Flow
     */
    public function showOtpVerification(): View|RedirectResponse
    {
        // Ensure there's a pending OTP in session
        $pendingUserId = session('pending_user_id');

        if (!$pendingUserId) {
            return redirect()->route('register')->withErrors(['otp' => 'No pending verification found. Please register again.']);
        }

        try {
            $dbOtp = DB::table('user_otps')
                ->where('user_id', $pendingUserId)
                ->where('expires_at', '>', now())
                ->where('used', false)
                ->orderBy('created_at', 'desc')
                ->value('code');
        } catch (Throwable $e) {
            Log::error('Unable to load pending OTP.', [
                'pending_user_id' => $pendingUserId,
                'exception' => $e,
            ]);

            return view('auth.verify-otp', ['dbOtp' => null])
                ->with('error', 'We could not load your verification code right now. You can still enter the code sent to your email, or try again shortly.');
        }

        return view('auth.verify-otp', compact('dbOtp'));
    }

    /**
     * Confirm the OTP entered by the user.
     * Module 10 Step 6: Customize OTP Handling and Error Messages
     */
    public function confirmOtp(Request $request): RedirectResponse
    {
        // Custom Validation per Module 10 Step 6
        $request->validate([
            'otp' => ['required', 'numeric', 'digits:6'],
        ], [
            'otp.required' => 'Please enter the OTP.',
            'otp.numeric' => 'The OTP must be a valid number.',
            'otp.digits' => 'The OTP must be exactly 6 digits.',
        ]);

        $userOtp = $request->input('otp');
        $pendingUserId = session('pending_user_id');
        $otpExpiry = session('otp_expiry');

        // Verify OTP against database record
        try {
            $validOtp = DB::table('user_otps')
                ->where('user_id', $pendingUserId)
                ->where('code', $userOtp)
                ->where('expires_at', '>', now())
                ->where('used', false)
                ->first();
        } catch (Throwable $e) {
            Log::error('Unable to verify OTP against the database.', [
                'pending_user_id' => $pendingUserId,
                'exception' => $e,
            ]);

            return back()
                ->withErrors(['otp' => 'We could not verify your OTP right now. Please try again shortly.'])
                ->withInput();
        }

        if ($validOtp && $otpExpiry && now()->lt($otpExpiry)) {
            // Mark OTP as used in database to prevent replay attacks
            try {
                DB::table('user_otps')
                    ->where('id', $validOtp->id)
                    ->update(['used' => true]);
            } catch (Throwable $e) {
                Log::error('Unable to mark OTP as used.', [
                    'pending_user_id' => $pendingUserId,
                    'exception' => $e,
                ]);

                return back()
                    ->withErrors(['otp' => 'We could not complete OTP verification right now. Please try again shortly.'])
                    ->withInput();
            }

            // Clear session data
            session()->forget(['pending_user_id', 'otp_expiry']);

            // Log the user in
            $user = User::find($pendingUserId);
            Auth::login($user);

            event(new Registered($user));

            return redirect()->route('dashboard')->with('success', 'OTP verified successfully! Welcome to Credit Management System.');
        } else {
            // Return with error message
            return back()->withErrors(['otp' => 'The OTP is incorrect or has expired.'])->withInput();
        }
    }
}