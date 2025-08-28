<?php
defined('ABSPATH') || exit;

/** @var WC_Order|null $order */
// Woo normalmente pasa $order; por si acaso lo resolvemos.
if (! isset($order) || ! is_a($order, 'WC_Order')) {
  // Buscar por order-pay o order-received
  $order_id = absint(get_query_var('order-pay')) ?: absint(get_query_var('order-received'));
  $order    = $order_id ? wc_get_order($order_id) : null;
}
if (! $order) {
  return;
}

echo \Roots\view('woocommerce.checkout.order-receipt', [
  'order' => $order,
])->render();
