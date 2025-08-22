{{-- Columna 1: Imágenes --}}

<div class="grid grid-cols-[20%_80%] gap-0 items-start bg-white ml-4">
  
    {{-- Miniaturas --}}
    <div class="hidden lg:flex flex-col space-y-1">
        @if ($main_image)
            <img src="{{ wp_get_attachment_image_url($main_image, 'thumbnail') }}" 
                class="w-16 h-16 object-cover cursor-pointer border border-white rounded bg-[#E1E6E4] hover:border-blue-500" 
                @click="$store.product.currentImage = '{{ wp_get_attachment_image_url($main_image, 'large') }}'">
        @endif
        @foreach ($attachment_ids as $id)
            <img src="{{ wp_get_attachment_image_url($id, 'thumbnail') }}" 
                class="w-16 h-16 object-cover cursor-pointer border border-white rounded bg-[#E1E6E4] hover:border-blue-500" 
                @click="$store.product.currentImage = '{{ wp_get_attachment_image_url($id, 'large') }}'">
        @endforeach
    </div>

    {{-- Imagen principal --}}
    
    <div 
        x-data="{
            zoomX: 0,
            zoomY: 0,
            showZoom: false,
            zoomEnabled: window.innerWidth >= 768,
            updateZoom(event) {
                if (!this.zoomEnabled) return;
                const bounds = event.target.getBoundingClientRect();
                const x = event.clientX - bounds.left;
                const y = event.clientY - bounds.top;
                this.zoomX = -x * 1.5 + 350;
                this.zoomY = -y * 1.5 + 275;
            }
        }"
        x-init="window.addEventListener('resize', () => zoomEnabled = window.innerWidth >= 768)"
        class="relative"
    >
        {{-- Imagen principal --}}
        <img 
            :src="$store.product.currentImage" 
            class="w-full h-auto object-contain border border-white rounded bg-[#E1E6E4]" 
            alt="Imagen principal"
            @mousemove="updateZoom"
            @mouseenter="if (zoomEnabled) showZoom = true" 
            @mouseleave="showZoom = false"
        >

        {{-- Zoom flotante, solo para pantallas grandes --}}
        <div 
            x-ref="zoom" 
            x-show="showZoom && zoomEnabled"
            x-transition
            class="absolute top-0 left-full ml-4 z-50 border rounded shadow-lg bg-white p-2 overflow-hidden hidden md:block"
            style="width: 700px; height: 550px;"
        >
            <img 
                :src="$store.product.currentImage" 
                :style="'transform: scale(1.5) translate(' + zoomX + 'px, ' + zoomY + 'px);'"
                class="w-full h-auto object-contain"
                alt="Zoom imagen"
            >
        </div>
    </div>
</div>
{{-- Fin de Columna 1 --}}