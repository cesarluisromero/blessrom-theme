<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WC_Product_Query;

class FrontPage extends Composer
{
    protected static $views = [
        'front-page', // tu archivo front-page.blade.php
    ];

    public function with()
    {
        return [
            'products' => $this->getPopularProducts(),
        ];
    }

    protected function getPopularProducts()
    {
        $query = new WC_Product_Query([
            'limit' => 24,
            'orderby' => 'popularity',
            'order' => 'desc',
            'return' => 'objects',
        ]);

        return $query->get_products();
    }
}
