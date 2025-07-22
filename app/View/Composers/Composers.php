<?php

namespace App\View;

use Roots\Acorn\View\Composer;

class Composers extends Composer
{
    protected static $composers = [
        Composers\FrontPage::class,
        Composers\SingleProductComposer::class,
        Composers\ThankYouComposer::class,

    ];
}
