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
      <div class="swiper-button-prev home-banner2-swiper-button-prev !hidden md:!flex text-white absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-transparent rounded-full"></div>
      <div class="swiper-button-next home-banner2-swiper-button-next !hidden md:!flex text-white absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-transparent rounded-full"></div>

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