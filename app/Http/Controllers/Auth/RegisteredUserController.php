<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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
        // Module 9: reCAPTCHA Validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => ['required', 'captcha'],
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Module 10: Generate and Send OTP
        $otp = $otpService->generate();
        $otpService->send($user->email, $otp);

        // Store OTP in session for verification
        session([
            'otp' => $otp,
            'otp_expiry' => now()->addMinutes(5),
            'pending_user_id' => $user->id,
        ]);

        event(new Registered($user));

        // Redirect to OTP verification page instead of dashboard
        return redirect()->route('verify-otp')->with('success', 'Registration successful! Please check your email for the OTP.');
    }

    /**
     * Show the OTP verification form.
     * Module 10 Step 5: Verify OTP and Handle Verification Flow
     */
    public function showOtpVerification(): View
    {
        // Ensure there's a pending OTP in session
        if (!session()->has('pending_user_id')) {
            return redirect()->route('register')->withErrors(['otp' => 'No pending verification found. Please register again.']);
        }

        return view('auth.verify-otp');
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
        $storedOtp = session('otp');
        $otpExpiry = session('otp_expiry');
        $pendingUserId = session('pending_user_id');

        // Module 10 Step 5: Verify OTP logic
        if ($storedOtp && $userOtp == $storedOtp && now()->lt($otpExpiry)) {
            // Clear OTP from session upon success
            session()->forget(['otp', 'otp_expiry', 'pending_user_id']);

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