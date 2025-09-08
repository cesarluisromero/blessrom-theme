{{-- Slider solo para escritorio (md en adelante) --}}
<section class="hidden md:block full-bleed text-center py-2 px-4">
    <div class="bg-white">
      <div class="swiper bannervestidos-swiper">
        <!-- Contenedor de slides -->
      
        <div class="swiper-wrapper">
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
        </div>
      

        <!-- Botones -->
        
        <div class="swiper-button-prev bannervestidos-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
        <div class="swiper-button-next bannervestidos-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
        <div class="mt-6 mb-10 flex justify-center">
                <a href="https://blessrom.com/tienda"
                  class="inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-6 py-3 text-white font-semibold shadow hover:bg-yellow-500">
                  Ver Más Estilos
                </a>
        </div>
      </div>
     
    </div>
  
</section>

{{-- Slider solo para móvil (hasta sm) --}}
<section class="block md:hidden w-full">
  <div class="bg-white">
      <div class="swiper bannervestidos-swiper">
        <!-- Contenedor de slides -->
      
        <div class="swiper-wrapper">
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

        </div>
      

        <!-- Botones -->
        
        <div class="swiper-button-prev bannervestidos-swiper-button-prev !hidden md:!flex text-blue-500 absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
        <div class="swiper-button-next bannervestidos-swiper-button-next !hidden md:!flex text-blue-500 absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center bg-white rounded-full shadow-md"></div>
        <div class="mt-6 mb-10 flex justify-center">
                <a href="https://blessrom.com/tienda"
                  class="inline-flex items-center gap-2 rounded-full bg-[#FFB816] px-6 py-3 text-white font-semibold shadow hover:bg-yellow-500">
                  Ver Más Estilos
                </a>
        </div>
      </div>
     
    </div>
</section>


