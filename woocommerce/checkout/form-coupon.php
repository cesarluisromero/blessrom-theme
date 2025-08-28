<?php
defined('ABSPATH') || exit;

if ( ! wc_coupons_enabled() ) {
  return;
}

echo \Roots\view('woocommerce.checkout.form-coupon')->render();
