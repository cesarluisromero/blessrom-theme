<?php
defined('ABSPATH') || exit;

// Variables que normalmente inyecta WooCommerce:
$available_gateways = WC()->payment_gateways()
  ? WC()->payment_gateways()->get_available_payment_gateways()
  : [];

$order_button_text = apply_filters('woocommerce_order_button_text', __('Place order', 'woocommerce'));

echo \Roots\view('woocommerce.checkout.payment', [
  'available_gateways' => $available_gateways,
  'order_button_text'  => $order_button_text,
])->render();
