<?php

namespace App\View\Composers;

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
        $page_id = 0; // ⬅️ Reemplaza con el ID de la página de configuración

        // Fallback: intentar buscar por slug si no se configuró el ID
        if (!$page_id) {
            $config_page = get_page_by_path('configuracion-banner-principal');
            $page_id = $config_page ? $config_page->ID : null;
        }

        $processSlides = function (string $prefix, ?int $page_id): array {
            $slides = [];
            if (! $page_id) {
                return $slides;
            }

            for ($i = 1; $i <= 10; $i++) {
                $imagen = get_field("{$prefix}_{$i}_imagen", $page_id);
                $alt = get_field("{$prefix}_{$i}_alt", $page_id);

                if (! $imagen) {
                    continue;
                }

                $imagen_url = '';
                $imagen_alt = $alt ?: '';

                if (is_array($imagen)) {
                    $imagen_url = $imagen['url'] ?? '';
                    $imagen_alt = $imagen_alt ?: ($imagen['alt'] ?? '');
                } elseif (is_numeric($imagen)) {
                    $data = wp_get_attachment_image_src($imagen, 'full');
                    $imagen_url = $data[0] ?? '';
                    if (! $imagen_alt) {
                        $imagen_alt = get_post_meta($imagen, '_wp_attachment_image_alt', true) ?: '';
                    }
                } else {
                    $imagen_url = $imagen;
                }

                if ($imagen_url) {
                    $slides[] = [
                        'imagen' => ['url' => $imagen_url],
                        'alt' => $imagen_alt,
                    ];
                }
            }

            return $slides;
        };

        $slides_desktop = $processSlides('banner2_slide', $page_id);
        $slides_mobile = $processSlides('banner2_slide_mobile', $page_id);

        if (empty($slides_mobile) && ! empty($slides_desktop)) {
            $slides_mobile = $slides_desktop;
        }

        $button_url = $page_id ? get_field('banner2_boton_url', $page_id) : null;
        $button_text = $page_id ? (get_field('banner2_boton_texto', $page_id) ?: 'Ver Más Estilos') : 'Ver Más Estilos';

        return compact('slides_desktop', 'slides_mobile', 'button_url', 'button_text');
    }
}

