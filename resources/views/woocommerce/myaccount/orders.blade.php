@extends('layouts.app')

@section('content')
    @php
        do_action( 'woocommerce_before_account_orders', $has_orders ); ?>
    @endphp

@endsection