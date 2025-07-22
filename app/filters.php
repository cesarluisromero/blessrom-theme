<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

add_filter('woocommerce_locate_template', function ($template, $template_name, $template_path) {
    // Redireccionar `archive-product.php` al archivo Blade
    if ($template_name === 'archive-product.php') {
      return get_theme_file_path() . '/resources/views/archive-product.blade.php';
    }
  
    return $template;
  }, 10, 3);
  