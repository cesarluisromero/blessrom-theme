<div x-data="productGallery()" class="block lg:hidden">
  <div class="relative flex min-h-screen flex-col justify-between bg-white overflow-x-hidden">
    
    {{-- Header --}}
    <div class="flex items-center justify-between px-4 py-3">
      <a href="{{ url()->previous() }}" class="text-gray-800">
        <x-icon-arrow-left class="w-6 h-6" />
      </a>
      <button class="text-gray-800">
        <x-icon-shopping-bag class="w-6 h-6" />
      </button>
    </div>

    {{-- Galería Swiper --}}
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

    {{-- Información del producto --}}
    <div class="p-4">
      <h1 class="text-lg font-bold text-gray-900">{{ $product->get_name() }}</h1>
      <div class="text-blue-600 text-xl font-bold">{!! $product->get_price_html() !!}</div>

      {{-- Variaciones y botón --}}
      <div class="mt-4">{!! woocommerce_template_single_add_to_cart() !!}</div>
    </div>
  </div>
</div>
