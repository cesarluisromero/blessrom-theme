{{-- Método de pago --}}
<div id="payment" class="woocommerce-checkout-payment w-full mt-6">
  @if (WC()->cart->needs_payment())
    @php
      do_action('woocommerce_review_order_before_payment');
      $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
    @endphp

    @if (!empty($available_gateways))
      <ul class="space-y-5">
        @foreach ($available_gateways as $gateway)
          <li class="border p-5 rounded-xl shadow-sm bg-white transition-all duration-200
              {{ !empty($gateway->chosen) ? 'border-blue-600 ring-2 ring-blue-200' : 'border-gray-200' }}">
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="radio"
                     name="payment_method"
                     class="input-radio"
                     id="payment_method_{{ $gateway->id }}"
                     value="{{ esc_attr($gateway->id) }}"
                     {{ !empty($gateway->chosen) ? 'checked' : '' }}
                     data-order_button_text="{{ esc_attr($gateway->order_button_text) }}">
              <span class="font-medium">{{ $gateway->get_title() }}</span>
            </label>

            @if ($gateway->has_fields() || $gateway->get_description())
              <div class="payment_box payment_method_{{ $gateway->id }}" @if(empty($gateway->chosen)) style="display:none;" @endif>
                @if ($gateway->get_description())
                  <div>{!! wp_kses_post($gateway->get_description()) !!}</div>
                @endif
                <div>
                  @php $gateway->payment_fields(); @endphp
                </div>
              </div>
            @endif
          </li>
        @endforeach
      </ul>
    @else
      <div class="woocommerce-notice woocommerce-notice--info">
        {!! wc_no_payment_methods_message() !!}
      </div>
    @endif

    @php do_action('woocommerce_review_order_after_payment'); @endphp
  @endif
</div>
