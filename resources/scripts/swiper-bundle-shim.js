import Swiper from 'swiper';
import {
  Navigation, Pagination, Autoplay, Scrollbar, Keyboard, A11y, EffectFade,
} from 'swiper/modules';

// Deja Swiper “preconfigurado” como hacía el bundle UMD:
Swiper.use([Navigation, Pagination, Autoplay, Scrollbar, Keyboard, A11y, EffectFade]);

export default Swiper;
