{{-- resources/views/woocommerce/checkout/terms.blade.php --}}
@php defined('ABSPATH') || exit; @endphp

@if ( apply_filters('woocommerce_checkout_show_terms', true) && function_exists('wc_terms_and_conditions_checkbox_enabled') )
  @php do_action('woocommerce_checkout_before_terms_and_conditions'); @endphp

  <div class="woocommerce-terms-and-conditions-wrapper mt-4">
    @php
      /**
       * Inyecta: texto de privacidad y contenido de TyC.
       * @hooked wc_checkout_privacy_policy_text (20)
       * @hooked wc_terms_and_conditions_page_content (30)
       */
      do_action('woocommerce_checkout_terms_and_conditions');
    @endphp

    @if ( wc_terms_and_conditions_checkbox_enabled() )
      <p class="form-row validate-required mt-3">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox inline-flex items-start gap-2">
          <input
            type="checkbox"
            class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-0"
            name="terms"
            id="terms"
            @php checked( apply_filters('woocommerce_terms_is_checked_default', isset($_POST['terms'])), true ); @endphp
          />
          <span class="woocommerce-terms-and-conditions-checkbox-text">
            @php wc_terms_and_conditions_checkbox_text(); @endphp
          </span>&nbsp;
          <abbr class="required" title="{{ esc_attr__('required', 'woocommerce') }}">*</abbr>
        </label>

        <input type="hidden" name="terms-field" value="1" />
      </p>
    @endif
  </div>

  @php do_action('woocommerce_checkout_after_terms_and_conditions'); @endphp
@endif
