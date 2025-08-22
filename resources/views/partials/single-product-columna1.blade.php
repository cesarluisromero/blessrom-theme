{{-- Columna 1: Imágenes --}}

@php
  // Armar lista de miniaturas (principal + adjuntas), sin nulos
  $thumb_ids = array_values(array_filter(array_merge(
    $main_image ? [$main_image] : [],
    $attachment_ids ?? []
  )));
  $top_ids    = array_slice($thumb_ids, 0, 6);   // primeras 6
  $bottom_ids = array_slice($thumb_ids, 6);      // el resto
@endphp

<div class="grid grid-cols-[112px_1fr] grid-rows-[auto_auto] gap-3 items-start bg-white ml-4">

  {{-- Rail de miniaturas (col 1) dividido en 2 filas --}}
  <div class="col-start-1 row-start-1">
    {{-- Fila 1: primeras 6 (una debajo de otra) --}}
    <div class="flex flex-col gap-2">
      @foreach ($top_ids as $id)
        <img
          src="{{ wp_get_attachment_image_url($id, 'thumbnail') }}"
          class="w-20 h-20 object-cover cursor-pointer border border-white rounded bg-[#E1E6E4] hover:border-blue-500"
          @click="$store.product.currentImage = '{{ wp_get_attachment_image_url($id, 'large') }}'">
      @endforeach
    </div>
  </div>

  {{-- Fila 2: resto de miniaturas (solo si hay más de 6) --}}
  @if (count($bottom_ids))
    <div class="col-start-1 row-start-2">
      <div class="flex flex-col gap-2">
        @foreach ($bottom_ids as $id)
          <img
            src="{{ wp_get_attachment_image_url($id, 'thumbnail') }}"
            class="w-20 h-20 object-cover cursor-pointer border border-white rounded bg-[#E1E6E4] hover:border-blue-500"
            @click="$store.product.currentImage = '{{ wp_get_attachment_image_url($id, 'large') }}'">
        @endforeach
      </div>
    </div>
  @endif

  {{-- Imagen principal (col 2, ocupa 2 filas) --}}
  <div
    class="relative col-start-2 row-span-2"
    x-data="{
      zoomX: 0, zoomY: 0, showZoom: false,
      zoomEnabled: window.innerWidth >= 768,
      updateZoom(e) {
        if (!this.zoomEnabled) return
        const b = e.target.getBoundingClientRect()
        const x = e.clientX - b.left, y = e.clientY - b.top
        this.zoomX = -x * 1.5 + 350
        this.zoomY = -y * 1.5 + 275
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
      style="width: 700px; height: 550px;"
    >
      <img
        :src="$store.product.currentImage"
        :style="'transform: scale(1.5) translate(' + zoomX + 'px, ' + zoomY + 'px)'"
        class="w-full h-auto object-contain"
        alt="Zoom imagen"
      >
    </div>
  </div>

</div>
{{-- Fin de Columna 1 --}}
