@extends('layouts.app')

@section('content')
  
    {{-- 🔄 WooCommerce necesita esto inicializar su contenido--}}
    @php do_action('woocommerce_before_main_content'); @endphp
    
    <div x-data="{
        currentImage: '{{ wp_get_attachment_image_url($main_image, 'large') }}'
    }" class="container max-w-6xl mx-auto px-2 md:px-4 lg:px-6 py-10">
        
        {{-- Imagen principal + galería táctil en móvil --}}
        <div x-data="productGallery()" class="product-swiper swiper block lg:hidden mb-6">
            <div class="swiper-wrapper">
                @php $ids = array_merge([$main_image], $attachment_ids); @endphp
                @foreach ($ids as $id)
                    <div class="swiper-slide">
                        <img src="{{ wp_get_attachment_image_url($id, 'large') }}" class="w-full h-auto object-contain">
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination absolute bottom-1 inset-x-0 flex justify-center"></div>
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
                init() {
                new Swiper(this.$el, {
                    slidesPerView: 1,
                    spaceBetween: 10,
                    pagination: { el: this.$el.querySelector('.swiper-pagination'), clickable: true },
                    breakpoints: {
                    768: {
                        pagination: false,
                        swipe: false,
                        allowTouchMove: false
                    }
                    }
                });
                }
            }
        }
    </script>
@endpush