<div x-data="productGallery()" class="swiper-container product-swiper block lg:hidden mb-6">
    {{-- Encabezado móvil con título del producto --}}
    <div class="text-center text-lg font-semibold text-gray-800">
        {{ $product->get_name() }}
    </div>
    <div class="swiper-wrapper">
        @php $ids = array_merge([$main_image], $attachment_ids); @endphp
        @foreach ($ids as $id)
            <div class="swiper-slide">
                <img src="{{ wp_get_attachment_image_url($id, 'large') }}" class="w-full h-auto object-contain">
            </div>
        @endforeach
    </div>
    <div class="swiper-pagination mt-2"></div>

    <div class="px-4 py-4 space-y-3">
        {{-- Precio --}}
        <div class="text-center text-xl font-bold text-blue-600">
            {!! $product->get_price_html() !!}
        </div>

        {{-- Atributos --}}
        <div class="text-sm text-center text-gray-700">
            {!! woocommerce_template_single_add_to_cart() !!}
        </div>
    </div>
</div>


