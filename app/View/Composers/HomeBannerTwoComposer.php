<?php

namespace App\View\Composers;

use App\View\Composers\Helpers\BannerCacheHelper;
use Roots\Acorn\View\Composer;

class HomeBannerTwoComposer extends Composer
{
    protected static $views = ['partials.home-banner2'];

    public function with(): array
    {
        /**
         * ID de la página donde están los campos del banner principal (home-banner2)
         *
         * INSTRUCCIONES:
         * 1. Crea una página en WordPress (puede estar en borrador) para configurar este banner.
         * 2. Edita la página y anota el ID que aparece en la URL: post.php?post=123&action=edit.
         * 3. Reemplaza el número 0 de abajo con el ID de tu página.
         *
         * Alternativa: asigna un slug único (por ejemplo, configuracion-banner-principal)
         * y actualiza la búsqueda en get_page_by_path más abajo.
         */
        $page_id = 2915; // ⬅️ Reemplaza con el ID de la página de configuración

        // Fallback: intentar buscar por slug si no se configuró el ID
        if (!$page_id) {
            $config_page = get_page_by_path('configuracion-banner-principal');
            $page_id = $config_page ? $config_page->ID : null;
        }

        // Leer slides para desktop y móvil (con caché)
        $slides_desktop = BannerCacheHelper::getCachedSlides('banner2_slide', $page_id);
        $slides_mobile = BannerCacheHelper::getCachedSlides('banner2_slide_mobile', $page_id);

        // Si no hay slides móviles, usar los de desktop como fallback
        if (empty($slides_mobile) && !empty($slides_desktop)) {
            $slides_mobile = $slides_desktop;
        }

        // Obtener datos del botón (con caché)
        $button_data = BannerCacheHelper::getCachedButton('banner2_boton_url', 'banner2_boton_texto', $page_id, 'Ver Más Estilos');
        $button_url = $button_data['url'];
        $button_text = $button_data['text'];

        return compact('slides_desktop', 'slides_mobile', 'button_url', 'button_text');
    }
}

