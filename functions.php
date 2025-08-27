<?php

use Roots\Acorn\Application;

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

Application::configure()
    ->withProviders([
        App\Providers\ThemeServiceProvider::class,
    ])
    ->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

collect(['setup', 'filters'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });

//remueve stilos por defecto de woocommerce
add_filter('woocommerce_enqueue_styles', '__return_empty_array');
//remueve migas de pan
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

<?php
/** ================================================================
 * A) CARRITO: no calcular ni mostrar envíos
 * ================================================================ */
add_filter('woocommerce_cart_ready_to_calc_shipping', function($show){
    if (is_cart()) return false;
    return $show;
}, 10, 1);

add_filter('woocommerce_cart_needs_shipping', function($needs){
    if (is_cart()) return false;
    return $needs;
}, 10, 1);

add_filter('woocommerce_cart_totals_shipping_html', function($html){
    if (is_cart()) return '';
    return $html;
}, 10, 1);

add_filter('woocommerce_no_shipping_available_html', function($html){
    if (is_cart()) return '';
    return $html;
}, 10, 1);

add_filter('woocommerce_cart_no_shipping_available_html', function($html){
    if (is_cart()) return '';
    return $html;
}, 10, 1);


/** ================================================================
 * B) CHECKOUT: ocultar envío hasta que haya región + distrito
 *    y aplicar envío gratis por distrito (texto) si corresponde
 * ================================================================ */
if (!function_exists('br_norm')) {
    function br_norm($s) {
        $s = remove_accents(strtolower(trim((string)$s)));
        return preg_replace('/\s+/', ' ', $s);
    }
}

add_filter('woocommerce_package_rates', function($rates, $package){
    if (!is_checkout()) return $rates;

    $dest  = $package['destination'] ?? [];
    $state = $dest['state'] ?? (WC()->customer ? WC()->customer->get_shipping_state() : '');
    $city  = $dest['city']  ?? (WC()->customer ? WC()->customer->get_shipping_city()  : '');

    // 1) Si falta región o falta distrito, no muestres ningún método
    if (empty($state) || empty($city)) {
        return []; // oculta hasta que el usuario complete ambos
    }

    // 2) Envío gratis por CIUDAD/DISTRITO (texto)
    $free_cities = array_map('br_norm', [
        'Tarapoto',
        // 'Morales',
        // 'La Banda de Shilcayo',
        // agrega más si quieres
    ]);

    $city_norm = br_norm($city);

    if (in_array($city_norm, $free_cities, true)) {
        // Si ya existe "free_shipping", deja solo ese + "local_pickup"
        $has_free = false;
        foreach ($rates as $rate) {
            if (!empty($rate->method_id) && $rate->method_id === 'free_shipping') {
                $has_free = true; break;
            }
        }

        if ($has_free) {
            foreach ($rates as $rate_id => $rate) {
                $m = $rate->method_id ?? '';
                if ($m === 'free_shipping' || $m === 'local_pickup') continue;
                unset($rates[$rate_id]);
            }
            return $rates;
        }

        // Si no existe "free_shipping", convierte las tarifas a costo 0 (excepto recogida local)
        foreach ($rates as $rate_id => $rate) {
            $m = $rate->method_id ?? '';
            if ($m === 'local_pickup') continue;
            $rates[$rate_id]->cost = 0;
            if (method_exists($rates[$rate_id], 'set_taxes')) {
                $rates[$rate_id]->set_taxes([]);
            } else {
                $rates[$rate_id]->taxes = [];
            }
            $rates[$rate_id]->label = __('Envío gratis (distrito elegible)', 'theme-textdomain');
        }
    }

    return $rates;
}, 20, 2);

// Ocultar totalmente la fila de envío si aún falta región o distrito
add_filter('woocommerce_cart_totals_shipping_html', function($html){
    if (is_checkout()) {
        $state = WC()->customer ? WC()->customer->get_shipping_state() : '';
        $city  = WC()->customer ? WC()->customer->get_shipping_city()  : '';
        if (empty($state) || empty($city)) return '';
    }
    return $html;
}, 20, 1);

// Ocultar mensajes "no hay envío disponible" hasta que haya región+distrito
add_filter('woocommerce_no_shipping_available_html', function($html){
    if (is_checkout()) {
        $state = WC()->customer ? WC()->customer->get_shipping_state() : '';
        $city  = WC()->customer ? WC()->customer->get_shipping_city()  : '';
        if (empty($state) || empty($city)) return '';
    }
    return $html;
}, 20, 1);

add_filter('woocommerce_cart_no_shipping_available_html', function($html){
    if (is_checkout()) {
        $state = WC()->customer ? WC()->customer->get_shipping_state() : '';
        $city  = WC()->customer ? WC()->customer->get_shipping_city()  : '';
        if (empty($state) || empty($city)) return '';
    }
    return $html;
}, 20, 1);


/** ================================================================
 * C) JS: refrescar el checkout al cambiar REGIÓN y mientras se escribe DISTRITO
 *    - change en state
 *    - keyup con debounce en city (para inputs de texto)
 * ================================================================ */
add_action('wp_footer', function () {
    if (!is_checkout()) return; ?>
<script>
(function(){
  function triggerUpdate(){
    if (typeof jQuery !== 'undefined') {
      jQuery('body').trigger('update_checkout');
    }
  }
  // change: región (state)
  document.addEventListener('change', function(e){
    if (!e.target) return;
    var el=e.target;
    var ids=['shipping_state','billing_state'];
    var names=['shipping_state','billing_state','shipping[state]','billing[state]'];
    if (ids.includes(el.id) || names.includes(el.name)) {
      triggerUpdate();
    }
  });

  // keyup con debounce: distrito/ciudad (city)
  var debounceTimer=null;
  function onCityInput(e){
    if (!e.target) return;
    var el=e.target;
    var ids=['shipping_city','billing_city'];
    var names=['shipping_city','billing_city','shipping[city]','billing[city]'];
    if (ids.includes(el.id) || names.includes(el.name)) {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(triggerUpdate, 400);
    }
  }
  document.addEventListener('keyup', onCityInput);
  document.addEventListener('change', onCityInput); // por si algunos themes usan change
})();
</script>
<?php });
