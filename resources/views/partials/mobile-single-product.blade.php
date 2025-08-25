@php
  // Construir color -> URL de imagen de variación
  $color_image_map = [];
  $variation_img_urls = [];

  if (!empty($available_variations)) {
    foreach ($available_variations as $v) {
      $color = $v['attributes']['attribute_pa_color'] ?? null;

      // ID de imagen de la variación (distintos formatos según Woo)
      $imgId = $v['image_id'] ?? ($v['image']['id'] ?? null);

      // URL 'large' de la imagen de la variación
      $imgUrl = $imgId ? wp_get_attachment_image_url($imgId, 'large') : ($v['image']['url'] ?? null);

      if ($color && $imgUrl) {
        // 1) mapa color -> URL (para Alpine.store.product.colorImages)
        if (!isset($color_image_map[$color])) {
          $color_image_map[$color] = $imgUrl;
        }
        // 2) juntar todas las URLs de variación para meterlas en el carrusel móvil
        $variation_img_urls[] = $imgUrl;
      }
    }
  }

  // Asegurar que el slider móvil contenga: imagen principal + adjuntas + variaciones
  $base_img_urls = array_filter(array_map(function($id) {
    return wp_get_attachment_image_url($id, 'large');
  }, array_merge([$main_image], $attachment_ids)));

  // Unir y limpiar duplicados por URL
  $all_img_urls = array_values(array_unique(array_merge($base_img_urls, $variation_img_urls)));
@endphp

<script>
  // Ahora el mapa es slug -> URL (igual que en desktop)
  window.BLESSROM_COLOR_IMAGE_MAP = {!! json_encode($color_image_map, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!};
</script>


<div class="text-center text-lg font-semibold text-gray-800 lg:hidden mb-6">
        {{ $product->get_name() }}
</div>
<div x-data="productGallery()" class="product-swiper-movil swiper block lg:hidden mb-6">
    <div class="swiper-wrapper">
        @foreach ($all_img_urls as $url)
        <div class="swiper-slide">
            <img src="{{ $url }}" class="w-full h-auto object-contain lg:hidden mb-6">
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
<div class="bg-white rounded-2xl shadow-md p-6 max-w-md space-y-4 border border-gray-100">
    <div class="flex items-center justify-center gap-2 text-yellow-500 text-2xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4v16m8-8H4"/>
        </svg>
        <h2 class="text-gray-800 font-semibold text-lg">¿Por qué te encantará?</h2>
    </div>

    <div class="space-y-3 text-gray-700 text-base leading-relaxed">
        {!! wpautop($product->get_short_description()) !!}
    </div>

    <div class="pt-4 border-t border-gray-200 text-center">
        <p class="text-sm text-gray-500 italic">¡Luce increíble y siéntete con total comodidad donde vayas!</p>
    </div>
</div>