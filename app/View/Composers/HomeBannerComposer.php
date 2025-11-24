<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class HomeBannerComposer extends Composer
{
    protected static $views = ['partials.home-banner-polo-hombre'];

    public function with(): array
    {
        $slides = get_field('slides', 'option') ?: [];
        $button_url = get_field('boton_url', 'option');
        $button_text = get_field('boton_texto', 'option') ?: 'Ver Todo';

        return compact('slides', 'button_url', 'button_text');
    }
}