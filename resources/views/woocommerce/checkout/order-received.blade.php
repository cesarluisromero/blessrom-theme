{{-- resources/views/woocommerce/checkout/order-received.blade.php --}}
@php
  defined('ABSPATH') || exit;

  /** @var WC_Order|false $order */
  $message = apply_filters(
    'woocommerce_thankyou_order_received_text',
    esc_html(__('Thank you. Your order has been received.', 'woocommerce')),
    $order
  );

  $view_order_url = ( $order && method_exists($order, 'get_view_order_url') ) ? $order->get_view_order_url() : '';
  $shop_url       = wc_get_page_permalink('shop');
@endphp

<div class="rounded-2xl border border-green-200 bg-green-50 p-4 sm:p-5">
  <div class="flex items-start gap-3">
    {{-- Icono check --}}
    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" class="h-6 w-6 text-green-600">
        <path fill="currentColor" d="M9.55 17.8 4.75 13l1.4-1.4 3.4 3.4 7.3-7.3 1.4 1.4-8.7 8.7z"/>
      </svg>
    </span>

    <div>
      <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received m-0 text-green-800 text-base sm:text-lg">
        {!! $message !!}
      </p>
      <p class="mt-1 text-sm text-green-700">
        {{ __('Hemos enviado la confirmación a tu correo. Revisa los detalles abajo o en tu cuenta.', 'woocommerce') }}
      </p>
    </div>
  </div>

  {{-- Acciones opcionales --}}
  <div class="mt-4 flex flex-wrap gap-3">
    @if ($view_order_url)
      <a href="{{ esc_url($view_order_url) }}"
         class="button {{ wc_wp_theme_get_element_class_name('button') ? wc_wp_theme_get_element_class_name('button') : '' }}
                inline-flex items-center justify-center rounded-xl px-4 py-2 bg-green-600 text-white font-semibold
                hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">
        {{ __('View order', 'woocommerce') }}
      </a>
    @endif

    @if ($shop_url)
      <a href="{{ esc_url($shop_url) }}"
         class="inline-flex items-center justify-center rounded-xl px-4 py-2 border border-green-300 text-green-800 font-medium
                hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">
        {{ __('Continue shopping', 'woocommerce') }}
      </a>
    @endif>
  </div>
</div>
