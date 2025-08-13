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
    {{-- === Productos relacionados (custom grid) === --}}
@php
    // Obtener hasta 8 productos relacionados al actual
    $related_ids = wc_get_related_products($product->get_id(), 8);

    // Si quieres forzar aleatorio cada carga, usa 'orderby' => 'rand'
    // Si prefieres respetar el orden de $related_ids, usa 'orderby' => 'post__in'
    $related_q = new WP_Query([
        'post_type'           => 'product',
        'post__in'            => $related_ids,
        'posts_per_page'      => 8,
        'ignore_sticky_posts' => 1,
        'orderby'             => 'rand',
        'post_status'         => 'publish',
        'tax_query'           => [
            // Asegura solo productos visibles (por si usas catálogos ocultos)
            [
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => ['exclude-from-catalog'],
                'operator' => 'NOT IN',
            ],
        ],
    ]);
@endphp

@if ($related_q->have_posts())
    <section class="max-w-6xl mx-auto px-2 md:px-4 lg:px-6 mt-12">
        <h2 class="text-xl md:text-2xl font-semibold mb-6">También te puede interesar</h2>

        <ul class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @while ($related_q->have_posts()) 
                @php $related_q->the_post(); @endphp

                <li class="bg-white rounded-2xl shadow hover:shadow-lg transition p-3">
                    {{-- Usa tu card personalizada si la tienes --}}
                    {{-- @include('partials.product-card', ['product' => wc_get_product(get_the_ID())]) --}}

                    {{-- O usa la card nativa de WooCommerce (compatible con tus estilos) --}}
                    @php wc_get_template_part('content', 'product'); @endphp
                </li>
            @endwhile
        </ul>
    </section>
    @php wp_reset_postdata(); @endphp
@endif
{{-- === /Productos relacionados === --}}


    {{-- 🔄 WooCommerce también necesita esto para finalizar su contenido --}}
   @php do_action('woocommerce_after_main_content'); @endphp
@endsection

@push('scripts')
    <script>
        window.wc_add_to_cart_params = {
            ajax_url: "{{ admin_url('admin-ajax.php') }}",
            cart_url: "{{ wc_get_cart_url() }}"
        };
        document.addEventListener('alpine:init', () => {
            Alpine.store('product', {
            colorImages: @json($colorImages),
            selectedColor: null,
            selectedImage: '{{ $defaultImage }}',
            currentImage: '{{ $defaultImage }}' ,
            swiper: null,
            slideToImage: () => {}
            });
        })
        function productGallery() {
            return {
                swiper: null,

                init() {
                this.swiper = new Swiper(this.$el, {
                    slidesPerView: 1,
                    spaceBetween: 10,
                    loop: true,
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

                // ✅ Guardar swiper en Alpine.store
                if (Alpine.store('product')) {
                    Alpine.store('product').swiper = this.swiper;

                    Alpine.store('product').slideToImage = (url) => {
                    const targetUrl = url.split('?')[0];

                    const slides = this.swiper.slides;
                    for (let i = 0; i < slides.length; i++) {
                        const img = slides[i].querySelector('img');
                        if (img && img.src.split('?')[0] === targetUrl) {
                        this.swiper.slideToLoop(i); // funciona con loop
                        break;
                        }
                    }
                    };
                } else {
                    console.warn('⚠️ Alpine.store("product") aún no está disponible');
                }
                }
            };
        };

        
    </script>
@endpush