<?php

namespace App\View\Composers;

use App\View\Composers\Helpers\BannerCacheHelper;
use Roots\Acorn\View\Composer;

class HomeBannerVestidosComposer extends Composer
{
    protected static $views = ['partials.home-banner-vestidos'];

    public function with(): array
    {
        /**
         * ID de la página donde están los campos del banner de vestidos
         * 
         * INSTRUCCIONES:
         * 1. Puedes usar la misma página que el banner de polos (2873)
         * 2. O crear una página separada y cambiar el ID aquí
         */
        $page_id = 2894; // ID de la página de configuración del banner

        // Fallback: intentar buscar por slug si no se configuró el ID
        if (!$page_id) {
            $config_page = get_page_by_path('configuracion-banner');
            $page_id = $config_page ? $config_page->ID : null;
        }

        // Leer slides para desktop y móvil (con caché)
        $slides_desktop = BannerCacheHelper::getCachedSlides('slide_vestidos', $page_id);
        $slides_mobile = BannerCacheHelper::getCachedSlides('slide_vestidos_mobile', $page_id);
        
        // Si no hay slides móviles, usar los de desktop como fallback
        if (empty($slides_mobile) && !empty($slides_desktop)) {
            $slides_mobile = $slides_desktop;
        }
        
        // Obtener datos del botón (con caché)
        $button_data = BannerCacheHelper::getCachedButton('boton_vestidos_url', 'boton_vestidos_texto', $page_id, 'Ver más vestidos');
        $button_url = $button_data['url'];
        $button_text = $button_data['text'];

        return compact('slides_desktop', 'slides_mobile', 'button_url', 'button_text');
    }
}

