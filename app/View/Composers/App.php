<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use Illuminate\View\View;
use Roots\Acorn\View\Composer as AcornComposer;
class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Retrieve the site name.
     */
    public function siteName(): string
    {
        return get_bloginfo('name', 'display');
    }

    public function with()
    {
        $cartCount = 0;

        if (function_exists('WC') && did_action('wp_loaded')) {
            $woocommerce = WC();

            if ($woocommerce && $woocommerce->cart) {
            $cartCount = $woocommerce->cart->get_cart_contents_count();
            }
        }

        return [
            'cartCount' => $cartCount,
        ];
    }

}