<?php
defined('ABSPATH') || exit;

/** @var WC_Order|null $order */
// Woo normalmente pasa $order, pero por si acaso lo resolvemos:
if (! isset($order) || ! is_a($order, 'WC_Order')) {
  $order_id = absint(get_query_var('order-received'));
  $order    = $order_id ? wc_get_order($order_id) : null;
}

echo \Roots\view('woocommerce.checkout.thankyou', [
  'order' => $order,
])->render();
