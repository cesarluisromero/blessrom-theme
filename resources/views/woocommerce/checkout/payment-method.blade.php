{{-- resources/views/woocommerce/checkout/payment-method.blade.php --}}
@php
  defined('ABSPATH') || exit;
  /** @var WC_Payment_Gateway $gateway */
  $id    = esc_attr($gateway->id);
  $title = $gateway->get_title(); // HTML permitido por Woo
  $icon  = $gateway->get_icon();  // HTML permitido por Woo
@endphp

<li class="wc_payment_method payment_method_{{ $id }} p-3 border rounded-xl">
  <input
    id="payment_method_{{ $id }}"
    type="radio"
    class="input-radio"
    name="payment_method"
    value="{{ $id }}"
    @checked($gateway->chosen)
    data-order_button_text="{{ esc_attr($gateway->order_button_text) }}"
  />

  <label for="payment_method_{{ $id }}" class="font-medium inline-flex items-center gap-2">
    {!! $title !!} {!! $icon !!}
  </label>

  @if ($gateway->has_fields() || $gateway->get_description())
    <div
      class="payment_box payment_method_{{ $id }} mt-3"
      @if(! $gateway->chosen) style="display:none;" @endif
    >
      {!! $gateway->get_description() !!}
      @php $gateway->payment_fields(); @endphp
    </div>
  @endif
</li>
