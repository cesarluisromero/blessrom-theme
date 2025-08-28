<?php
defined('ABSPATH') || exit;

/** @var WC_Payment_Gateway $gateway */
echo \Roots\view('woocommerce.checkout.payment-method', [
  'gateway' => $gateway,
])->render();
