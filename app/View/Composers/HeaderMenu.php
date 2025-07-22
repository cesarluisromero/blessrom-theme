<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class HeaderMenu extends Composer
{
    protected static $views = [
        'partials.desktop-menu',
        'partials.mobile-menu',
    ];

    public function with()
    {
        $product_categories = get_terms([
            'taxonomy'   => 'product_cat',
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true,
        ]);

        return [
            'categories_by_parent' => collect($product_categories)->groupBy('parent'),
        ];
    }
}
