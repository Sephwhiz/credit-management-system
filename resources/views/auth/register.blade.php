<x-guest-layout>
    <!-- TEMPORARY INLINE STYLES -->
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; max-width: 600px; margin: 2rem auto; padding: 0 1rem; background: #fff; color: #1f2937; }
        h2 { font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; }
        label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem; }
        input[type="text"], input[type="email"], input[type="password"] {
            display: block; width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; margin-top: 0.25rem;
        }
        input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .error-msg { color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; }
        .g-recaptcha { margin: 1rem 0; min-height: 78px; }
        .btn-row { display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; }
        .btn-primary { background: #4f46e5; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; font-size: 0.875rem; cursor: pointer; }
        .btn-primary:hover { background: #4338ca; }
        a.link { color: #6b7280; font-size: 0.875rem; text-decoration: underline; }
        a.link:hover { color: #1f2937; }
    </style>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            @error('name') <p class="error-msg">{{ $message }}</p> @enderror
        </div>

        <!-- Email Address -->
        <div style="margin-top:1rem;">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            @error('email') <p class="error-msg">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div style="margin-top:1rem;">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" />
            @error('password') <p class="error-msg">{{ $message }}</p> @enderror
        </div>

        <!-- Confirm Password -->
        <div style="margin-top:1rem;">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            @error('password_confirmation') <p class="error-msg">{{ $message }}</p> @enderror
        </div>

        <!-- Module 9: reCAPTCHA Integration (HARDCODED KEY) -->
        <div style="margin-top:1rem;">
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            
            <div class="g-recaptcha" 
                 data-sitekey="6Ld8qfQtAAAAABRItxM_s7H5BR2nUsCBSku-ZSOG">
            </div>
            
            @error('g-recaptcha-response') 
                <p class="error-msg">{{ $message }}</p> 
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="btn-row">
            <a class="link" href="{{ route('login') }}">Already registered?</a>
            <button type="submit" class="btn-primary">Register</button>
        </div>
    </form>
</x-guest-layout>