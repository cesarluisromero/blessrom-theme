<?php
/**
 * Lost password reset form (delegador hacia Blade).
 *
 * @version 9.2.0
 */

defined('ABSPATH') || exit;

echo \Roots\view('woocommerce.myaccount.form-reset-password', [
  'args' => $args,
])->render();
