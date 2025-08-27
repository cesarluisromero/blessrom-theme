<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

//require_once get_theme_file_path('app/cart-actions.php');

/**
 * Editor styles
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/styles/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Editor scripts
 *
 * @return void
 */
add_filter('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    $dependencies = json_decode(Vite::content('editor.deps.json'));

    foreach ($dependencies as $dependency) {
        if (! wp_script_is($dependency)) {
            wp_enqueue_script($dependency);
        }
    }

    echo Vite::withEntryPoints([
        'resources/scripts/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    
    remove_theme_support('block-templates');
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);
    remove_theme_support('core-block-patterns');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');  
    add_theme_support('responsive-embeds');
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('woocommerce');   

}, 20);

//carga la página del producto
add_filter('template_include', function ($template) {
        if (is_singular('product')) {
            $blade_template = locate_template('resources/views/woocommerce/single-product.blade.php');
            if ($blade_template) {
                echo \Roots\view('woocommerce.single-product')->render();
                exit; // Evita que cargue otras plantillas
            }
        }
        return $template;
}, 99);

//redirige al checkout
add_filter('template_include', function ($template) {
    if (is_checkout() && !is_order_received_page()) {
        $blade_template = locate_template('resources/views/woocommerce/checkout/form-checkout.blade.php');
        if ($blade_template) {
            echo \Roots\view('woocommerce.checkout.form-checkout')->render();
            exit; // Detiene el flujo de carga de otras plantillas
        }
    }
    return $template;
}, 99);

//carga la página del producto
add_filter('template_include', function ($template) {
        if (is_singular('product')) {
            $blade_template = locate_template('resources/views/woocommerce/single-product.blade.php');
            if ($blade_template) {
                echo \Roots\view('woocommerce.single-product')->render();
                exit; // Evita que cargue otras plantillas
            }
        }
        return $template;
}, 99);

//redirige al checkout
add_filter('template_include', function ($template) {
    if (is_checkout() && !is_order_received_page()) {
        $blade_template = locate_template('resources/views/woocommerce/checkout/form-checkout.blade.php');
        if ($blade_template) {
            echo \Roots\view('woocommerce.checkout.form-checkout')->render();
            exit; // Detiene el flujo de carga de otras plantillas
        }
    }
    return $template;
}, 99);

//redirige a la página de agradecimiento después de comprar
add_filter('template_include', function ($template) {
    if (is_order_received_page()) {
        $order_id = absint(get_query_var('order-received'));
        $order = wc_get_order($order_id);

        if ($order) {
            echo \Roots\view('woocommerce.thankyou', [
                'order' => $order,
            ])->render();
            exit;
        }
    }

    return $template;
}, 99);


/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});


require_once get_theme_file_path('app/ajax.php');

add_filter('template_include', function ($template) {
    if (is_woocommerce()) {
        $theme_template = locate_template('woocommerce.blade.php');
        if ($theme_template) {
            return $theme_template;
        }
    }

    return $template;
}, 99);

// Formulario "Añadir nuevo Color"
add_action('pa_color_add_form_fields', function () {
  ?>
  <div class="form-field term-color-wrap">
    <label for="color_hex">Color (hex)</label>
    <input type="text" name="color_hex" id="color_hex" value="#cccccc" class="color-picker" data-default-color="#cccccc" />
    <p class="description">Elige el color para este término (por ej. #165DFF).</p>
  </div>
  <?php
});

// Formulario "Editar Color"
add_action('pa_color_edit_form_fields', function ($term) {
  $value = get_term_meta($term->term_id, 'color_hex', true) ?: '#cccccc';
  ?>
  <tr class="form-field term-color-wrap">
    <th scope="row"><label for="color_hex">Color (hex)</label></th>
    <td>
      <input type="text" name="color_hex" id="color_hex" value="<?php echo esc_attr($value); ?>" class="color-picker" data-default-color="#cccccc" />
      <p class="description">Elige el color para este término (por ej. #165DFF).</p>
    </td>
  </tr>
  <?php
});


// Guardar meta
// Guardar/actualizar el meta color_hex en pa_color
function blessrom_save_pa_color_meta($term_id, $tt_id) {
  if (!isset($_POST['color_hex'])) return;

  $hex = $_POST['color_hex'];

  // Sanear con fallback por si algo falla
  if (function_exists('sanitize_hex_color')) {
    $hex = sanitize_hex_color($hex);
  }
  if (!$hex) { // si no es válido, usa un gris por defecto
    $hex = '#cccccc';
  }

  update_term_meta($term_id, 'color_hex', $hex);
}
add_action('created_pa_color', 'App\\blessrom_save_pa_color_meta', 10, 2);
add_action('edited_pa_color',  'App\\blessrom_save_pa_color_meta', 10, 2);



// Encolar color picker solo en pantallas de taxonomías
// Cargar wp-color-picker solo en la pantalla de términos de pa_color
add_action('admin_enqueue_scripts', function () {
  $screen = get_current_screen();
  if (!$screen) return;

  // Pantallas donde se gestionan términos
  if (in_array($screen->base, ['edit-tags', 'term'], true) && $screen->taxonomy === 'pa_color') {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');

    // Inicializar el picker y re-inicializar tras añadir por AJAX (action=add-tag)
    $init = <<<JS
    jQuery(function($){
      function initPickers(){
        $('.color-picker').each(function(){
          if (!$(this).hasClass('wp-color-picker')) {
            $(this).wpColorPicker();
          }
        });
      }
      initPickers();
      $(document).ajaxComplete(function(event, xhr, settings){
        if (settings && typeof settings.data === 'string' && settings.data.indexOf('action=add-tag') !== -1) {
          initPickers();
        }
      });
    });
    JS;
    wp_add_inline_script('wp-color-picker', $init);
  }
});

// functions.php o app/setup.php
add_action('wp', function () {
  if (!function_exists('wc_get_notices')) return;
  if (is_checkout() && !is_order_received_page()) {
    $notices = wc_get_notices();
    // Elimina SOLO los mensajes de éxito (los de “añadido/eliminado del carrito”)
    if (!empty($notices['success'])) {
      unset($notices['success']);
      wc_set_notices($notices);
    }
  }
}, 1);

// Elimina el formulario de login del checkout
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );


add_filter('woocommerce_checkout_fields', function ($fields) {
    $fields['billing']['billing_document'] = [
        'type'        => 'text',
        'label'       => 'DNI / RUC',
        'required'    => true,
        'priority'    => 65,                // orden entre tus campos
        'class'       => ['form-row-wide'],
        'autocomplete'=> 'off',
        'input_class' => ['h-12','text-base'], // tailwind utilidades si usas
        'custom_attributes' => [
            'inputmode'  => 'numeric',
            'pattern'    => '[0-9]*',
            'maxlength'  => '11',
            'placeholder'=> '8 dígitos (DNI) o 11 (RUC)',
        ],
    ];
    return $fields;
});

add_filter('woocommerce_billing_fields', function ($fields) {
    $fields['billing_document'] = $fields['billing_document'] ?? [
        'type'        => 'text',
        'label'       => 'DNI / RUC',
        'required'    => true,
        'priority'    => 65,
    ];
    return $fields;
});


add_filter('woocommerce_checkout_fields', function ($fields) {
  if (isset($fields['billing']['billing_document'])) {
    $fields['billing']['billing_document']['label']       = 'DNI / RUC';
    $fields['billing']['billing_document']['class']       = ['mb-4'];
    $fields['billing']['billing_document']['label_class'] = ['block text-sm font-medium text-gray-700 mb-1'];
    $fields['billing']['billing_document']['input_class'] = ['form-input w-full rounded-md border-gray-300 shadow-sm h-12 text-base focus:ring-blue-500 focus:border-blue-500'];
  }
  return $fields;
});

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












