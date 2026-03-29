<x-guest-layout>
  <div x-data="changePasswordForm()" class="flex flex-col gap-6">

    {{-- Header --}}
    <div>
      <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mb-4">
        <i class="fa-solid fa-key text-blue-950 text-sm"></i>
      </div>
      <h2 class="text-2xl font-bold text-blue-950">Set new password</h2>
      <p class="text-sm text-gray-500 mt-1">Choose a strong password for your account.</p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('password.change') }}" @submit.prevent="submit($el)" novalidate class="flex flex-col gap-4">
      @csrf
      <input type="hidden" name="email" value="{{ $email }}">

      {{-- New password --}}
      <div class="flex flex-col gap-1">
        <label for="password" class="text-sm font-medium text-blue-950">New password</label>

        <div class="relative">
          <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>

          <input
            id="password"
            :type="showPwd ? 'text' : 'password'"
            name="password"
            placeholder="••••••••"
            autocomplete="new-password"
            autofocus
            x-model="password"
            @input="updateStrength()"
            @blur="touch('password')"
            class="w-full pl-10 pr-10 py-3 text-sm border rounded-md focus:outline-none focus:ring-2 transition-colors
              {{ $errors->has('password') ? 'border-red-400 focus:ring-red-300 bg-red-50' : 'border-gray-300 focus:ring-blue-400 focus:border-blue-400' }}"
          >

          <button type="button" @click="showPwd = !showPwd"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
            <i :class="showPwd ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
          </button>
        </div>

        {{-- Strength bar --}}
        <div class="flex gap-1 mt-1" x-show="password.length > 0" x-cloak>
          <template x-for="i in 4" :key="i">
            <div class="h-1 flex-1 rounded-full"
              :class="i <= strength
                ? (strength === 1 ? 'bg-red-400'
                : strength === 2 ? 'bg-amber-400'
                : strength === 3 ? 'bg-yellow-400'
                : 'bg-emerald-500')
                : 'bg-gray-200'">
            </div>
          </template>

          <span class="text-xs ml-1 font-medium"
            :class="strength === 1 ? 'text-red-500'
            : strength === 2 ? 'text-amber-500'
            : strength === 3 ? 'text-yellow-600'
            : 'text-emerald-600'"
            x-text="['', 'Weak', 'Fair', 'Good', 'Strong'][strength]">
          </span>
        </div>

        {{-- Rules --}}
        <ul class="text-xs text-gray-400 flex flex-wrap gap-x-3 gap-y-0.5 mt-1"
            x-show="password.length > 0" x-cloak>
          <li :class="password.length >= 8 ? 'text-emerald-600' : ''">8+ chars</li>
          <li :class="/[A-Z]/.test(password) ? 'text-emerald-600' : ''">Uppercase</li>
          <li :class="/[a-z]/.test(password) ? 'text-emerald-600' : ''">Lowercase</li>
          <li :class="/[0-9]/.test(password) ? 'text-emerald-600' : ''">Number</li>
        </ul>

        {{-- Error --}}
        @error('password')
          <p class="text-xs text-red-600 flex items-center gap-1">
            <i class="fa-solid fa-circle-exclamation fa-xs"></i> {{ $message }}
          </p>
        @else
          <p class="text-xs text-red-600 flex items-center gap-1" x-show="errors.password" x-cloak>
            <i class="fa-solid fa-circle-exclamation fa-xs"></i>
            <span x-text="errors.password"></span>
          </p>
        @enderror
      </div>

      {{-- Confirm password --}}
      <div class="flex flex-col gap-1">
        <label for="password_confirmation" class="text-sm font-medium text-blue-950">Confirm new password</label>

        <div class="relative">
          <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>

          <input
            id="password_confirmation"
            :type="showConfirm ? 'text' : 'password'"
            name="password_confirmation"
            placeholder="••••••••"
            autocomplete="new-password"
            x-model="confirmation"
            @blur="touch('confirmation')"
            class="w-full pl-10 pr-10 py-3 text-sm border rounded-md focus:outline-none focus:ring-2
              border-gray-300 focus:ring-blue-400 focus:border-blue-400"
          >

          <button type="button" @click="showConfirm = !showConfirm"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
            <i :class="showConfirm ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
          </button>
        </div>

        <p class="text-xs text-red-600 flex items-center gap-1"
           x-show="confirmation && confirmation !== password" x-cloak>
          <i class="fa-solid fa-circle-exclamation fa-xs"></i>
          Passwords do not match
        </p>
      </div>

      {{-- Submit --}}
      <button type="submit"
        :disabled="loading || strength < 3 || confirmation !== password"
        class="w-full py-3 bg-blue-950 hover:bg-blue-900 text-white text-sm font-semibold rounded-md transition
          disabled:opacity-60 disabled:cursor-not-allowed">
        <span x-show="!loading">Update Password</span>
        <span x-show="loading" x-cloak class="flex items-center justify-center gap-2">
          <i class="fa-solid fa-circle-notch fa-spin fa-sm"></i> Updating…
        </span>
      </button>
    </form>

    {{-- Back --}}
    <p class="text-center text-sm text-gray-500">
      <a href="{{ route('login') }}" class="text-blue-700 font-medium hover:underline">Back to sign in</a>
    </p>

  </div>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('changePasswordForm', () => ({
        password: '',
        confirmation: '',
        showPwd: false,
        showConfirm: false,
        loading: false,
        strength: 0,
        errors: { password: '', confirmation: '' },

        rules: {
          password(v) {
            if (!v) return 'Password is required.';
            if (v.length < 8) return 'At least 8 characters.';
            if (!/[A-Z]/.test(v)) return 'Include uppercase letter.';
            if (!/[a-z]/.test(v)) return 'Include lowercase letter.';
            if (!/[0-9]/.test(v)) return 'Include a number.';
            return '';
          },
          confirmation(v, pwd) {
            if (!v) return 'Please confirm your password.';
            if (v !== pwd) return 'Passwords do not match.';
            return '';
          },
        },

        updateStrength() {
          let p = this.password;
          let score = 0;
          if (p.length >= 8) score++;
          if (/[A-Z]/.test(p)) score++;
          if (/[a-z]/.test(p)) score++;
          if (/[0-9]/.test(p)) score++;
          this.strength = score;
        },

        touch(field) {
          if (field === 'confirmation') {
            this.errors.confirmation = this.rules.confirmation(this.confirmation, this.password);
          } else {
            this.errors[field] = this.rules[field](this[field]);
          }
        },

        submit(form) {
          this.errors.password = this.rules.password(this.password);
          this.errors.confirmation = this.rules.confirmation(this.confirmation, this.password);

          if (this.errors.password || this.errors.confirmation) return;

          this.loading = true;
          form.submit();
        },
      }));
    });
  </script>
</x-guest-layout>