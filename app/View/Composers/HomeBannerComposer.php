<?php

namespace App\View\Composers;

use App\View\Composers\Helpers\BannerCacheHelper;
use Roots\Acorn\View\Composer;

class HomeBannerComposer extends Composer
{
    protected static $views = ['partials.home-banner-polo-hombre'];

    public function with(): array
    {
        /**
         * ID de la página donde están los campos del banner
         * 
         * INSTRUCCIONES:
         * 1. Crea una página en WordPress (puede estar en borrador)
         * 2. Edita la página y anota el ID que aparece en la URL: post.php?post=123&action=edit
         * 3. Reemplaza el número 0 abajo con el ID de tu página
         * 
         * Alternativa: Usa get_page_by_path() si prefieres buscar por slug
         */
        $page_id = 2873; // ID de la página de configuración del banner

        // Fallback: intentar buscar por slug si no se configuró el ID
        if (!$page_id) {
            $config_page = get_page_by_path('configuracion-banner');
            $page_id = $config_page ? $config_page->ID : null;
        }

        // Leer slides para desktop y móvil (con caché)
        $slides_desktop = BannerCacheHelper::getCachedSlides('slide', $page_id);
        $slides_mobile = BannerCacheHelper::getCachedSlides('slide_mobile', $page_id);
        
        // Si no hay slides móviles, usar los de desktop como fallback
        if (empty($slides_mobile) && !empty($slides_desktop)) {
            $slides_mobile = $slides_desktop;
        }
        
        // Obtener datos del botón (con caché)
        $button_data = BannerCacheHelper::getCachedButton('boton_url', 'boton_texto', $page_id, 'Ver Todo');
        $button_url = $button_data['url'];
        $button_text = $button_data['text'];

        return compact('slides_desktop', 'slides_mobile', 'button_url', 'button_text');
    }
}