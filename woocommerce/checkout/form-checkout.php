<?php
defined('ABSPATH') || exit;

$checkout = isset($checkout) && is_a($checkout, 'WC_Checkout') ? $checkout : WC()->checkout();

echo \Roots\view('woocommerce.checkout.form-checkout', [
  'checkout' => $checkout,
])->render();
