<?php
/**
 * Wrapper Blade – View single order
 */
defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce ya prepara $order y $order_id antes de incluir
 * este template. Si no existieran, avisa.
 */
if ( ! isset( $order ) || ! $order instanceof WC_Order ) {
    wc_print_notice( esc_html__( 'Order not found.', 'woocommerce' ), 'error' );
    return;
}

echo \Roots\view( 'woocommerce.myaccount.view-order', [
    'order'     => $order,
    'order_id'  => $order->get_id(),
    'notes'     => $order->get_customer_order_notes(),
] )->render();
