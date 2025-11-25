import '../styles/app.css';
import Alpine from 'alpinejs';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import AOS from 'aos';
import 'aos/dist/aos.css';



document.addEventListener('alpine:init', () => {
  const map = window.BLESSROM_COLOR_IMAGE_MAP; // puede ser undefined

  if (!Alpine.store('product')) {
    Alpine.store('product', {
      colorImages: (map && Object.keys(map).length) ? map : {},
      currentImage: null,
      // 🔸 OJO: no definimos slideToImage aquí para no pisar desktop
    });
  } else {
    // Solo fusionar si el mapa existe (no vacíes el que ya usa desktop)
    if (map && Object.keys(map).length) {
      Alpine.store('product').colorImages = {
        ...(Alpine.store('product').colorImages || {}),
        ...map
      };
    }
  }
});

function alpineCart() {
    return {
        selected_pa_talla: '',
        selected_pa_color: '',
        quantity: 1,
        maxQty: 0,
        errorMessage: '',
        availableVariations: [],
        cartQuantities: {},
        currentVariationId: 0,

        init() {
            this.availableVariations = JSON.parse(this.$root.dataset.product_variations || '[]');
            this.cartQuantities = JSON.parse(this.$root.dataset.cart_quantities || '{}');
            this.$watch('selected_pa_color', color => {
              console.log('🟢 Color seleccionado:', color);
              // Cuando cambia el color seleccionado
              const talla = this.selected_pa_talla;
              if (!color || !talla) return; // esperar a que ambos estén seleccionados
              const variation = this.availableVariations.find(v =>
                  v.attributes['attribute_pa_talla'] === talla &&
                  v.attributes['attribute_pa_color'] === color
              );
              if (variation) {
                  const url = Alpine.store('product')?.colorImages?.[color];
                  if (url) Alpine.store('product').slideToImage(url);
              }
          });
        },

        selectedVariationId() {
            const match = this.availableVariations.find(v => {
                return Object.entries(v.attributes).every(([key, val]) => {
                    const attr = key.replace('attribute_', '');
                    return this['selected_' + attr] === val;
                });
            });
            return match ? match.variation_id : 0;
        },

        updateMaxQty() {
            const match = this.availableVariations.find(v => {
                return Object.entries(v.attributes).every(([key, val]) => {
                    const attr = key.replace('attribute_', '');
                    return this['selected_' + attr] === val;
                });
            });

            if (match) {
                const vid = match.variation_id;
                const stock = parseInt(match.max_qty) || 0;
                const inCart = this.cartQuantities?.[vid] ?? 0;
                this.maxQty = stock - inCart;
                this.currentVariationId = vid;

                if (inCart >= stock) {
                    this.errorMessage = "Ya tienes en el carrito toda la cantidad disponible de este producto.";
                    this.maxQty = 0;
                    this.quantity = 0;
                } else {
                    this.errorMessage = "";
                    this.quantity = 1;
                }

                this.$refs.variationId.value = vid;
                this.$refs.maxQty.value = this.maxQty;
                
            } else {
                this.maxQty = 10;
                this.quantity = 1;
                this.errorMessage = "";
                this.currentVariationId = 0;
                this.$refs.variationId.value = 0;
                this.$refs.maxQty.value = 0;
            }
        },

        validColors() {
            const talla = this.selected_pa_talla;
            if (!talla) return [];

            const colors = new Set();
            this.availableVariations.forEach(v => {
                if (v.attributes['attribute_pa_talla'] === talla) {
                    const color = v.attributes['attribute_pa_color'];
                    if (color) colors.add(color);
                }
            });

            return Array.from(colors);
        },

        validateBeforeSubmit(form) {
            if (!this.selected_pa_talla) {
                this.errorMessage = "Por favor, selecciona una talla.";
                return;
            }

            if (!this.selected_pa_color) {
                this.errorMessage = "Por favor, selecciona el color.";
                return;
            }

            if (this.maxQty <= 0) {
                return;
            }

            this.addToCartAjax(form);
        },

        

        async addToCartAjax(form) {
          console.log('🛒 Ejecutando addToCartAjax', form);

          let formData = new FormData(form);

          // 👇 Agregar campos obligatorios
          formData.append('action', 'add_to_cart_custom');
          console.log('muestra formData cuando agrego action-add_to_cart_custom', formData);
          if (!form.dataset.product_id) {
              console.error('Falta el data-product_id en el formulario');
              this.errorMessage = "Error interno: falta ID del producto.";
              return;
          }

          formData.append('product_id', form.dataset.product_id);
          
          
          // Evita campos duplicados solo para claves sensibles
          const cleaned = new FormData();
          const skipKeys = ['quantity', 'variation_id', 'add-to-cart'];

          const seen = new Set();
          for (const [key, value] of formData.entries()) {
              if (skipKeys.includes(key)) {
                  if (!seen.has(key)) {
                      cleaned.append(key, value);
                      seen.add(key);
                  } else {
                      console.warn(`🟡 Duplicado sensible omitido: ${key}`);
                  }
              } else {
                  cleaned.append(key, value);
              }
          }

          console.log('muestra cleaned cuando es igual ', cleaned);
          formData = cleaned;


          // 👇 Opcional: mostrar lo que realmente se enviará
          for (let [k, v] of formData.entries()) {
              console.log('muestro lo que se enviará de formData', `${k}: ${v}`);
          }

          try {
            const response = await fetch(wc_add_to_cart_params.ajax_url, {
              method: 'POST',
              credentials: 'same-origin',
              body: formData,
            });

            // Si el servidor redirige, lo manejamos aquí
            if (response.redirected) {
              window.location.href = response.url;
            } else {
              // Si no hubo redirección, por seguridad te llevamos al carrito igual
              window.location.href = wc_add_to_cart_params.cart_url;
            }

          } catch (err) {
            console.error('❌ Error inesperado al agregar al carrito:', err);
            this.errorMessage = "Error inesperado al agregar al carrito.";
          }

        }
    }
};

window.productGallery = function () {
  console.log('Móvil activo');
  return {
    swiper: null,

    init() {
      // Instancia Swiper en ESTE carrusel (no por selector global)
      console.log('Móvil ESTE carrusel');
      this.swiper = new Swiper(this.$root, {
        loop: true,
        pagination: {
          el: this.$root.querySelector('.swiper-pagination'),
          clickable: true,
        },
      });

      // --- helpers para normalizar URLs (evitar fallos por ?resize=..., CDN, etc.)
      const normalizarUrlImagen = (url) => {
          if (!url) return '';
          try {
            let base = url.split('#')[0].split('?')[0];
            const idx = base.indexOf('/uploads/');
            if (idx !== -1) base = base.substring(idx);
            base = decodeURIComponent(base).toLowerCase();
            base = base.replace(/\.(jpe?g|png|webp|avif)$/i, '');
            return base;
          } catch (e) { return url; }
        };
      
      // Asegurar que el store exista
      const store =
        Alpine.store('product') ||
        Alpine.store('product', { colorImages: {}, currentImage: null });
        console.log('Store es:', store);
      // 👉 redefinimos slideToImage usando comparación robusta de URLs
      store.slideToImage = (targetUrl) => {
        if (!this.swiper || !targetUrl) return;

        const objetivo = normalizarUrlImagen(targetUrl);
        let foundIndex = -1;
        const slides = this.swiper.slides; // incluye clones por loop:true

        // 1) Buscar si ya existe esa imagen en alguna slide
        for (let i = 0; i < slides.length; i++) {
          const img = slides[i].querySelector('img');
          if (!img) continue;
          const raw = img.currentSrc || img.src || '';
          const actual = normalizarUrlImagen(raw);
          if (actual === objetivo || actual.endsWith(objetivo) || objetivo.endsWith(actual)) {
            foundIndex = i;
            break;
          }
        }

        if (foundIndex >= 0) {
          // 2) Si existe, mover el slider a esa slide
          this.swiper.slideTo(foundIndex);
        } else {
          // 3) Si NO existe, fallback: reemplazar la imagen del slide ACTIVO
          const active = this.swiper.slides[this.swiper.activeIndex];
          const img = active && active.querySelector('img');

          // (opcional) Pre-cargar para evitar parpadeo
          const pre = new Image();
          pre.onload = () => {
            if (img) {
              img.src = targetUrl;
              this.swiper.update(); // refresca tamaños/observadores
            } else {
              // o añadir una nueva slide al final y moverse a ella
              this.swiper.addSlide(
                this.swiper.slides.length,
                `<div class="swiper-slide"><img src="${targetUrl}" class="w-full h-auto object-contain lg:hidden mb-6"></div>`
              );
              this.swiper.update();
              this.swiper.slideTo(this.swiper.slides.length - 1);
            }
          };
          pre.src = targetUrl;
        }

        // Mantén el estado global sincronizado (útil para desktop)
        Alpine.store('product').currentImage = targetUrl;
      };
    },
  };
};


window.alpineCart = alpineCart;
window.Alpine = Alpine;
Alpine.start();
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('menu-toggle');
  const menu = document.getElementById('mobile-menu');

  // ✅ Solo agregar listener si el botón existe
  if (toggle && menu) {
    toggle.addEventListener('click', () => {
      menu.classList.toggle('hidden');
      menu.classList.toggle('animate-slide-in');
    });

    menu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        menu.classList.add('hidden');
      });
    });
  }
});


  document.addEventListener('DOMContentLoaded', function () {
    const updateCartCount = () => {
      fetch('/blessrom/?wc-ajax=get_refreshed_fragments', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      })
      .then(r => r.json())
      .then(data => {
        const wrapper = document.createElement('div');
        wrapper.innerHTML = data.fragments['div.widget_shopping_cart_content'];
        const updatedCart = wrapper.querySelector('#cart-count');
        const target = document.getElementById('cart-count');
  
        if (updatedCart && target) {
          target.textContent = updatedCart.textContent.trim();
          console.log('✅ Actualizado a:', updatedCart.textContent);
        }
      });
    };
  
    // Inicial
    updateCartCount();
  
    // Al agregar producto
    document.body.addEventListener('added_to_cart', updateCartCount);
  
    // Al eliminar producto
    document.body.addEventListener('click', function (e) {
      const removeBtn = e.target.closest('.remove_from_cart_button');
      if (removeBtn) {
        setTimeout(() => {
          updateCartCount();
        }, 1000);
      }
    });
  
    // Fragment refresh
    document.body.addEventListener('wc_fragments_refreshed', updateCartCount);
  });

  document.addEventListener('DOMContentLoaded', function () {
  new Swiper('.product-swiper', {
    slidesPerView: 4,
    slidesPerGroup: 4,
    loop: true,
    spaceBetween: 18,
    navigation: {
      nextEl: '.product-swiper-button-next',
      prevEl: '.product-swiper-button-prev',
      enabled: true,
    },
    scrollbar: {
      el: '.swiper-scrollbar',
      draggable: true,
      hide: true,
    },
    autoplay: {
      delay: 3000, // ⏱ Tiempo entre slides en milisegundos (3000 = 3 segundos)
      disableOnInteraction: false // sigue después de hacer clic o tocar
    },
  
    breakpoints: {
      0: {
        slidesPerView: 1,
        slidesPerGroup: 1, 
        navigation: { enabled: false },
      },
      640: {   
        slidesPerView: 3,
        slidesPerGroup: 3,
      },
      1024: {
        slidesPerView: 6,
        slidesPerGroup: 6,
      },
    },
  });

  new Swiper('.category-swiper', {
    slidesPerView: 4,
    slidesPerGroup: 4,
    loop: true,
    spaceBetween: 18,
    navigation: { 
      nextEl: '.category-swiper-button-next',
      prevEl: '.category-swiper-button-prev',
      enabled: true,
    },
    autoplay: {
      delay: 3000, // ⏱ Tiempo entre slides en milisegundos (3000 = 3 segundos)
      disableOnInteraction: false // sigue después de hacer clic o tocar
    },
    pagination: {
      el: '.category-swiper-pagination',
      clickable: true,
      enabled: false,
    },
    breakpoints: {
      0: { 
        slidesPerView: 1,
        slidesPerGroup: 1,
        navigation: { enabled: false },
        pagination:  { enabled: true  },
      },
      640: {   
        slidesPerView: 3,
        slidesPerGroup: 3,
      },
      1024: {
        slidesPerView: 6,
        slidesPerGroup: 6, 
      },
    },
  });
  // HERO (si lo usas) - bannervestidos-swiper
  const bannervestidosElements = document.querySelectorAll('.bannervestidos-swiper:not(.swiper-initialized)');
  bannervestidosElements.forEach((element) => {
    const slides = element.querySelectorAll('.swiper-slide');
    const slideCount = slides.length;
    const hasMultipleSlides = slideCount > 1;
    
    const section = element.closest('section');
    const nextBtn = section ? section.querySelector('.bannervestidos-swiper-button-next') : null;
    const prevBtn = section ? section.querySelector('.bannervestidos-swiper-button-prev') : null;
    
    new Swiper(element, {
      slidesPerView: 1,
      slidesPerGroup: 1,
      loop: hasMultipleSlides,
      spaceBetween: 10,
      touchEventsTarget: 'container',
      navigation: hasMultipleSlides && nextBtn && prevBtn ? { 
        nextEl: nextBtn,
        prevEl: prevBtn,
        enabled: true,
      } : false,
      autoplay: hasMultipleSlides ? {
        delay: 5000,
        disableOnInteraction: false
      } : false,
      pagination: {
        el: '.bannervestidos-swiper-pagination',
        clickable: true,
        enabled: false,
      },
      observer: true,
      observeParents: true,
      watchOverflow: true,
      breakpoints: {
        0: { 
          slidesPerView: 1,
          slidesPerGroup: 1,
          navigation: { enabled: false },
          pagination: { enabled: hasMultipleSlides },
        },
        640: {   
          slidesPerView: 1,
          slidesPerGroup: 1,
        },
        1024: {
          slidesPerView: 1,
          slidesPerGroup: 1, 
        },
      },
    });
  });

  const createSingleImageSwiper = ({ selector, nextSelector, prevSelector, paginationSelector }) => {
    const elements = document.querySelectorAll(`${selector}:not(.swiper-initialized)`);

    elements.forEach((element) => {
      // Si ya está inicializado, no hacer nada
      if (element.swiper) {
        return;
      }

      const slides = element.querySelectorAll('.swiper-slide');
      const slideCount = slides.length;

      if (slideCount === 0) {
        return;
      }

      const section = element.closest('section');
      const nextBtn = section ? section.querySelector(nextSelector) : null;
      const prevBtn = section ? section.querySelector(prevSelector) : null;
      const paginationEl = paginationSelector && section ? section.querySelector(paginationSelector) : null;

      const swiperConfig = {
        slidesPerView: 1,
        slidesPerGroup: 1,
        loop: slideCount > 1,
        spaceBetween: 10,
        allowTouchMove: slideCount > 1,
        touchEventsTarget: 'container',
        autoplay: slideCount > 1 ? {
          delay: 5000,
          disableOnInteraction: false,
        } : false,
        navigation: slideCount > 1 && nextBtn && prevBtn ? {
          nextEl: nextBtn,
          prevEl: prevBtn,
          enabled: true,
        } : false,
        pagination: false,
        observer: true,
        observeParents: true,
        watchOverflow: true,
        breakpoints: {
          0: {
            slidesPerView: 1,
            slidesPerGroup: 1,
          },
          640: {
            slidesPerView: 1,
            slidesPerGroup: 1,
          },
          1024: {
            slidesPerView: 1,
            slidesPerGroup: 1,
          },
        },
      };

      if (slideCount > 1 && paginationEl) {
        swiperConfig.pagination = {
          el: paginationEl,
          clickable: true,
          enabled: false,
        };
        swiperConfig.breakpoints[0].pagination = { enabled: true };
      }

      try {
        const swiper = new Swiper(element, swiperConfig);
        
        // Actualizar cuando el elemento se muestre (para elementos ocultos por CSS responsive)
        const updateWhenVisible = () => {
          if (swiper) {
            swiper.update();
            swiper.slideTo(0, 0);
          }
        };
        
        // Usar MutationObserver para detectar cambios en clases CSS
        const sectionElement = element.closest('section');
        if (sectionElement) {
          const styleObserver = new MutationObserver(() => {
            const style = window.getComputedStyle(sectionElement);
            if (style.display !== 'none' && swiper) {
              // Pequeño delay para asegurar que el layout esté listo
              setTimeout(updateWhenVisible, 50);
            }
          });
          
          styleObserver.observe(sectionElement, {
            attributes: true,
            attributeFilter: ['class', 'style']
          });
        }
        
        // También actualizar en resize
        let resizeTimer;
        window.addEventListener('resize', () => {
          clearTimeout(resizeTimer);
          resizeTimer = setTimeout(() => {
            if (swiper) {
              swiper.update();
            }
          }, 250);
        });
        
      } catch (error) {
        console.error(`Error inicializando ${selector}:`, error);
      }
    });
  };

  const initHomeBanners = () => {
    createSingleImageSwiper({
      selector: '.banner-vestidos-swiper',
      nextSelector: '.banner-vestidos-swiper-button-next',
      prevSelector: '.banner-vestidos-swiper-button-prev',
      paginationSelector: '.banner-vestidos-swiper-pagination',
    });

    createSingleImageSwiper({
      selector: '.home-banner2-swiper',
      nextSelector: '.home-banner2-swiper-button-next',
      prevSelector: '.home-banner2-swiper-button-prev',
      paginationSelector: '.home-banner2-swiper-pagination',
    });
  };

  initHomeBanners();

  // slider de vestidos 
  new Swiper('.vestidos-swiper', {
    slidesPerView: 4,
    slidesPerGroup: 4,
    loop: true,
    spaceBetween: 18,
    navigation: { 
      nextEl: '.vestidos-swiper-button-next',
      prevEl: '.vestidos-swiper-button-prev',
      enabled: true,
    },
    autoplay: {
      delay: 5000, // ⏱ Tiempo entre slides en milisegundos (3000 = 3 segundos)
      disableOnInteraction: false // sigue después de hacer clic o tocar
    },
    pagination: {
      el: '.vestidos-swiper-pagination',
      clickable: true,
      enabled: false,
    },
    breakpoints: {
      0: { 
        slidesPerView: 1,
        slidesPerGroup: 1,
        navigation: { enabled: false },
        pagination:  { enabled: true  },
      },
      640: {   
        slidesPerView: 3,
        slidesPerGroup: 3,
      },
      1024: {
        slidesPerView: 6,
        slidesPerGroup: 6, 
      },
    },
  });

});

  
  
document.addEventListener('DOMContentLoaded', function () {
  AOS.init({
    once: true,
    duration: 800,
    easing: 'ease-in-out',
  });
});



  






