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
                        {{ $gateway->chosen ? 'border-blue-600 ring-2 ring-blue-200' : 'border-gray-200' }}">
                        
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input 
                                type="radio" 
                                name="payment_method" 
                                value="{{ $gateway->id }}" 
                                class="accent-blue-600 focus:ring-0 focus:outline-none"
                                {{ $gateway->chosen ? 'checked' : '' }} 
                                @change="document.body.dispatchEvent(new Event('update_checkout'))"
                            >
                            <span class="text-sm font-medium text-gray-800">
                                {{ $gateway->get_title() }}
                            </span>
                        </label>

                        @if ($gateway->has_fields() || $gateway->get_description())
                            <div class="mt-4 space-y-2 text-sm text-gray-600">
                                @if ($gateway->get_description())
                                    <div>{!! $gateway->get_description() !!}</div>
                                @endif
                                <div>
                                    {!! $gateway->payment_fields() !!}
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
