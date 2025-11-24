
<section class="hidden md:block full-bleed text-center py-2 px-4">
    <div class="bg-white">
      <div class="swiper bannervestidos-swiper">
        <!-- Contenedor de slides -->
      
        <div class="swiper-wrapper">
          @forelse($slides as $index => $slide)
            <div class="swiper-slide">
              <img
                src="{{ esc_url($slide['imagen']['url'] ?? $slide['imagen'] ?? '') }}"
                alt="{{ esc_attr($slide['alt'] ?? '') }}"
                class="w-full h-full object-cover"
                {{ $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' }} 
                decoding="async">
            </div>
          @empty
            {{-- Fallback si no hay slides configurados --}}
            <div class="swiper-slide">
              <img
                src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/White-Simple-Photocentric-Jewelry-Promo-Banner-Landscape-scaled.png') }}"
                alt="Banner"
                class="w-full h-full object-cover"
                fetchpriority="high" decoding="async">
            </div>
          @endforelse
        </div>
      

        <!-- Botones -->
        
        <div class="swiper-button-prev bannervestidos-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
        <div class="swiper-button-next bannervestidos-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>

        {{-- botón fuera del swiper --}}
        @if($button_url)
        <div class="mt-6 mb-10 flex justify-center">
          <a href="{{ esc_url($button_url) }}"
            class="inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-6 py-3 text-white font-semibold shadow hover:bg-yellow-500">
            {{ esc_html($button_text) }}
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>

<section class="block md:hidden full-bleed text-center py-6 overflow-x-clip">
  <div class="bg-white">
    <div class="swiper bannervestidos-swiper rounded-none" aria-label="Banner vestidos móvil">
      <div class="swiper-wrapper">
        @forelse($slides as $index => $slide)
          <div class="swiper-slide">
            <img
              src="{{ esc_url($slide['imagen']['url'] ?? $slide['imagen'] ?? '') }}"
              alt="{{ esc_attr($slide['alt'] ?? '') }}"
              class="w-full h-auto block"
              {{ $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' }} 
              decoding="async">
          </div>
        @empty
          {{-- Fallback si no hay slides configurados --}}
          <div class="swiper-slide">
            <img
              src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Creamy-Black-Clean-Mens-Fashion-Instagram-Post-movil.png') }}"
              alt="Banner"
              class="w-full h-auto block"
              fetchpriority="high" decoding="async">
          </div>
        @endforelse
      </div>

      {{-- Flechas (ocultas en móvil) --}}
      <div class="swiper-button-prev bannervestidos-swiper-button-prev !hidden md:!flex"></div>
      <div class="swiper-button-next bannervestidos-swiper-button-next !hidden md:!flex"></div>
      {{-- debajo del .swiper bannervestidos-swiper --}}
    
      {{-- botón fuera del swiper --}}
      @if($button_url)
      <div class="mt-6 mb-10 flex justify-center">
        <a href="{{ esc_url($button_url) }}"
          class="inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-6 py-3 text-white font-semibold shadow hover:bg-yellow-500">
          {{ esc_html($button_text) }}
        </a>
      </div>
      @endif
    </div>
    

  </div>
</section>



