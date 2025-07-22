

@php do_action('woocommerce_before_reset_password_form') @endphp

<div class="max-w-md mx-auto mt-16 bg-white border border-gray-200 rounded-2xl shadow-lg p-8">
  <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">
    {{ __('Reset your password', 'woocommerce') }}
  </h2>

  <form method="post" class="space-y-6 woocommerce-ResetPassword lost_reset_password" x-data="{ submitting: false }">

    <p class="text-sm text-gray-600 text-center">
      {!! apply_filters('woocommerce_reset_password_message', __('Enter a new password below.', 'woocommerce')) !!}
    </p>

    <div>
      <label for="password_1" class="block text-sm font-medium text-gray-700">
        {{ __('New password', 'woocommerce') }} <span class="text-red-500">*</span>
      </label>
      <input
        type="password"
        name="password_1"
        id="password_1"
        required
        autocomplete="new-password"
        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
      >
    </div>

    <div>
      <label for="password_2" class="block text-sm font-medium text-gray-700">
        {{ __('Re-enter new password', 'woocommerce') }} <span class="text-red-500">*</span>
      </label>
      <input
        type="password"
        name="password_2"
        id="password_2"
        required
        autocomplete="new-password"
        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
      >
    </div>

    {{-- Hidden values --}}
    <input type="hidden" name="reset_key" value="{{ esc_attr($args['key']) }}">
    <input type="hidden" name="reset_login" value="{{ esc_attr($args['login']) }}">
    <input type="hidden" name="wc_reset_password" value="true">

    @php do_action('woocommerce_resetpassword_form') @endphp

    <div>
      
    <button
      type="submit"
      class="bg-blue-600 hover:bg-blue-700 text-white w-full py-3 rounded-lg text-center font-bold text-lg"
    >
      {{ __('Save new password', 'woocommerce') }}
    </button>

    </div>

    @php wp_nonce_field('reset_password', 'woocommerce-reset-password-nonce') @endphp
  </form>
</div>

@php do_action('woocommerce_after_reset_password_form') @endphp
