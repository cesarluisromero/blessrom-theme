{{-- DEBUG TEMPORAL - Quitar después --}}
@if(isset($debug_info) && current_user_can('administrator'))
<div style="background: #fff3cd; border: 2px solid #ffc107; padding: 15px; margin: 10px; font-family: monospace; font-size: 12px;">
  <strong>DEBUG Banner Vestidos:</strong><br>
  Page ID: {{ $debug_info['page_id'] ?? 'N/A' }}<br>
  Desktop Slides: {{ $debug_info['desktop_count'] ?? 0 }}<br>
  Mobile Slides: {{ $debug_info['mobile_count'] ?? 0 }}<br>
  Button URL: {{ $debug_info['button_url'] ?? 'N/A' }}<br>
  Button Text: {{ $debug_info['button_text'] ?? 'N/A' }}<br>
  <strong>Campos encontrados:</strong><br>
  @if(isset($debug_info['test_fields']))
    @foreach($debug_info['test_fields'] as $field => $status)
      {{ $field }}: {{ $status }}<br>
    @endforeach
  @endif
</div>
@endif

<section class="hidden md:block full-bleed text-center py-2 px-4">
    <div class="bg-white">
      <div class="swiper bannervestidos-swiper">
        <!-- Contenedor de slides -->
      
        <div class="swiper-wrapper">
          @forelse($slides_desktop as $index => $slide)
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
                src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Red-Tan-and-Black-Modern-Fashion-Sale-Banner-Landscape.png') }}"
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
        @forelse($slides_mobile as $index => $slide)
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
              src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/1.png') }}"
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



