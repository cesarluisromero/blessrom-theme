// SIN returns sueltos, SIN auto-ejecutarse aquí
import Swiper, { Navigation, Pagination, Autoplay, Keyboard, A11y, EffectFade } from 'swiper';

export default function mountHeroSwiper() {
  const els = document.querySelectorAll('.js-hero-swiper');
  if (!els.length) return;

  els.forEach((el) => {
    new Swiper(el, {
      modules: [Navigation, Pagination, Autoplay, Keyboard, A11y, EffectFade],
      effect: 'fade',
      fadeEffect: { crossFade: true },
      speed: 700,
      loop: true,
      autoplay: { delay: 5000, disableOnInteraction: false },
      keyboard: { enabled: true },
      a11y: true,
      pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
      navigation: {
        nextEl: el.querySelector('.swiper-button-next'),
        prevEl: el.querySelector('.swiper-button-prev'),
      },
    });
  });
}
