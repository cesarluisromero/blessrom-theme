@extends('layouts.app')

@section('content')

{{-- Banner principal --}}
  @include('partials.home-banner')

  {{-- Categorías destacadas --}}
  @include('partials.home-categories')

  

  {{-- Slider productos --}}
  @include('partials.home-slider-products')

  {{-- Productos más vendidos --}}
  @include('partials.home-best-sellers')

  @include('partials.home-explore')


  @include('partials.home-testimonials')
  
  {{-- Beneficios o razones para elegir Blessrom --}}
  @include('partials.home-features')
@endsection
