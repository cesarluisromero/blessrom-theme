<div x-data="productGallery()" class="swiper lg:hidden mb-6">
    <div class="swiper-wrapper">
        @php $ids = array_merge([$main_image], $attachment_ids); @endphp
        @foreach ($ids as $id)
            <div class="swiper-slide">
                <img src="{{ wp_get_attachment_image_url($id, 'full') }}" class="w-full h-auto object-contain">
            </div>
        @endforeach
    </div>
    <div class="swiper-pagination mt-2"></div>
</div>