import '../styles/app.css';
import Alpine from 'alpinejs';




import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import AOS from 'aos';
import 'aos/dist/aos.css';

document.addEventListener('alpine:init', () => {
  // Crea o actualiza el store 'product'
  const initialMap = window.BLESSROM_COLOR_IMAGE_MAP || {};
  if (!Alpine.store('product')) {
    Alpine.store('product', {
      colorImages: initialMap,   // color -> imageId o URL
      currentImage: null,
      slideToImage: () => {},    // lo redefine productGallery()
    });
  } else {
    // Si ya existía (por desktop), asegúrate de que el mapa esté también en móvil
    Alpine.store('product').colorImages = initialMap;
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
                this.errorMessage = "Por favor, selecciona un color.";
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
    spaceBetween: 10,
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
        slidesPerView: 4,
        slidesPerGroup: 4,
      },
    },
  });

  new Swiper('.category-swiper', {
    slidesPerView: 4,
    slidesPerGroup: 4,
    loop: true,
    spaceBetween: 10,
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
        slidesPerView: 4,
        slidesPerGroup: 4, 
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
  
window.productGallery = function () {
  return {
    swiper: null,
    indexById: {},
    indexByUrl: {},
    init() {
      // Instancia Swiper en ESTE carrusel (root del x-data)
      this.swiper = new Swiper(this.$root, {
        loop: true,
        pagination: {
          el: this.$root.querySelector('.swiper-pagination'),
          clickable: true,
        },
      });

      // Mapea imágenes a índices
      const imgs = this.$root.querySelectorAll('.swiper-slide img');
      imgs.forEach((img, i) => {
        const id = img.getAttribute('data-id');
        if (id) this.indexById[String(id)] = i;
        try {
          const abs = new URL(img.src, location.origin).href;
          this.indexByUrl[abs] = i;
        } catch (e) {}
      });

      // Expón el método al store para que lo use variable.php
      const store = Alpine.store('product');
      store.slideToImage = (imageIdOrUrl) => {
        let idx = -1;

        // Si te pasan un ID numérico (recomendado)
        if (imageIdOrUrl !== null && imageIdOrUrl !== undefined) {
          const str = String(imageIdOrUrl);
          if (/^[0-9]+$/.test(str)) {
            idx = this.indexById[str] ?? -1;
          }
        }

        // Si te pasan una URL
        if (idx < 0 && typeof imageIdOrUrl === 'string') {
          try {
            const abs = new URL(imageIdOrUrl, location.origin).href;
            idx = this.indexByUrl[abs] ?? -1;
          } catch (e) {}
        }

        if (idx >= 0) this.swiper.slideTo(idx);
      };
    }
  }
}




