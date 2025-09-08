@extends('layouts.app')

@section('content')

{{-- Banner principal --}}
  @include('partials.home-banner2')

  {{-- Categorías destacadas --}}
  @include('partials.home-categories')

  {{-- Sección después de las categorías --}}
  @include('partials.home-banner-polo-hombre') 

  {{-- Slider productos --}}
  @include('partials.home-slider-products')
  
  {{-- banner de vestidos --}}
  @include('partials.home-banner-vestidos')

  {{-- slider de vestidos --}}
  @include('partials.home-categories-vestidos')

  {{-- Testimonios --}}  
  @include('partials.home-testimonials')
  
  {{-- Beneficios o razones para elegir Blessrom --}}
  @include('partials.home-features')

@endsection
