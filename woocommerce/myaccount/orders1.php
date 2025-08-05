<?php
/**
 * Wrapper para la vista Blade de “Mis pedidos”.
 * Ubicación: your-theme/woocommerce/myaccount/orders.php
 */

defined( 'ABSPATH' ) || exit;

/* ---------------------------------------------------------
 * 1) Paginación y consulta de pedidos del cliente
 * ------------------------------------------------------- */

// Página actual (endpoint “orders” usa la query-var 'paged')
$current_page = max( 1, absint( get_query_var( 'paged' ) ) );

// Pedidos por página (10 por defecto –  puedes filtrar)
$per_page = apply_filters( 'woocommerce_my_account_my_orders_per_page', 10 );

// Consulta de pedidos (la misma que usa WooCommerce)
$customer_orders = wc_get_orders(
    apply_filters(
        'woocommerce_my_account_my_orders_query',
        [
            'customer' => get_current_user_id(),
            'paginate' => true,
            'page'     => $current_page,
            'limit'    => $per_page,  // ≤ Woo 9.x
            // 'per_page' => $per_page, // ≤ Woo 8.x , usa la clave anterior si tu versión lo requiere
        ]
    )
);

/* ---------------------------------------------------------
 * 2) Variables que usará la vista
 * ------------------------------------------------------- */

// ¿Existen pedidos?
$has_orders = $customer_orders && $customer_orders->total > 0;

// Clase extra que WordPress añade a los <button>
// (en FSE suele ser “wp-element-button”, puede ser cadena vacía)
$wp_button_class = wc_wp_theme_get_element_class_name( 'button' );
$wp_button_class = $wp_button_class ? ' ' . $wp_button_class : '';

/* ---------------------------------------------------------
 * 3) Renderizar la vista Blade
 * ------------------------------------------------------- */

echo \Roots\view( 'woocommerce.myaccount.orders', [
    'customer_orders' => $customer_orders,
    'has_orders'      => $has_orders,
    'current_page'    => $current_page,
    'per_page'        => $per_page,
    'wp_button_class' => $wp_button_class,
] )->render();
