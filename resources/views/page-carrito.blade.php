{{-- resources/views/page-carrito.blade.php --}}
@extends('layouts.app')

@section('content')
  {{-- Usa tu vista Blade para el carrito --}}
  {!! \Roots\view('woocommerce.cart')->render() !!}
@endsection
