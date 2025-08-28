{{-- resources/views/woocommerce/checkout/form-billing.blade.php --}}
@php
  defined('ABSPATH') || exit;

  /** @var WC_Checkout $checkout */
  $checkout = isset($checkout) && is_object($checkout) ? $checkout : WC()->checkout();

  $needs_shipping        = WC()->cart && WC()->cart->needs_shipping();
  $ship_to_billing_only  = wc_ship_to_billing_address_only();

  // 1) Toma los campos UNA sola vez
  $fields = $checkout->get_checkout_fields('billing') ?: [];

  // 2) Helper para renderizar y luego quitar del arreglo (evita duplicados)
  $renderField = function (string $key, array $extraWrapperClasses = []) use (&$fields, $checkout) {
    if (!isset($fields[$key])) return;

    // Permite añadir clases Tailwind al <p class="form-row ..."> contenedor
    $field = $fields[$key];
    $field['class'] = array_values(array_unique(array_merge(
      isset($field['class']) ? (array) $field['class'] : [],
      $extraWrapperClasses
    )));

    woocommerce_form_field($key, $field, $checkout->get_value($key));
    unset($fields[$key]); // <- crucial para que no aparezca de nuevo
  };
@endphp

<div class="woocommerce-billing-fields">
  <h3 class="text-lg font-semibold mb-4">
    {{ ($ship_to_billing_only && $needs_shipping) ? __('Billing & Shipping', 'woocommerce') : __('Billing details', 'woocommerce') }}
  </h3>

  @php do_action('woocommerce_before_checkout_billing_form', $checkout); @endphp

  <div class="woocommerce-billing-fields__field-wrapper space-y-0">
    {{-- Ejemplo de layout con grid: Nombres/Apellidos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @php $renderField('billing_first_name'); @endphp
      @php $renderField('billing_last_name');  @endphp
    </div>

    {{-- Empresa --}}
    @php $renderField('billing_company'); @endphp

    {{-- ⚠️ AQUÍ donde quieras: DNI/RUC --}}
    @php
      // Puedes forzar que ocupe una columna o dos:
      //   - una col: []
      //   - dos cols en md+: ['md:col-span-2']
      $renderField('billing_document', ['md:col-span-2']);
    @endphp

    {{-- País --}}
    @php $renderField('billing_country'); @endphp

    {{-- Dirección --}}
    @php $renderField('billing_address_1'); @endphp
    @php $renderField('billing_address_2'); @endphp

    {{-- Ciudad / Estado / Código Postal --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      @php $renderField('billing_city');     @endphp
      @php $renderField('billing_state');    @endphp
      @php $renderField('billing_postcode'); @endphp
    </div>

    {{-- Teléfono / Email --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @php $renderField('billing_phone'); @endphp
      @php $renderField('billing_email'); @endphp
    </div>

    {{-- Cualquier otro campo extra de billing que no hayamos mostrado arriba --}}
    @foreach ($fields as $key => $field)
      @php woocommerce_form_field($key, $field, $checkout->get_value($key)); @endphp
    @endforeach
  </div>

  @php do_action('woocommerce_after_checkout_billing_form', $checkout); @endphp
</div>

{{-- Sección de crear cuenta (igual que antes) --}}
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
