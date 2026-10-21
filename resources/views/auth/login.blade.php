<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div role="status" class="mb-4 text-sm font-medium text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        @if ($errors->any())
            <div role="alert" class="mb-4 rounded-md border border-red-300 bg-red-50 p-4 text-sm text-red-800">
                <strong>Please fix these errors:</strong>
                <ul class="mt-1 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" class="mt-1"
                   type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" class="mt-1"
                   type="password" name="password" required autocomplete="current-password" />
            @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Remember Me -->
        <div class="mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <div class="auth-actions mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm underline" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif

            <button type="submit">
                Log in
            </button>
        </div>
    </form>
</x-guest-layout>