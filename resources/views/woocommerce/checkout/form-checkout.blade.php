@extends('layouts.app')
@section('content')
	@php
	// Asegura instancia
	$checkout = isset($checkout) && is_object($checkout) ? $checkout : WC()->checkout();

	// Hooks previos
	do_action('woocommerce_before_checkout_form', $checkout);

	// Bloquea si requiere registro y el usuario no está logueado
	if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
		echo esc_html(apply_filters(
			'woocommerce_checkout_must_be_logged_in_message',
			__('You must be logged in to checkout.', 'woocommerce')
		));
		return;
	}
	@endphp

	<form
	name="checkout"
	method="post"
	class="checkout woocommerce-checkout"
	action="{{ esc_url( wc_get_checkout_url() ) }}"
	enctype="multipart/form-data"
	aria-label="{{ __('Checkout', 'woocommerce') }}"
	>
		
		<div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10"> 
			@php if ($checkout->get_checkout_fields()) do_action('woocommerce_checkout_before_customer_details'); @endphp
			<div class="bg-gray-50 rounded-xl shadow p-4 md:p-6">
				@if ($checkout->get_checkout_fields())
					@php do_action('woocommerce_checkout_before_customer_details'); @endphp
						<h2 class="text-xl font-semibold mb-4 text-gray-700">Datos de Envío y facturación</h2>
						<div class="[&_input]:form-input [&_select]:form-select [&_textarea]:form-textarea">
							@php do_action('woocommerce_checkout_billing'); @endphp

							@php do_action('woocommerce_checkout_shipping'); @endphp
						</div>							
					@php do_action('woocommerce_checkout_after_customer_details'); @endphp
				@endif
			</div>
			<div x-data="{ loading: false }" class="bg-gray-50 rounded-xl shadow p-4 md:p-6 w-full"> 
				@php do_action('woocommerce_checkout_before_order_review_heading'); @endphp

				<h3 id="order_review_heading">{{ __('Your order', 'woocommerce') }}</h3>

				@php do_action('woocommerce_checkout_before_order_review'); @endphp

				<div id="order_review" class="woocommerce-checkout-review-order">
					@php do_action('woocommerce_checkout_order_review'); @endphp
				</div>


				  {{-- Botón realizar pedido --}}    
                                <div class="w-full pt-4">                                                    
                                    @if (is_user_logged_in())
                                        {{-- Botón de compra normal para usuarios logueados --}}
                                        <button
                                            type="submit"
                                            id="place_order"
                                            name="woocommerce_checkout_place_order"
                                            x-data="{ loading: false }"
                                            @click="setTimeout(() => loading = true, 100)"
                                            class="button alt w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl text-sm transition-all duration-200 flex items-center justify-center"
                                        >
                                            <template x-if="loading">
                                                <svg class="w-5 h-5 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                                </svg>
                                            </template>
                                            <span x-text="loading ? 'Procesando...' : 'Realizar el pedido'"></span>
                                        </button>
                                    @else
                                        {{-- Botón falso que redirige al login si no está logueado --}}
                                        <div class="flex flex-col space-y-3">
                                            <a
                                                href="{{ esc_url( wc_get_page_permalink('myaccount') . '?redirect_to=' . urlencode(wc_get_checkout_url()) ) }}"
                                                class="w-full block bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl text-sm text-center transition-all duration-200"
                                            >
                                                Inicia sesión para completar tu compra
                                            </a>
                                            <button
                                                type="submit"
                                                id="place_order_guest"
                                                name="woocommerce_checkout_place_order"
                                                class="button alt w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl text-sm transition-all duration-200"
                                            >
                                                Pagar como invitado
                                            </button>
                                        </div>
                                    @endif 
                                </div>    



				@php do_action('woocommerce_checkout_after_order_review'); @endphp
			</div>
			
		</div>
</form>
	@php do_action('woocommerce_after_checkout_form', $checkout); @endphp
@endsection
