<?php

namespace App\Providers;

use Roots\Acorn\Sage\SageServiceProvider;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        parent::boot();
        
        // Incluir acciones personalizadas del carrito
        require_once get_theme_file_path('app/cart-actions.php');

        add_filter('template_include', function ($template) {
            if (is_post_type_archive('product') || is_tax('product_cat')) {
              return get_theme_file_path('resources/views/woocommerce/archive-product.php');
            }
            return $template;
          }, 100);
          
          

    }

    protected $composers = [
    \App\View\Composers\HeaderMenu::class,
    ];

}
