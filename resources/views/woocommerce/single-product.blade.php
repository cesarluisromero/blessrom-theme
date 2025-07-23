@extends('layouts.app')

@section('content')
    @php
        // Obtener todas las variaciones disponibles del producto actual (asumiendo $product es WC_Product_Variable)
        $variations = $product->get_available_variations();
        $colorImages = [];
        foreach ($variations as $variation) {
            // Suponiendo que el atributo color se llama 'pa_color'
            if (!empty($variation['attributes']['attribute_pa_color'])) {
                $colorSlug = $variation['attributes']['attribute_pa_color'];  // slug del color
                $imgUrl = $variation['image']['url'] ?? '';  // URL de la imagen de la variación
                if ($imgUrl && !isset($colorImages[$colorSlug])) {
                    $colorImages[$colorSlug] = $imgUrl;
                }
            }
        }
        // Obtener imagen por defecto (por ejemplo, la imagen destacada del producto)
        $defaultImage = wp_get_attachment_image_url($product->get_image_id(), 'large');
    @endphp
  
    {{-- 🔄 WooCommerce necesita esto para inicializar el carrito --}}
    @php do_action('woocommerce_before_main_content'); @endphp
    
    <div x-data="{
        currentImage: '{{ wp_get_attachment_image_url($main_image, 'large') }}'
    }" class="container max-w-6xl mx-auto px-2 md:px-4 lg:px-6 py-10">
        
        {{-- Imagen principal + galería táctil en móvil --}}
            @include('partials.mobile-single-product')

        {{-- Galería de escritorio en columnas --}}
        <div class="grid grid-cols-1 lg:grid-cols-[40%_30%_30%] gap-4  desktop-gallery">
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

@push('scripts')
    <script>
        window.wc_add_to_cart_params = {
            ajax_url: "{{ admin_url('admin-ajax.php') }}",
            cart_url: "{{ wc_get_cart_url() }}"
        };
        function productGallery() {
            return {
                swiper: null,

                init() {
                this.swiper = new Swiper(this.$el, {
                    slidesPerView: 1,
                    spaceBetween: 10,
                    pagination: {
                    el: this.$el.querySelector('.swiper-pagination'),
                    clickable: true
                    },
                    breakpoints: {
                    768: {
                        pagination: false,
                        swipe: false,
                        allowTouchMove: false
                    }
                    }
                });

                // 🔗 Enlazar al store Alpine
                Alpine.store('product').swiper = this.swiper;

                Alpine.store('product').slideToImage = (url) => {
                    const slides = this.swiper.slides;
                    for (let i = 0; i < slides.length; i++) {
                    const img = slides[i].querySelector('img');
                    if (img && img.src.split('?')[0] === url.split('?')[0]) {
                        this.swiper.slideToLoop(i); // porque loop está activado
                        break;
                    }
                    }
                };
                }
            }
        };

        document.addEventListener('alpine:init', () => {
            Alpine.store('product', {
            colorImages: @json($colorImages),
            selectedColor: null,
            selectedImage: '{{ $defaultImage }}',
            currentImage: '{{ $defaultImage }}'  // NUEVO
            });
        })
    </script>
@endpush