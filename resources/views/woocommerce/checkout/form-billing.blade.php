{{-- resources/views/woocommerce/checkout/form-billing.blade.php --}}
@php
  defined('ABSPATH') || exit;

  /** @var WC_Checkout $checkout */
  $checkout = isset($checkout) && is_object($checkout) ? $checkout : WC()->checkout();

  $needs_shipping = WC()->cart && WC()->cart->needs_shipping();
  $ship_to_billing_only = wc_ship_to_billing_address_only();

  $fields = $checkout->get_checkout_fields('billing') ?: [];
@endphp

<div class="woocommerce-billing-fields">
  <h3 class="text-lg font-semibold mb-4">
    {{ ($ship_to_billing_only && $needs_shipping) ? __('Billing & Shipping', 'woocommerce') : __('Billing details', 'woocommerce') }}
  </h3>

  @php do_action('woocommerce_before_checkout_billing_form', $checkout); @endphp

  <div class="woocommerce-billing-fields__field-wrapper space-y-0">
    {{-- GRID superior para nombre/apellido --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @if(isset($fields['billing_first_name']))
        @php woocommerce_form_field('billing_first_name', $fields['billing_first_name'], $checkout->get_value('billing_first_name')); @endphp
        @unset($fields['billing_first_name'])
      @endif

      @if(isset($fields['billing_last_name']))
        @php woocommerce_form_field('billing_last_name', $fields['billing_last_name'], $checkout->get_value('billing_last_name')); @endphp
        @unset($fields['billing_last_name'])
      @endif
    </div>

	


	

    {{-- País (full) --}}
    @if(isset($fields['billing_country']))
      @php woocommerce_form_field('billing_country', $fields['billing_country'], $checkout->get_value('billing_country')); @endphp
      @unset($fields['billing_country'])
    @endif

    {{-- Dirección 1 y 2 (full o grid) --}}
    @if(isset($fields['billing_address_1']))
      @php woocommerce_form_field('billing_address_1', $fields['billing_address_1'], $checkout->get_value('billing_address_1')); @endphp
      @unset($fields['billing_address_1'])
    @endif

    @if(isset($fields['billing_address_2']))
      @php woocommerce_form_field('billing_address_2', $fields['billing_address_2'], $checkout->get_value('billing_address_2')); @endphp
      @unset($fields['billing_address_2'])
    @endif

    {{-- Ciudad / Estado / Código postal (grid) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      @if(isset($fields['billing_city']))
        @php woocommerce_form_field('billing_city', $fields['billing_city'], $checkout->get_value('billing_city')); @endphp
        @unset($fields['billing_city'])
      @endif

      @if(isset($fields['billing_state']))
        @php woocommerce_form_field('billing_state', $fields['billing_state'], $checkout->get_value('billing_state')); @endphp
        @unset($fields['billing_state'])
      @endif

      @if(isset($fields['billing_postcode']))
        @php woocommerce_form_field('billing_postcode', $fields['billing_postcode'], $checkout->get_value('billing_postcode')); @endphp
        @unset($fields['billing_postcode'])
      @endif
    </div>

    {{-- Teléfono / Email (grid) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @if(isset($fields['billing_phone']))
        @php woocommerce_form_field('billing_phone', $fields['billing_phone'], $checkout->get_value('billing_phone')); @endphp
        @unset($fields['billing_phone'])
      @endif

      @if(isset($fields['billing_email']))
        @php woocommerce_form_field('billing_email', $fields['billing_email'], $checkout->get_value('billing_email')); @endphp
        @unset($fields['billing_email'])
      @endif
    </div>

    {{-- Cualquier otro campo de facturación personalizado que no hayamos renderizado arriba --}}
    @foreach($fields as $key => $field)
      @php woocommerce_form_field($key, $field, $checkout->get_value($key)); @endphp
    @endforeach
  </div>

  @php do_action('woocommerce_after_checkout_billing_form', $checkout); @endphp
</div>

{{-- Crear cuenta / Campos de cuenta --}}
@if (!is_user_logged_in() && $checkout->is_registration_enabled())
  <div class="woocommerce-account-fields mt-6 p-4 rounded-xl border border-slate-200 bg-slate-50">
    @if (!$checkout->is_registration_required())
      @php
        $create_checked = (
          true === $checkout->get_value('createaccount')
          || true === apply_filters('woocommerce_create_account_default_checked', false)
        );
      @endphp

      <p class="form-row form-row-wide create-account flex items-center gap-2 mb-4">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox inline-flex items-center gap-2">
          <input
            class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-0"
            id="createaccount"
            type="checkbox"
            name="createaccount"
            value="1"
            @if ($create_checked) checked="checked" @endif
          />
          <span class="text-sm text-slate-700">{{ __('Create an account?', 'woocommerce') }}</span>
        </label>
      </p>
    @endif

    @php do_action('woocommerce_before_checkout_registration_form', $checkout); @endphp

    @if ($checkout->get_checkout_fields('account'))
      <div class="create-account grid grid-cols-1 md:grid-cols-2 gap-4">
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
