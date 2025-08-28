{{-- resources/views/woocommerce/checkout/form-verify-email.blade.php --}}
@php defined('ABSPATH') || exit; @endphp

<form
  name="checkout"
  method="post"
  class="woocommerce-form woocommerce-verify-email space-y-4"
  action="{{ esc_url($verify_url) }}"
>
  @php wp_nonce_field('wc_verify_email', 'check_submission'); @endphp

  @if (!empty($failed_submission))
    @php
      wc_print_notice(
        esc_html__('We were unable to verify the email address you provided. Please try again.', 'woocommerce'),
        'error'
      );
    @endphp
  @endif

  <p class="text-sm text-slate-700">
    @php
      printf(
        /* translators: 1: opening login link 2: closing login link */
        esc_html__('To view this page, you must either %1$slogin%2$s or verify the email address associated with the order.', 'woocommerce'),
        '<a class="underline font-medium" href="' . esc_url( wc_get_page_permalink('myaccount') ) . '">',
        '</a>'
      );
    @endphp
  </p>

  <p class="form-row">
    <label for="email" class="block mb-1 text-sm font-medium text-slate-700">
      {{ __('Email address', 'woocommerce') }}&nbsp;<span class="required">*</span>
    </label>
    <input
      type="email"
      class="input-text w-full rounded-xl border border-slate-300 focus:border-slate-400 focus:ring-0 text-sm py-2.5 px-3 bg-white"
      name="email"
      id="email"
      autocomplete="email"
      required
    />
  </p>

  <p class="form-row">
    <button
      type="submit"
      class="woocommerce-button button {{ wc_wp_theme_get_element_class_name('button') ? wc_wp_theme_get_element_class_name('button') : '' }} rounded-xl px-4 py-2 bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
      name="verify"
      value="1"
    >
      {{ __('Verify', 'woocommerce') }}
    </button>
  </p>
</form>
