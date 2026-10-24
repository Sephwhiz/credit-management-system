<x-guest-layout>
    @if(isset($dbOtp) && $dbOtp)
        <div style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1rem; text-align: center;">
            <strong>Your OTP Code:</strong> 
            <span style="font-size: 1.5rem; font-weight: bold; letter-spacing: 0.25rem; display: block; margin-top: 0.5rem;">{{ $dbOtp }}</span>
            <small style="display: block; margin-top: 0.5rem; opacity: 0.8;">(Copy this code to verify below)</small>
        </div>
    @endif

    <div class="mb-4 text-sm text-gray-600">
        {{ __('We\'ve sent a 6-digit OTP to your email. Please enter it below to verify your account.') }}
    </div>

    @if (session('success'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('success') }}
        </div>
    @endif

    @if (isset($error))
        <div class="mb-4 font-medium text-sm text-red-600">
            {{ $error }}
        </div>
    @endif

    <form method="POST" action="{{ route('verify-otp.confirm') }}">
        @csrf

        <!-- OTP Input -->
        <div>
            <x-input-label for="otp" :value="__('One-Time Password')" />
            <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" required autofocus autocomplete="one-time-code" maxlength="6" placeholder="Enter 6-digit OTP" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <!-- Resend OTP Link (Optional Enhancement) -->
        <div class="flex items-center justify-end mt-4">
            <a href="#" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Resend OTP?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Verify OTP') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>