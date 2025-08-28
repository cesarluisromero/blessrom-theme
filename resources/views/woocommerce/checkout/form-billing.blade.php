{{-- resources/views/woocommerce/checkout/form-billing.blade.php --}}
@php
  defined('ABSPATH') || exit;

  /** @var WC_Checkout $checkout */
  $checkout = isset($checkout) && is_object($checkout) ? $checkout : WC()->checkout();

  $needs_shipping = WC()->cart && WC()->cart->needs_shipping();
  $ship_to_billing_only = wc_ship_to_billing_address_only();
@endphp

<div class="woocommerce-billing-fields">
  @if ($ship_to_billing_only && $needs_shipping)
  <h1 class="text-3xl font-bold text-center mb-10 text-gray-800">Esto ES</h1>
    <h3>{{ __('Billing & Shipping', 'woocommerce') }}</h3>
  @else
    <h3>{{ __('Billing details', 'woocommerce') }}</h3>
  @endif

  @php do_action('woocommerce_before_checkout_billing_form', $checkout); @endphp

  <div class="woocommerce-billing-fields__field-wrapper">
    @php
      $fields = $checkout->get_checkout_fields('billing');
      foreach ($fields as $key => $field) {
        woocommerce_form_field($key, $field, $checkout->get_value($key));
      }
    @endphp
  </div>

  @php do_action('woocommerce_after_checkout_billing_form', $checkout); @endphp
</div>

@if (!is_user_logged_in() && $checkout->is_registration_enabled())
  <div class="woocommerce-account-fields">
    @if (!$checkout->is_registration_required())
      @php
        $create_checked = (
          true === $checkout->get_value('createaccount')
          || true === apply_filters('woocommerce_create_account_default_checked', false)
        );
      @endphp

      <p class="form-row form-row-wide create-account">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
          <input
            class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
            id="createaccount"
            type="checkbox"
            name="createaccount"
            value="1"
            @if ($create_checked) checked="checked" @endif
          />
          <span>{{ __('Create an account?', 'woocommerce') }}</span>
        </label>
      </p>
    @endif

    @php do_action('woocommerce_before_checkout_registration_form', $checkout); @endphp

    @if ($checkout->get_checkout_fields('account'))
      <div class="create-account">
        @php
          foreach ($checkout->get_checkout_fields('account') as $key => $field) {
            woocommerce_form_field($key, $field, $checkout->get_value($key));
          }
        @endphp
        <div class="clear"></div>
      </div>
    @endif

    @php do_action('woocommerce_after_checkout_registration_form', $checkout); @endphp
  </div>
@endif
