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
			<div class="bg-gray-50 rounded-xl shadow p-4 md:p-6 w-full"> 
				@php do_action('woocommerce_checkout_before_order_review_heading'); @endphp

				<h3 id="order_review_heading">{{ __('Your order', 'woocommerce') }}</h3>

				@php do_action('woocommerce_checkout_before_order_review'); @endphp

				<div id="order_review" class="woocommerce-checkout-review-order">
					@php do_action('woocommerce_checkout_order_review'); @endphp
				</div>

				@php do_action('woocommerce_checkout_after_order_review'); @endphp
			</div>
			
		</div>
</form>
	@php do_action('woocommerce_after_checkout_form', $checkout); @endphp
@endsection
