<div x-data="productGallery()" class="block lg:hidden">
    {{-- Encabezado móvil con título del producto --}}
    <div class="text-center text-lg font-semibold text-gray-800">
        {{ $product->get_name() }}
    </div>
    <div class="swiper-container product-swiper aspect-[3/4] bg-cover bg-center rounded-lg overflow-hidden">
    
        <div class="swiper-wrapper">
            @php $ids = array_merge([$main_image], $attachment_ids); @endphp
            @foreach ($ids as $id)
                <div class="swiper-slide">
                    <img src="{{ wp_get_attachment_image_url($id, 'large') }}" class="w-full h-full object-cover">
                </div>
            @endforeach
        </div>
         <div class="flex justify-center gap-2 py-4 swiper-pagination"></div>
    </div>
    

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



