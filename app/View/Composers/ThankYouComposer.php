<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WC_Order;

class ThankYouComposer extends Composer
{
    protected static $views = ['woocommerce.thankyou'];

    public function with()
    {
        $order_id = absint(get_query_var('order-received'));
        $order = wc_get_order($order_id);

        return [
            'order' => $order,
        ];
    }
}
