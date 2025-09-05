import Swiper, { Navigation, Pagination, Autoplay, Keyboard, A11y, EffectFade } from 'swiper';

export function mountHeroSwiper() {
  const els = document.querySelectorAll('.js-hero-swiper');
  if (!els.length) return;

  els.forEach((el) => {
    // eslint-disable-next-line no-new
    new Swiper(el, {
      modules: [Navigation, Pagination, Autoplay, Keyboard, A11y, EffectFade],
      effect: 'fade',             // o 'slide' si prefieres
      fadeEffect: { crossFade: true },
      speed: 700,
      loop: true,
      autoplay: { delay: 5000, disableOnInteraction: false },
      keyboard: { enabled: true },
      a11y: true,
      pagination: {
        el: el.querySelector('.swiper-pagination'),
        clickable: true,
      },
      navigation: {
        nextEl: el.querySelector('.swiper-button-next'),
        prevEl: el.querySelector('.swiper-button-prev'),
      },
    });
  });
}

// Auto-mount
document.addEventListener('DOMContentLoaded', mountHeroSwiper);
