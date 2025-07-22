<?php
defined('ABSPATH') || exit;

$customer_id = get_current_user_id();

$get_addresses = wc_ship_to_billing_address_only() || ! wc_shipping_enabled()
  ? apply_filters('woocommerce_my_account_get_addresses', ['billing' => __('Billing address', 'woocommerce')], $customer_id)
  : apply_filters('woocommerce_my_account_get_addresses', [
      'billing' => __('Billing address', 'woocommerce'),
      'shipping' => __('Shipping address', 'woocommerce'),
    ], $customer_id);

echo \Roots\view('woocommerce.myaccount.my-address', [
  'get_addresses' => $get_addresses,
  'customer_id'   => $customer_id,
])->render();
