<?php
defined('ABSPATH') || exit;

/** @var WC_Checkout $checkout */
$checkout = isset($checkout) && is_a($checkout, 'WC_Checkout') ? $checkout : WC()->checkout();

echo \Roots\view('woocommerce.checkout.form-shipping', [
  'checkout' => $checkout,
])->render();
