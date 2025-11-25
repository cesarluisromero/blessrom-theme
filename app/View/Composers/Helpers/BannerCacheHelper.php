<?php

namespace App\View\Composers\Helpers;

/**
 * Helper para procesar y cachear datos de banners ACF
 */
class BannerCacheHelper
{
    /**
     * Tiempo de expiración de la caché (1 hora)
     */
    const CACHE_EXPIRATION = HOUR_IN_SECONDS;

    /**
     * Procesa slides desde campos ACF con caché
     *
     * @param string $prefix Prefijo del campo (ej: 'slide', 'slide_vestidos')
     * @param int|null $page_id ID de la página donde están los campos
     * @param int $max_slides Número máximo de slides a procesar
     * @return array Array de slides procesados
     */
    public static function getCachedSlides(string $prefix, ?int $page_id, int $max_slides = 10): array
    {
        if (!$page_id || $page_id <= 0) {
            return [];
        }

        // Clave única para la caché
        $cache_key = self::getCacheKey($prefix, $page_id);

        // Intentar obtener de la caché
        $slides = get_transient($cache_key);

        if (false !== $slides) {
            return $slides;
        }

        // Si no está en caché, procesar
        $slides = self::processSlides($prefix, $page_id, $max_slides);

        // Guardar en caché
        set_transient($cache_key, $slides, self::CACHE_EXPIRATION);

        return $slides;
    }

    /**
     * Procesa slides desde campos ACF
     *
     * @param string $prefix Prefijo del campo
     * @param int $page_id ID de la página
     * @param int $max_slides Número máximo de slides
     * @return array Array de slides
     */
    private static function processSlides(string $prefix, int $page_id, int $max_slides): array
    {
        $slides = [];

        for ($i = 1; $i <= $max_slides; $i++) {
            $imagen = get_field("{$prefix}_{$i}_imagen", $page_id);
            $alt = get_field("{$prefix}_{$i}_alt", $page_id);

            if (!$imagen) {
                continue;
            }

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

        return $slides;
    }

    /**
     * Obtiene campos de botón con caché
     *
     * @param string $url_field Nombre del campo URL
     * @param string $text_field Nombre del campo texto
     * @param int|null $page_id ID de la página
     * @param string $default_text Texto por defecto
     * @return array Array con 'url' y 'text'
     */
    public static function getCachedButton(string $url_field, string $text_field, ?int $page_id, string $default_text = 'Ver más'): array
    {
        if (!$page_id || $page_id <= 0) {
            return [
                'url' => null,
                'text' => $default_text
            ];
        }

        $cache_key = self::getCacheKey("button_{$url_field}_{$text_field}", $page_id);
        $button = get_transient($cache_key);

        if (false !== $button) {
            return $button;
        }

        $button = [
            'url' => get_field($url_field, $page_id) ?: null,
            'text' => get_field($text_field, $page_id) ?: $default_text
        ];

        set_transient($cache_key, $button, self::CACHE_EXPIRATION);

        return $button;
    }

    /**
     * Genera una clave única para la caché
     *
     * @param string $prefix Prefijo del campo
     * @param int $page_id ID de la página
     * @return string Clave de caché
     */
    private static function getCacheKey(string $prefix, int $page_id): string
    {
        return sprintf('blessrom_banner_%s_%d', sanitize_key($prefix), $page_id);
    }

    /**
     * Limpia la caché de un banner específico
     *
     * @param string $prefix Prefijo del campo
     * @param int $page_id ID de la página
     * @return bool True si se limpió correctamente
     */
    public static function clearCache(string $prefix, int $page_id): bool
    {
        $cache_key = self::getCacheKey($prefix, $page_id);
        return delete_transient($cache_key);
    }

    /**
     * Limpia toda la caché de banners de una página
     *
     * @param int $page_id ID de la página
     * @return int Número de transients eliminados
     */
    public static function clearAllPageCache(int $page_id): int
    {
        global $wpdb;
        
        $pattern = $wpdb->esc_like('_transient_blessrom_banner_') . '%' . $wpdb->esc_like("_{$page_id}");
        $sql = "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s";
        
        $deleted = $wpdb->query($wpdb->prepare($sql, $pattern));
        
        // También limpiar transients con timeout
        $pattern_timeout = $wpdb->esc_like('_transient_timeout_blessrom_banner_') . '%' . $wpdb->esc_like("_{$page_id}");
        $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $pattern_timeout));
        
        return $deleted;
    }
}

