<?php
defined( 'ABSPATH' ) || exit;

/* 1) Página actual */
$current_page = max(
    1,
    absint( get_query_var( 'orders' ) ?: get_query_var( 'paged' ) ) // ← aquí el cambio
);

/* 2) Pedidos por página */
$per_page = apply_filters( 'woocommerce_my_account_my_orders_per_page', 10 );

/* 3) Consulta paginada de pedidos */
$customer_orders = wc_get_orders(
    apply_filters(
        'woocommerce_my_account_my_orders_query',
        [
            'customer' => get_current_user_id(),
            'paginate' => true,
            'page'     => $current_page,
            'limit'    => $per_page, // si tu Woo < 9 usa 'per_page' en vez de 'limit'
        ]
    )
);

/* 4) Variables para la vista */
$has_orders      = $customer_orders && $customer_orders->total > 0;
$wp_button_class = wc_wp_theme_get_element_class_name( 'button' );
$wp_button_class = $wp_button_class ? ' ' . $wp_button_class : '';

echo \Roots\view( 'woocommerce.myaccount.orders', [
    'customer_orders' => $customer_orders,
    'has_orders'      => $has_orders,
    'current_page'    => $current_page,
    'per_page'        => $per_page,
    'wp_button_class' => $wp_button_class,
] )->render();
