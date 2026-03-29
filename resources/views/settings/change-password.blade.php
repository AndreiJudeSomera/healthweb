@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<main class="p-6 bg-gray-50 min-h-screen">
  <div class="max-w-lg mx-auto">

    <div class="mb-5 flex items-center gap-3">
      <a href="{{ route('settings.setting') }}" class="text-gray-500 hover:text-blue-700 transition">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <h1 class="text-xl font-semibold text-[#1F2B5B]">Change Password</h1>
    </div>

    @if ($errors->updatePassword->any())
      <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm space-y-1">
        @foreach ($errors->updatePassword->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

 <div x-data="passwordForm()" class="bg-white shadow-md rounded-2xl p-6">
      <form method="POST" action="{{ route('settings.password.update') }}">
        @csrf
        @method('PUT')

        <div class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
            <input type="password" name="current_password" required autocomplete="current-password"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>

            <div class="relative">
              <input
                :type="showPwd ? 'text' : 'password'"
                name="password"
                x-model="password"
                @input="updateStrength()"
                required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm pr-10 focus:outline-none focus:ring-2 focus:ring-blue-400"
              >

              <button type="button" @click="showPwd = !showPwd"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                <i :class="showPwd ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
              </button>
            </div>

            <!-- Strength Bar -->
            <div class="flex gap-1 mt-2" x-show="password.length > 0" x-cloak>
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

              <span class="text-xs ml-2 font-medium"
                :class="strength === 1 ? 'text-red-500'
                : strength === 2 ? 'text-amber-500'
                : strength === 3 ? 'text-yellow-600'
                : 'text-emerald-600'"
                x-text="['', 'Weak', 'Fair', 'Good', 'Strong'][strength]">
              </span>
            </div>

            <!-- Rules (optional but nice UX) -->
            <ul class="text-xs text-gray-400 flex flex-wrap gap-3 mt-1" x-show="password.length > 0" x-cloak>
              <li :class="password.length >= 8 ? 'text-emerald-600' : ''">8+ chars</li>
              <li :class="/[A-Z]/.test(password) ? 'text-emerald-600' : ''">Uppercase</li>
              <li :class="/[a-z]/.test(password) ? 'text-emerald-600' : ''">Lowercase</li>
              <li :class="/[0-9]/.test(password) ? 'text-emerald-600' : ''">Number</li>
            </ul>
          </div>


          <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>

          <div class="relative">
            <input
              :type="showPwdConfirm ? 'text' : 'password'"
              name="password_confirmation"
              x-model="passwordConfirm"
              required
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm pr-10 focus:outline-none focus:ring-2 focus:ring-blue-400"
            >

            <button type="button" @click="showPwdConfirm = !showPwdConfirm"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
              <i :class="showPwdConfirm ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
            </button>
          </div>

          <!-- Match Error -->
          <p class="text-xs text-red-500 mt-1"
            x-show="passwordConfirm && passwordConfirm !== password">
            Passwords do not match
          </p>
        </div>

        <div class="mt-6 flex justify-end">
          <button type="submit"
            class="px-5 py-2 bg-[#1F2B5B] text-white text-sm font-medium rounded-lg hover:bg-[#162046] transition">
            Update Password
          </button>
        </div>
      </form>
    </div>

  </div>
</main>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('passwordForm', () => ({
    password: '',
    passwordConfirm: '',
    showPwd: false,
    showPwdConfirm: false,
    strength: 0,

    updateStrength() {
      let p = this.password;
      let score = 0;

      if (p.length >= 8) score++;
      if (/[A-Z]/.test(p)) score++;
      if (/[a-z]/.test(p)) score++;
      if (/[0-9]/.test(p)) score++;

      this.strength = score;
    }
  }));
});
</script>
@endsection
