@php
  // Construir color -> image_id desde variaciones
  $color_image_map = [];
  if (!empty($available_variations)) {
    foreach ($available_variations as $v) {
      $color = $v['attributes']['attribute_pa_color'] ?? null;
      $imgId = $v['image_id'] ?? ($v['image']['id'] ?? null);
      if ($color && $imgId) $color_image_map[$color] = (int) $imgId;
    }
  }

  // Asegurar que TODAS las imágenes de variación estén en el slider
  $variation_img_ids = array_values(array_unique(array_filter(array_map(function($v){
    return $v['image_id'] ?? ($v['image']['id'] ?? null);
  }, $available_variations ?? []))));

  $ids = array_values(array_unique(array_merge([$main_image], $attachment_ids, $variation_img_ids)));
@endphp

<script>
  window.BLESSROM_COLOR_IMAGE_MAP = {!! json_encode($color_image_map, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!};
</script>

<div class="text-center text-lg font-semibold text-gray-800 lg:hidden mb-6">
        {{ $product->get_name() }}
</div>
<div x-data="productGallery()" class="product-swiper-movil swiper block lg:hidden mb-6">
    
    <div class="swiper-wrapper">
        @php $ids = array_merge([$main_image], $attachment_ids); @endphp
        @foreach ($ids as $id)
            <div class="swiper-slide">
                <img src="{{ wp_get_attachment_image_url($id, 'large') }}" data-id="{{ $id }}" class="w-full h-auto object-contain lg:hidden mb-6">
            </div>
        @endforeach
    </div> 
    
    <div class="swiper-pagination absolute bottom-1 inset-x-0 flex justify-center"></div>   
</div>
<div class="px-4 py-4 space-y-3 lg:hidden mb-6">
    {{-- Precio --}}
    <div class="text-center text-xl font-bold text-blue-600">
        {!! $product->get_price_html() !!}
    </div>

    {{-- Atributos --}}
    <div class="text-sm text-center text-gray-700">
        {!! woocommerce_template_single_add_to_cart() !!}
    </div>
</div>
