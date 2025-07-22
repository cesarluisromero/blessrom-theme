<?php
/**
 * WooCommerce - Edit Account Blade Wrapper
 */

defined('ABSPATH') || exit;

// Pasa los datos necesarios a la vista Blade
echo \Roots\view('woocommerce.myaccount.form-edit-account', [
  'user' => wp_get_current_user(),
])->render();
