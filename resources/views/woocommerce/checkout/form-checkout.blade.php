{{-- resources/views/woocommerce/checkout/form-checkout.blade.php --}}
@extends('layouts.app')
@section('content')

    @php if (!defined('ABSPATH')) exit; @endphp
    @include('partials.checkout-login-warning')
    @php  
        if (function_exists('WC') && WC()->checkout()) {
            do_action('woocommerce_before_checkout_form', WC()->checkout());
        }
    @endphp

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="{{ esc_url(wc_get_checkout_url()) }}" enctype="multipart/form-data">
        
        
            <h1 class="text-3xl font-bold text-center mb-10 text-gray-800">Finalizar compra</h1>
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">     
                {{-- Columna izquierda --}}
                
                    {{-- Facturación --}}
                    <div class="bg-gray-50 rounded-xl shadow p-4 md:p-6">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700">Datos de Envío y facturación</h2>
                        <div class="[&_input]:form-input [&_select]:form-select [&_textarea]:form-textarea">
                            @include('partials.checkout-billing-fields')
                        </div>
                    </div>
                

                {{-- Columna derecha: Resumen del pedido y pago --}}
                    {{-- Antes del resumen --}}
                    @php do_action('woocommerce_checkout_before_order_review'); @endphp
                        <div x-data="{ loading: false }" class="bg-gray-50 rounded-xl shadow p-4 md:p-6 w-full">                                    
                            
                                {{-- Resumen del pedido --}}
                                <div id="order_review" class="w-full">
                                    @include('woocommerce.checkout.partials.review-order')                            
                                </div>
                                {{-- Método de pago (WooCommerce hook) --}}
                                <div class="w-full">                            
                                    @include('woocommerce.checkout.payment')
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
                        </div>
                    {{-- Después del resumen --}}
                    @php do_action('woocommerce_checkout_after_order_review'); @endphp
                    
                </div>
            </div>
    </form>

    @php do_action('woocommerce_after_checkout_form', WC()->checkout()); @endphp


@endsection
