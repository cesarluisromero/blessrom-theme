<div
      class="relative flex size-full min-h-screen flex-col bg-white justify-between group/design-root overflow-x-hidden"
      style='font-family: "Plus Jakarta Sans", "Noto Sans", sans-serif;'
    >
    <div>
        <div x-data="productGallery()" class="swiper-container product-swiper block lg:hidden mb-6">
            <div class="swiper-wrapper">
                @php $ids = array_merge([$main_image], $attachment_ids); @endphp
                @foreach ($ids as $id)
                    <div class="swiper-slide">
                        <img src="{{ wp_get_attachment_image_url($id, 'large') }}" class="w-full h-auto object-contain">
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination mt-2"></div>
        </div>
    </div>
</div>
