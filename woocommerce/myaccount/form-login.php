<?php
/**
 * Login + Registration form delegador a Blade
 * 
 * Ruta: your-theme/woocommerce/myaccount/form-login.php
 */
defined('ABSPATH') || exit;

echo \Roots\view('woocommerce.myaccount.form-login', [
  'registration_enabled' => get_option('woocommerce_enable_myaccount_registration') === 'yes',
])->render();
