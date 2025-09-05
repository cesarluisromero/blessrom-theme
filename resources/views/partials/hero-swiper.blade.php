{{-- Hero con 2 sliders: mobile y desktop --}}
<div class="relative mx-auto max-w-[120rem] px-4 sm:px-6 lg:px-8">

  {{-- DESKTOP (md≥) --}}
  <div class="hidden md:block">
    <div class="swiper js-hero-desktop rounded-2xl overflow-hidden h-[72vh]" aria-label="Hero desktop">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <img
            src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Experimenta-nuestra-pasion-por-la-moda.png') }}"
            alt="Experimenta nuestra pasión por la moda en Tarapoto (desktop)"
            class="h-full w-full object-cover"
            fetchpriority="high"
            decoding="async">
        </div>
        <div class="swiper-slide">
          <img
            src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Experimenta-nuestra-pasion-por-la-moda.-2.png') }}"
            alt="Colección de vestidos (desktop)"
            class="h-full w-full object-cover"
            loading="lazy"
            decoding="async">
        </div>
      </div>

      <div class="swiper-pagination !bottom-4"></div>
      <button class="swiper-button-prev !text-white !w-12 !h-12 !-translate-y-1/2"><span class="sr-only">Anterior</span></button>
      <button class="swiper-button-next !text-white !w-12 !h-12 !-translate-y-1/2"><span class="sr-only">Siguiente</span></button>

      <div class="pointer-events-none absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-black/20 to-transparent"></div>
      <div class="pointer-events-none absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-black/20 to-transparent"></div>
    </div>
  </div>

  {{-- MOBILE (<md) --}}
  <div class="md:hidden">
    <div class="swiper js-hero-mobile rounded-2xl overflow-hidden h-[56vh]" aria-label="Hero mobile">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <img
            src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Experimenta-nuestra-pasion-por-la-moda.png') }}"
            alt="Experimenta nuestra pasión por la moda en Tarapoto (mobile)"
            class="h-full w-full object-cover"
            fetchpriority="high"
            decoding="async">
        </div>
        <div class="swiper-slide">
          <img
            src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Experimenta-nuestra-pasion-por-la-moda.-2.png') }}"
            alt="Colección de vestidos (mobile)"
            class="h-full w-full object-cover"
            loading="lazy"
            decoding="async">
        </div>
      </div>

      <div class="swiper-pagination !bottom-3"></div>
      {{-- En móvil dejo solo bullets; si quieres flechas, descomenta: --}}
      {{-- <button class="swiper-button-prev !text-white"></button>
           <button class="swiper-button-next !text-white"></button> --}}
    </div>
  </div>

</div>
