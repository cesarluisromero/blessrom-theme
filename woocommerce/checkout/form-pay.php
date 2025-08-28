<?php
defined('ABSPATH') || exit;

/** @var WC_Order|null $order */
$order = (isset($order) && is_a($order, 'WC_Order'))
  ? $order
  : (function () {
      $order_id = absint(get_query_var('order-pay'));
      return $order_id ? wc_get_order($order_id) : null;
    })();

if (!$order) {
  return;
}

$available_gateways = WC()->payment_gateways()
  ? WC()->payment_gateways()->get_available_payment_gateways()
  : [];

$order_button_text = apply_filters('woocommerce_pay_order_button_text', __('Pay for order', 'woocommerce'));
$totals            = $order->get_order_item_totals();

echo \Roots\view('woocommerce.checkout.form-pay', [
  'order'              => $order,
  'totals'             => $totals,
  'available_gateways' => $available_gateways,
  'order_button_text'  => $order_button_text,
])->render();
