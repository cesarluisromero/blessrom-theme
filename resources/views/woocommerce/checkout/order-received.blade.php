{{-- resources/views/woocommerce/checkout/order-received.blade.php --}}
@php
  defined('ABSPATH') || exit;

  /** @var WC_Order|false $order */
  // Mensaje filtrable, igual que el template original
  $message = apply_filters(
    'woocommerce_thankyou_order_received_text',
    esc_html(__('Thank you. Your order has been received.', 'woocommerce')),
    $order
  );
@endphp

<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
  {!! $message !!}
</p>
