{{-- resources/views/woocommerce/checkout/form-coupon.blade.php --}}
@php defined('ABSPATH') || exit; @endphp

<div class="woocommerce-form-coupon-toggle mb-4">
  @php
    // Mensaje con enlace que despliega el formulario (Woo escucha .showcoupon)
    wc_print_notice(
      apply_filters(
        'woocommerce_checkout_coupon_message',
        esc_html__('Have a coupon?', 'woocommerce') .
        ' <a href="#" role="button" aria-label="' . esc_attr__('Enter your coupon code', 'woocommerce') .
        '" aria-controls="woocommerce-checkout-form-coupon" aria-expanded="false" class="showcoupon underline font-medium">' .
        esc_html__('Click here to enter your code', 'woocommerce') .
        '</a>'
      ),
      'notice'
    );
  @endphp
</div>

<form
  class="checkout_coupon woocommerce-form-coupon flex flex-wrap items-end gap-3"
  method="post"
  style="display:none"
  id="woocommerce-checkout-form-coupon"
>
  <p class="form-row form-row-first grow">
    <label for="coupon_code" class="screen-reader-text">
      {{ __('Coupon:', 'woocommerce') }}
    </label>
    <input
      type="text"
      name="coupon_code"
      id="coupon_code"
      value=""
      class="input-text w-full rounded-xl border border-slate-300 focus:border-slate-400 focus:ring-0 text-sm py-2.5 px-3 bg-white"
      placeholder="{{ esc_attr__('Coupon code', 'woocommerce') }}"
      autocomplete="off"
    />
  </p>

  <p class="form-row form-row-last">
    <button
      type="submit"
      name="apply_coupon"
      value="{{ esc_attr__('Apply coupon', 'woocommerce') }}"
      class="button {{ wc_wp_theme_get_element_class_name('button') ? wc_wp_theme_get_element_class_name('button') : '' }} rounded-xl px-4 py-2 bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
    >
      {{ __('Apply coupon', 'woocommerce') }}
    </button>
  </p>

  <div class="clear"></div>
</form>
