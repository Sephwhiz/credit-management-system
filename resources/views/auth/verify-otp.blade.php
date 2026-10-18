<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('We\'ve sent a 6-digit OTP to your email. Please enter it below to verify your account.') }}
    </div>

    @if (session('success'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('success') }}
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