<div class="text-center text-lg font-semibold text-gray-800">
        {{ $product->get_name() }}
</div>
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