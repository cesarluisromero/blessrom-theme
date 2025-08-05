@extends('layouts.app')

@section('content')
    @php
        do_action( 'woocommerce_before_account_orders', $has_orders ); 
    @endphp

    @if ($has_orders)
        <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
        </table>
        @php 
            do_action( 'woocommerce_before_account_orders_pagination' );
        @endphp

       

    @else


    @endif
@endsection