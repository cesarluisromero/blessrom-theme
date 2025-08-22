@php
  // IDs de miniaturas (principal + adjuntas)
  $thumb_ids = array_values(array_filter(array_merge([$main_image], $attachment_ids)));
  $top_ids    = array_slice($thumb_ids, 0, 6);   // primeras 6
  $bottom_ids = array_slice($thumb_ids, 6);      // el resto
@endphp

{{-- Columna 1: Imágenes --}}
<div class="grid grid-cols-[20%_80%] grid-rows-[auto_auto] gap-0 items-start bg-white ml-4">

  {{-- Miniaturas (arriba, col 1 / fila 1) --}}
  <div class="hidden lg:flex flex-col space-y-1 row-start-1 col-start-1">
    @foreach ($top_ids as $id)
      <img
        src="{{ wp_get_attachment_image_url($id, 'thumbnail') }}"
        class="w-16 h-16 object-cover cursor-pointer border border-white rounded bg-[#E1E6E4] hover:border-blue-500"
        @click="$store.product.currentImage = '{{ wp_get_attachment_image_url($id, 'large') }}'">
    @endforeach
  </div>

  {{-- Miniaturas sobrantes (abajo, col 1 / fila 2) --}}
  @if (!empty($bottom_ids))
    <div class="hidden lg:flex flex-wrap gap-1 row-start-2 col-start-1">
      @foreach ($bottom_ids as $id)
        <img
          src="{{ wp_get_attachment_image_url($id, 'thumbnail') }}"
          class="w-16 h-16 object-cover cursor-pointer border border-white rounded bg-[#E1E6E4] hover:border-blue-500"
          @click="$store.product.currentImage = '{{ wp_get_attachment_image_url($id, 'large') }}'">
      @endforeach
    </div>
  @endif

  {{-- Imagen principal (col 2 / ocupa 2 filas) --}}
  <div
    class="relative row-span-2 col-start-2"
    x-data="{
      zoomX: 0, zoomY: 0, showZoom: false,
      zoomEnabled: window.innerWidth >= 768,
      updateZoom(e) {
        if (!this.zoomEnabled) return
        const b = e.target.getBoundingClientRect()
        const x = e.clientX - b.left, y = e.clientY - b.top
        this.zoomX = -x * 1.5 + 350; this.zoomY = -y * 1.5 + 275
      }
    }"
    x-init="window.addEventListener('resize', () => zoomEnabled = window.innerWidth >= 768)"
  >
    <img
      :src="$store.product.currentImage"
      class="w-full h-auto object-contain border border-white rounded bg-[#E1E6E4]"
      alt="Imagen principal"
      @mousemove="updateZoom"
      @mouseenter="if (zoomEnabled) showZoom = true"
      @mouseleave="showZoom = false"
    >
    <div
      x-ref="zoom" x-show="showZoom && zoomEnabled" x-transition
      class="absolute top-0 left-full ml-4 z-50 border rounded shadow-lg bg-white p-2 overflow-hidden hidden md:block"
      style="width:700px;height:550px"
    >
      <img :src="$store.product.currentImage"
           :style="'transform: scale(1.5) translate(' + zoomX + 'px,' + zoomY + 'px)'"
           class="w-full h-auto object-contain" alt="Zoom imagen">
    </div>
  </div>

</div>
{{-- Fin de Columna 1 --}}
