
<section class="hidden md:block full-bleed text-center py-2 px-4">
    <div class="bg-white">
      <div class="swiper bannervestidos-swiper">
        <!-- Contenedor de slides -->
      
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <img
              src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Creamy-Black-Clean-Mens-Fashion-Landscape-Banner.png') }}"
              alt="Experimenta nuestra pasión por la moda en Tarapoto"
              class="w-full h-full object-cover"
              fetchpriority="high" decoding="async">
          </div>
          <div class="swiper-slide">
            <img
              src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/White-Simple-Photocentric-Jewelry-Promo-Banner-Landscape-scaled.png') }}"
              alt="Colección de vestidos"
              class="w-full h-full object-cover"
              loading="lazy" decoding="async">
          </div>
        </div>
      

        <!-- Botones -->
        
        <div class="swiper-button-prev bannervestidos-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
        <div class="swiper-button-next bannervestidos-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>

        {{-- botón fuera del swiper --}}
        <div class="mt-6 mb-10 flex justify-center">
          <a href="https://blessrom.com/tienda/?categorias[]=hombre-polos"
            class="inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-6 py-3 text-white font-semibold shadow hover:bg-yellow-500">
            Ver Todo
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="block md:hidden full-bleed text-center py-6 overflow-x-clip">
  <div class="bg-white">
    <div class="swiper bannervestidos-swiper rounded-none" aria-label="Banner vestidos móvil">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <img
            src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Creamy-Black-Clean-Mens-Fashion-Instagram-Post-movil.png') }}"
            alt="Experimenta nuestra pasión por la moda en Tarapoto"
            class="w-full h-auto block"
            fetchpriority="high" decoding="async">
        </div>
        <div class="swiper-slide">
          <img src="{{ esc_url('https://blessrom.com/wp-content/uploads/2025/09/Creamy-Black-Clean-Mens-Fashion-Instagram-Post-movil.png') }}"
               alt="Colección de vestidos"
               class="w-full h-auto block" loading="lazy" decoding="async">
        </div>
        
      </div>

      {{-- Flechas (ocultas en móvil) --}}
      <div class="swiper-button-prev bannervestidos-swiper-button-prev !hidden md:!flex"></div>
      <div class="swiper-button-next bannervestidos-swiper-button-next !hidden md:!flex"></div>
      {{-- debajo del .swiper bannervestidos-swiper --}}
    
      {{-- botón fuera del swiper --}}
      <div class="mt-6 mb-10 flex justify-center">
        <a href="https://blessrom.com/tienda/?categorias[]=vestido"
          class="inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-6 py-3 text-white font-semibold shadow hover:bg-yellow-500">
          Ver Todo
        </a>
      </div>
    </div>
    

  </div>
</section>



