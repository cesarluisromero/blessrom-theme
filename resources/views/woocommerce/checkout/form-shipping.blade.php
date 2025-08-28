{{-- resources/views/woocommerce/checkout/form-shipping.blade.php --}}
@php
  defined('ABSPATH') || exit;

  /** @var WC_Checkout $checkout */
  $checkout = isset($checkout) && is_object($checkout) ? $checkout : WC()->checkout();

  $needs_shipping_address = (true === WC()->cart->needs_shipping_address());
@endphp

<div class="woocommerce-shipping-fields">
  @if ($needs_shipping_address)
    <h3 id="ship-to-different-address" class="mb-3">
      <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox inline-flex items-center gap-2">
        <input
          id="ship-to-different-address-checkbox"
          class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
          type="checkbox"
          name="ship_to_different_address"
          value="1"
          @checked( apply_filters('woocommerce_ship_to_different_address_checked', 'shipping' === get_option('woocommerce_ship_to_destination') ? 1 : 0) )
        />
        <span>{{ __('Ship to a different address?', 'woocommerce') }}</span>
      </label>
    </h3>

    <div class="shipping_address">
      @php do_action('woocommerce_before_checkout_shipping_form', $checkout); @endphp

      @php
        // Campos de envío y helper para orden/maquetación sin duplicados
        $fields = $checkout->get_checkout_fields('shipping') ?: [];
        $renderField = function (string $key, array $extraWrapperClasses = []) use (&$fields, $checkout) {
          if (!isset($fields[$key])) return;
          $f = $fields[$key];
          $f['class'] = array_values(array_unique(array_merge($f['class'] ?? [], $extraWrapperClasses)));
          woocommerce_form_field($key, $f, $checkout->get_value($key));
          unset($fields[$key]);
        };
      @endphp

      <div class="woocommerce-shipping-fields__field-wrapper space-y-0">
        {{-- Nombre / Apellido --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          @php $renderField('shipping_first_name'); @endphp
          @php $renderField('shipping_last_name');  @endphp
        </div>

        {{-- Empresa (si existe) --}}
        @php $renderField('shipping_company'); @endphp

        {{-- País --}}
        @php $renderField('shipping_country'); @endphp

        {{-- Dirección --}}
        @php $renderField('shipping_address_1'); @endphp
        @php $renderField('shipping_address_2'); @endphp

        {{-- Ciudad / Estado / Código Postal --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          @php $renderField('shipping_city');     @endphp
          @php $renderField('shipping_state');    @endphp
          @php $renderField('shipping_postcode'); @endphp
        </div>

        {{-- Cualquier otro campo de envío pendiente (p.ej. teléfono si algún plugin lo añade) --}}
        @foreach ($fields as $key => $field)
          @php woocommerce_form_field($key, $field, $checkout->get_value($key)); @endphp
        @endforeach
      </div>

      @php do_action('woocommerce_after_checkout_shipping_form', $checkout); @endphp
    </div>
  @endif
</div>

{{-- Información adicional / Notas del pedido --}}
<div class="woocommerce-additional-fields mt-6">
  @php do_action('woocommerce_before_order_notes', $checkout); @endphp

  @if ( apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes')) )
    @if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() )
      <h3 class="mb-3">{{ __('Additional information', 'woocommerce') }}</h3>
    @endif

    <div class="woocommerce-additional-fields__field-wrapper">
      @foreach ($checkout->get_checkout_fields('order') as $key => $field)
        @php woocommerce_form_field($key, $field, $checkout->get_value($key)); @endphp
      @endforeach
    </div>
  @endif

  @php do_action('woocommerce_after_order_notes', $checkout); @endphp
</div>
