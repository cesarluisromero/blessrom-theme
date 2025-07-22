@extends('layouts.app')

@section('content')
  
    {{-- 🔄 WooCommerce necesita esto para inicializar el carrito --}}
    @php do_action('woocommerce_before_main_content'); @endphp
    
    <div x-data="{
        currentImage: '{{ wp_get_attachment_image_url($main_image, 'large') }}'
    }" class="container max-w-6xl mx-auto px-2 md:px-4 lg:px-6 py-10">
        
        {{-- Imagen principal + galería táctil en móvil --}}
        @include('partials.mobile-single-product')

        {{-- Galería de escritorio en columnas --}}
        <div class="grid grid-cols-1 lg:grid-cols-[40%_30%_30%] gap-4 h-[300px] desktop-gallery">
            {{-- Columna 1: Imágenes --}}
            @include('partials.single-product-columna1')
            
            {{-- Columna 2: Información --}}
            @include('partials.single-product-columna2')
            
            {{-- Columna 3: Acciones y Descripción --}}
            @include('partials.single-product-columna3')
        </div>
    </div>

    {{-- 🔄 WooCommerce también necesita esto para finalizar su contenido --}}
   @php do_action('woocommerce_after_main_content'); @endphp
@endsection

