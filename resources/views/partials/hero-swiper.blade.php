<div class="relative mx-auto max-w-[120rem] px-4 sm:px-6 lg:px-8">
  <div class="swiper js-hero-swiper rounded-2xl overflow-hidden
              h-[48vh] sm:h-[56vh] md:h-[64vh] lg:h-[72vh]"  {{-- 👈 altura por viewport --}}
       aria-label="Hero promocional">

    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <img
          src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Experimenta-nuestra-pasion-por-la-moda.png') }}"
          alt="Experimenta nuestra pasión por la moda en Tarapoto"
          class="h-full w-full object-cover"
          fetchpriority="high"
          decoding="async">
      </div>

      <div class="swiper-slide">
        <img
          src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Experimenta-nuestra-pasion-por-la-moda.-2.png') }}"
          alt="Colección de vestidos"
          class="h-full w-full object-cover"
          loading="lazy"
          decoding="async">
      </div>
    </div>

    <div class="swiper-pagination !bottom-4"></div>
    <button class="swiper-button-prev !text-white !w-12 !h-12 !-translate-y-1/2"><span class="sr-only">Anterior</span></button>
    <button class="swiper-button-next !text-white !w-12 !h-12 !-translate-y-1/2"><span class="sr-only">Siguiente</span></button>
  </div>
</div>
