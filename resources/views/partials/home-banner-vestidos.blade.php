
<section class="hidden md:block w-full text-center py-2 px-4">
  <div class="max-w-screen-2xl mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
      <div class="swiper bannervestidos-swiper">
        <!-- Contenedor de slides -->
      
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <img
              src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Experimenta-nuestra-pasion-por-la-moda.png') }}"
              alt="Experimenta nuestra pasión por la moda en Tarapoto"
              class="w-full h-full object-cover"
              fetchpriority="high" decoding="async">
          </div>
          <div class="swiper-slide">
            <img
              src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Experimenta-nuestra-pasion-por-la-moda.-150.png') }}"
              alt="Colección de vestidos"
              class="w-full h-full object-cover"
              loading="lazy" decoding="async">
          </div>
        </div>
      

        <!-- Botones -->
        
        <div class="swiper-button-prev bannervestidos-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
        <div class="swiper-button-next bannervestidos-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>

      </div>
    </div>
  </div>
</section>

<section class="block md:hidden full-bleed text-center py-2 overflow-x-clip">
  <div class="bg-white">
    <div class="swiper bannervestidos-swiper rounded-none" aria-label="Banner vestidos móvil">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <img
            src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/1.png') }}"
            alt="Experimenta nuestra pasión por la moda en Tarapoto"
            class="w-full h-auto block"
            fetchpriority="high" decoding="async">
        </div>
        <div class="swiper-slide">
          <img src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/1-1.png') }}"
               alt="Colección de vestidos"
               class="w-full h-auto block" loading="lazy" decoding="async">
        </div>
        <div class="swiper-slide">
          <img src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/1-2.png') }}"
               alt="Colección de vestidos"
               class="w-full h-auto block" loading="lazy" decoding="async">
        </div>
        <div class="swiper-slide">
          <img src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/2.png') }}"
               alt="Colección de vestidos"
               class="w-full h-auto block" loading="lazy" decoding="async">
        </div>
      </div>

      {{-- Flechas (ocultas en móvil) --}}
      <div class="swiper-button-prev bannervestidos-swiper-button-prev !hidden md:!flex"></div>
      <div class="swiper-button-next bannervestidos-swiper-button-next !hidden md:!flex"></div>
    </div>
    {{-- debajo del .swiper bannervestidos-swiper --}}
    <div class="mt-4 flex justify-center">
      <a
        href="{{ esc_url( add_query_arg(
          ['categorias' => ['vestido']],
          wc_get_page_permalink('shop')
        )) }}"
        class="inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-6 py-3 text-white font-semibold shadow hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
        aria-label="Ver más vestidos"
      >
        Ver más vestidos
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
          <path fill="currentColor" d="m10 17l5-5l-5-5v10Z"/>
        </svg>
      </a>
    </div>

  </div>
</section>



