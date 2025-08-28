<?php
defined('ABSPATH') || exit;

// Igual que el template original: no mostrar si ya está logueado
// o si está desactivado el recordatorio de login en el checkout.
if ( is_user_logged_in() || 'no' === get_option('woocommerce_enable_checkout_login_reminder') ) {
  return;
}

echo \Roots\view('woocommerce.checkout.form-login')->render();
