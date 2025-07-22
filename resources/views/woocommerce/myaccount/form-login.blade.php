{{-- resources/views/woocommerce/myaccount/form-login.blade.php --}}
@extends('layouts.app')

@section('content')
@php do_action('woocommerce_before_customer_login_form') @endphp

<div class="max-w-5xl mx-auto my-12 grid grid-cols-1 md:grid-cols-2 gap-8">

  {{-- Login Form --}}
  <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
      {{ __('Login', 'woocommerce') }}
    </h2>

    <form class="space-y-6 woocommerce-form woocommerce-form-login login" method="post" novalidate>
      @php do_action('woocommerce_login_form_start') @endphp

      <div>
        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
          {{ __('Username or email address', 'woocommerce') }} <span class="text-red-500">*</span>
        </label>
        <input
          type="text"
          name="username"
          id="username"
          autocomplete="username"
          required
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"
          value="{{ old('username') }}"
        >
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
          {{ __('Password', 'woocommerce') }} <span class="text-red-500">*</span>
        </label>
        <input
          type="password"
          name="password"
          id="password"
          autocomplete="current-password"
          required
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"
        >
      </div>

      @php do_action('woocommerce_login_form') @endphp

      <div class="flex justify-between items-center">
        <label class="flex items-center gap-2 text-sm text-gray-600">
          <input class="form-checkbox" type="checkbox" name="rememberme" id="rememberme" value="forever">
          {{ __('Remember me', 'woocommerce') }}
        </label>

        <a href="{{ esc_url( wp_lostpassword_url() ) }}" class="text-sm text-primary hover:underline">
          {{ __('Lost your password?', 'woocommerce') }}
        </a>
      </div>

      @php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ) @endphp

      <div>
        <button
          type="submit"
          name="login"
          value="{{ __('Log in', 'woocommerce') }}"
          class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition"
        >
          {{ __('Log in', 'woocommerce') }}
        </button>
      </div>

      @php do_action('woocommerce_login_form_end') @endphp
    </form>
  </div>

  {{-- Registration Form --}}
  @if ($registration_enabled)
    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
        {{ __('Register', 'woocommerce') }}
      </h2>

      <form method="post" class="space-y-6 woocommerce-form woocommerce-form-register register">
        @php do_action('woocommerce_register_form_start') @endphp

        @if (get_option('woocommerce_registration_generate_username') === 'no')
          <div>
            <label for="reg_username" class="block text-sm font-medium text-gray-700 mb-1">
              {{ __('Username', 'woocommerce') }} <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              name="username"
              id="reg_username"
              autocomplete="username"
              required
              value="{{ old('username') }}"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"
            >
          </div>
        @endif

        <div>
          <label for="reg_email" class="block text-sm font-medium text-gray-700 mb-1">
            {{ __('Email address', 'woocommerce') }} <span class="text-red-500">*</span>
          </label>
          <input
            type="email"
            name="email"
            id="reg_email"
            autocomplete="email"
            required
            value="{{ old('email') }}"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"
          >
        </div>

        @if (get_option('woocommerce_registration_generate_password') === 'no')
          <div>
            <label for="reg_password" class="block text-sm font-medium text-gray-700 mb-1">
              {{ __('Password', 'woocommerce') }} <span class="text-red-500">*</span>
            </label>
            <input
              type="password"
              name="password"
              id="reg_password"
              autocomplete="new-password"
              required
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:outline-none"
            >
          </div>
        @else
          <p class="text-sm text-gray-600">
            {{ __('A link to set a new password will be sent to your email address.', 'woocommerce') }}
          </p>
        @endif

        @php do_action('woocommerce_register_form') @endphp
        @php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce') @endphp

        <div>
          <button
            type="submit"
            name="register"
            value="{{ __('Register', 'woocommerce') }}"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition"

          >
            {{ __('Register', 'woocommerce') }}
          </button>
        </div>

        @php do_action('woocommerce_register_form_end') @endphp
      </form>
    </div>
  @endif
</div>

@php do_action('woocommerce_after_customer_login_form') @endphp

@endsection
