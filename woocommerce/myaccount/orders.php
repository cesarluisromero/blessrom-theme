<?php
defined( 'ABSPATH' ) || exit;

/**
 * Réplica de la lógica del template nativo.
 * Ajusta si necesitas otros filtros.
 */
$current_page    = max( 1, absint( get_query_var( 'paged' ) ) );
$customer_orders = wc_get_orders(
    apply_filters(
        'woocommerce_my_account_my_orders_query',
        [
            'customer' => get_current_user_id(),
            'page'     => $current_page,
            'paginate' => true,
        ]
    )
);

$has_orders = $customer_orders->total > 0;   // ← la variable que necesitas

echo \Roots\view('woocommerce.myaccount.orders', [
    'customer_orders' => $customer_orders,
    'has_orders'      => $has_orders,
    'current_page'    => $current_page,
])->render();
