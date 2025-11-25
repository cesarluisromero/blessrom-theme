@if(isset($debug_info) && current_user_can('administrator'))
  <div style="background:#fff3cd;border:2px solid #ffc107;padding:12px;margin:10px 0;font-family:monospace;font-size:12px;">
    <strong>DEBUG home-banner2:</strong><br>
    Page ID: {{ $debug_info['page_id'] ?? 'N/A' }}<br>
    Desktop Slides: {{ $debug_info['desktop_count'] ?? 0 }}<br>
    Mobile Slides: {{ $debug_info['mobile_count'] ?? 0 }}<br>
    Button URL: {{ $debug_info['button_url'] ?? 'N/A' }}<br>
    Button Text: {{ $debug_info['button_text'] ?? 'N/A' }}<br>
    <strong>Campos:</strong><br>
    @if(isset($debug_info['fields']))
      @foreach($debug_info['fields'] as $field => $status)
        {{ $field }}: {{ $status }}<br>
      @endforeach
    @endif
  </div>
@endif

{{-- Slider solo para escritorio (md en adelante) --}}
<section class="hidden md:block full-bleed text-center py-2 px-4">
  <div class="bg-white">
    <div class="swiper home-banner2-swiper">
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
              src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/1-4-scaled.png') }}"
              alt="Experimenta nuestra pasión por la moda en Tarapoto"
              class="w-full h-full object-cover"
              fetchpriority="high" decoding="async">
          </div>
          <div class="swiper-slide">
            <img
              src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/2-2-scaled.png') }}"
              alt="Colección de vestidos"
              class="w-full h-full object-cover"
              loading="lazy" decoding="async">
          </div>
        @endforelse
      </div>

      <!-- Botones -->
      <div class="swiper-button-prev home-banner2-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
      <div class="swiper-button-next home-banner2-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>

      <div class="mt-6 mb-10 flex justify-center">
        <a href="{{ esc_url($button_url ?: 'https://blessrom.com/tienda') }}"
          class="inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-6 py-3 text-white font-semibold shadow hover:bg-yellow-500">
          {{ esc_html($button_text ?: 'Ver Más Estilos') }}
        </a>
      </div>
    </div>
  </div>
</section>

{{-- Slider solo para móvil (hasta sm) --}}
<section class="block md:hidden w-full">
  <div class="bg-white">
    <div class="swiper home-banner2-swiper rounded-none" aria-label="Banner principal móvil">
      <div class="swiper-wrapper">
        @forelse($slides_mobile as $index => $slide)
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
              src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/3-1.png') }}"
              alt="Experimenta nuestra pasión por la moda en Tarapoto"
              class="w-full h-full object-cover"
              fetchpriority="high" decoding="async">
          </div>
          <div class="swiper-slide">
            <img
              src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/4-1.png') }}"
              alt="Colección de vestidos"
              class="w-full h-full object-cover"
              loading="lazy" decoding="async">
          </div>
        @endforelse
      </div>

      <div class="swiper-button-prev home-banner2-swiper-button-prev !hidden md:!flex"></div>
      <div class="swiper-button-next home-banner2-swiper-button-next !hidden md:!flex"></div>

      <div class="mt-6 mb-10 flex justify-center">
        <a href="{{ esc_url($button_url ?: 'https://blessrom.com/tienda') }}"
          class="inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-6 py-3 text-white font-semibold shadow hover:bg-yellow-500">
          {{ esc_html($button_text ?: 'Ver Más Estilos') }}
        </a>
      </div>
    </div>
  </div>
</section>