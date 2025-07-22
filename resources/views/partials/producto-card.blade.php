{{-- partials/producto-card.blade.php --}}
<div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden flex flex-col h-full relative">
  <a href="{{ get_permalink($product->get_id()) }}" class="block">
    <div class="w-full aspect-[3/4] bg-white overflow-hidden">
      <img
        src="{{ wp_get_attachment_image_url($product->get_image_id(), 'medium') }}"
        alt="{{ $product->get_name() }}"
        class="w-full h-full object-contain transition-transform duration-300 hover:scale-105"
      />
    </div>
  </a>

  <div class="p-3 flex flex-col justify-between flex-grow">
    <span class="text-[11px] text-purple-600 font-semibold uppercase tracking-wide mb-1">Promocionado</span>

    <h3 class="text-sm font-bold text-gray-800 leading-tight truncate mb-1">
      {{ $product->get_name() }}
    </h3>

    <div class="text-[13px] text-gray-900 font-bold mb-1">
      {!! $product->get_price_html() !!}
    </div>

    <a href="{{ get_permalink($product->get_id()) }}"
      class="mt-2 inline-block bg-[#FFB816] text-white text-xs text-center py-1.5 px-3 rounded hover:bg-yellow-500 transition font-medium">
      Ver producto
    </a>
  </div>
</div>
