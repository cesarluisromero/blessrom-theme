{{-- resources/views/partials/hero-swiper.blade.php --}}
<div class="relative mx-auto max-w-[120rem] px-4 sm:px-6 lg:px-8">
  <div
    class="swiper js-hero-swiper rounded-2xl overflow-hidden"
    aria-label="Hero promocional"
  >
    <div class="swiper-wrapper">

      {{-- Slide 1 --}}
      <div class="swiper-slide">
        <figure class="aspect-[21/9] sm:aspect-[16/7] md:aspect-[16/6] lg:aspect-[16/5] bg-slate-100">
          <img
            src="{{ get_theme_file_uri('https://blessrom.com/wp-content/uploads/2025/09/Experimenta-nuestra-pasion-por-la-moda.png') }}"
            alt="Experimenta nuestra pasión por la moda en Tarapoto"
            class="h-full w-full object-cover"
            fetchpriority="high"
            decoding="async"
          >
        </figure>
      </div>

      {{-- Slide 2 --}}
      <div class="swiper-slide">
        <figure class="aspect-[21/9] sm:aspect-[16/7] md:aspect-[16/6] lg:aspect-[16/5] bg-slate-100">
          <img
            src="{{ get_theme_file_uri('https://blessrom.com/wp-content/uploads/2025/09/Experimenta-nuestra-pasion-por-la-moda.-2.png') }}"
            alt="Colección de vestidos"
            class="h-full w-full object-cover"
            loading="lazy"
            decoding="async"
          >
        </figure>
      </div>

    </div>

    {{-- Controles --}}
    <div class="swiper-pagination !bottom-4"></div>

    <button class="swiper-button-prev !text-white !w-12 !h-12 !-translate-y-1/2">
      <span class="sr-only">Anterior</span>
    </button>
    <button class="swiper-button-next !text-white !w-12 !h-12 !-translate-y-1/2">
      <span class="sr-only">Siguiente</span>
    </button>

    {{-- Sombras laterales para mejorar contraste de flechas --}}
    <div class="pointer-events-none absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-black/20 to-transparent"></div>
    <div class="pointer-events-none absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-black/20 to-transparent"></div>
  </div>
</div>
