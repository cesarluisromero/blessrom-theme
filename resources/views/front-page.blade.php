@extends('layouts.app')

@section('content')

{{-- Banner principal --}}
  @include('partials.home-banner')

  {{-- Categorías destacadas --}}
  @include('partials.home-categories')

  {{-- Sección después de las categorías --}}
  @include('partials.home-despues-categorias')

  {{-- Slider productos --}}
  @include('partials.home-slider-products')


  {{-- Slider productos --}}
  @include('partials.home-categories-vestidos')

  {{-- Productos más vendidos --}}
  @include('partials.home-best-sellers')

  
  
  @include('partials.home-testimonials')
  
  
  {{-- Beneficios o razones para elegir Blessrom --}}
  @include('partials.home-features')
@endsection
