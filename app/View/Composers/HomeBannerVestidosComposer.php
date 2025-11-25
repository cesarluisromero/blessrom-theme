<?php

namespace App\View\Composers;

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

        // Función helper para procesar slides
        $processSlides = function($prefix, $page_id) {
            $slides = [];
            // Leer hasta 10 slides (puedes ajustar este número)
            for ($i = 1; $i <= 10; $i++) {
                $imagen = get_field("{$prefix}_{$i}_imagen", $page_id);
                $alt = get_field("{$prefix}_{$i}_alt", $page_id);
                
                // Si hay imagen, procesar según el formato de retorno de ACF
                if ($imagen) {
                    $imagen_url = '';
                    $imagen_alt = $alt ?: '';
                    
                    // Manejar diferentes formatos de retorno de ACF
                    if (is_array($imagen)) {
                        // Array de imagen (formato completo)
                        $imagen_url = $imagen['url'] ?? '';
                        $imagen_alt = $imagen_alt ?: ($imagen['alt'] ?? '');
                    } elseif (is_numeric($imagen)) {
                        // ID de imagen
                        $imagen_data = wp_get_attachment_image_src($imagen, 'full');
                        $imagen_url = $imagen_data ? $imagen_data[0] : '';
                        if (!$imagen_alt) {
                            $imagen_alt = get_post_meta($imagen, '_wp_attachment_image_alt', true) ?: '';
                        }
                    } else {
                        // URL directa (string)
                        $imagen_url = $imagen;
                    }
                    
                    // Solo añadir si tenemos una URL válida
                    if ($imagen_url) {
                        $slides[] = [
                            'imagen' => ['url' => $imagen_url],
                            'alt' => $imagen_alt
                        ];
                    }
                }
            }
            return $slides;
        };

        // Leer slides para desktop y móvil (usando prefijo 'slide_vestidos')
        $slides_desktop = $page_id ? $processSlides('slide_vestidos', $page_id) : [];
        $slides_mobile = $page_id ? $processSlides('slide_vestidos_mobile', $page_id) : [];
        
        // Si no hay slides móviles, usar los de desktop como fallback
        if (empty($slides_mobile) && !empty($slides_desktop)) {
            $slides_mobile = $slides_desktop;
        }
        
        $button_url = $page_id ? get_field('boton_vestidos_url', $page_id) : null;
        $button_text = $page_id ? (get_field('boton_vestidos_texto', $page_id) ?: 'Ver más vestidos') : 'Ver más vestidos';

        // DEBUG TEMPORAL - Quitar después de verificar
        $debug_info = [];
        if (current_user_can('administrator')) {
            $debug_info = [
                'page_id' => $page_id,
                'desktop_count' => count($slides_desktop),
                'mobile_count' => count($slides_mobile),
                'button_url' => $button_url,
                'button_text' => $button_text,
                'test_fields' => [
                    'slide_vestidos_1_imagen' => get_field('slide_vestidos_1_imagen', $page_id) ? 'EXISTS' : 'NOT FOUND',
                    'slide_vestidos_1_alt' => get_field('slide_vestidos_1_alt', $page_id) ? 'EXISTS' : 'NOT FOUND',
                    'slide_vestidos_mobile_1_imagen' => get_field('slide_vestidos_mobile_1_imagen', $page_id) ? 'EXISTS' : 'NOT FOUND',
                ]
            ];
        }

        return compact('slides_desktop', 'slides_mobile', 'button_url', 'button_text', 'debug_info');
    }
}

