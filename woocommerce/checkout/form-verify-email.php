<?php
defined('ABSPATH') || exit;

/**
 * Woo normalmente pasa $failed_submission y $verify_url al template.
 * Los recogemos y les damos un fallback seguro.
 */

/** @var bool $failed_submission */
$failed_submission = isset($failed_submission) ? (bool) $failed_submission : false;

/** @var string $verify_url */
$verify_url = isset($verify_url) ? $verify_url : ( (isset($_SERVER['REQUEST_URI']) ? esc_url_raw(wp_unslash($_SERVER['REQUEST_URI'])) : wc_get_checkout_url()) );

echo \Roots\view('woocommerce.checkout.form-verify-email', [
  'failed_submission' => $failed_submission,
  'verify_url'        => $verify_url,
])->render();
