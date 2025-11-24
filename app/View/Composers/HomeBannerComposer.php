<?php

namespace App\View\Composers;

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

        // Leer campos individuales (sin Repetidor - compatible con ACF gratuito)
        // Construir array de slides desde campos individuales
        $slides = [];
        if ($page_id) {
            // Leer hasta 10 slides (puedes ajustar este número)
            for ($i = 1; $i <= 10; $i++) {
                $imagen = get_field("slide_{$i}_imagen", $page_id);
                $alt = get_field("slide_{$i}_alt", $page_id);
                
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
        }
        
        $button_url = $page_id ? get_field('boton_url', $page_id) : null;
        $button_text = $page_id ? (get_field('boton_texto', $page_id) ?: 'Ver Todo') : 'Ver Todo';

        return compact('slides', 'button_url', 'button_text');
    }
}