<?php
defined('ABSPATH') || exit;

/** @var WC_Order|false $order */
if (! isset($order) || (! $order instanceof WC_Order && false !== $order)) {
  // Fallback por si no nos pasaron $order
  $order_id = absint(get_query_var('order-received'));
  $order    = $order_id ? wc_get_order($order_id) : false;
}

echo \Roots\view('woocommerce.checkout.order-received', [
  'order' => $order,
])->render();
