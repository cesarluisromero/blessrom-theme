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


/* ============================================================
 * UBIGEO desde BD: Región -> Provincia -> Distrito (checkout)
 * Requiere tabla {prefix}br_ubigeo con columnas:
 * region_code, province_code, district_code, region_name, province_name, district_name, ubigeo_code, country_code, is_active
 * ============================================================ */

/** Helper: nombre real de la tabla con prefijo */
function br_ubigeo_table() { global $wpdb; return $wpdb->prefix . 'wp_br_ubigeo'; }

/* -------- AJAX: Regiones -------- */
add_action('wp_ajax_br_regions', function () {
  global $wpdb; $t = br_ubigeo_table();
  $rows = $wpdb->get_results("SELECT DISTINCT region_code, region_name FROM $t WHERE country_code='PE' AND is_active=1 ORDER BY region_name ASC", ARRAY_A);
  \wp_send_json_success(['regions' => $rows ?: []]);
});
add_action('wp_ajax_nopriv_br_regions', function () { do_action('wp_ajax_br_regions'); });

/* -------- AJAX: Provincias por región -------- */
add_action('wp_ajax_br_provinces', function () {
  global $wpdb; $t = br_ubigeo_table();
  $rc = isset($_POST['region_code']) ? \sanitize_text_field(\wp_unslash($_POST['region_code'])) : '';
  $rows = $wpdb->get_results($wpdb->prepare(
    "SELECT DISTINCT province_code, province_name FROM $t
     WHERE country_code='PE' AND is_active=1 AND region_code=%s ORDER BY province_name ASC", $rc
  ), ARRAY_A);
  \wp_send_json_success(['provinces' => $rows ?: []]);
});
add_action('wp_ajax_nopriv_br_provinces', function () { do_action('wp_ajax_br_provinces'); });

/* -------- AJAX: Distritos por región + provincia -------- */
add_action('wp_ajax_br_districts', function () {
  global $wpdb; $t = br_ubigeo_table();
  $rc = isset($_POST['region_code']) ? \sanitize_text_field(\wp_unslash($_POST['region_code'])) : '';
  $pc = isset($_POST['province_code']) ? \sanitize_text_field(\wp_unslash($_POST['province_code'])) : '';
  $rows = $wpdb->get_results($wpdb->prepare(
    "SELECT DISTINCT district_code, district_name, ubigeo_code FROM $t
     WHERE country_code='PE' AND is_active=1 AND region_code=%s AND province_code=%s
     ORDER BY district_name ASC", $rc, $pc
  ), ARRAY_A);
  \wp_send_json_success(['districts' => $rows ?: []]);
});
add_action('wp_ajax_nopriv_br_districts', function () { do_action('wp_ajax_br_districts'); });

/* -------- Ajustar campos del checkout a SELECT con placeholder -------- */
add_filter('woocommerce_checkout_fields', function ($fields) {
  // Región/Departamento
  $fields['billing']['billing_state']['type']      = 'select';
  $fields['billing']['billing_state']['label']     = __('Región / Departamento *', 'theme');
  $fields['billing']['billing_state']['required']  = true;
  $fields['billing']['billing_state']['priority']  = 60;
  $fields['billing']['billing_state']['options']   = ['' => __('Seleccione su región', 'theme')];

  // Provincia (nuevo campo)
  $fields['billing']['billing_province'] = [
    'type'       => 'select',
    'label'      => __('Provincia *', 'theme'),
    'required'   => true,
    'class'      => ['form-row-wide'],
    'priority'   => 61,
    'options'    => ['' => __('Seleccione su provincia', 'theme')],
  ];

  // Distrito (usamos billing_city como select)
  $fields['billing']['billing_city']['type']      = 'select';
  $fields['billing']['billing_city']['label']     = __('Distrito *', 'theme');
  $fields['billing']['billing_city']['required']  = true;
  $fields['billing']['billing_city']['priority']  = 62;
  $fields['billing']['billing_city']['options']   = ['' => __('Seleccione su distrito', 'theme')];

  return $fields;
}, 20);

/* -------- Validación: combinación válida en BD -------- */
add_action('woocommerce_after_checkout_validation', function ($data, $errors) {
  $rc = isset($data['billing_state'])    ? \sanitize_text_field($data['billing_state'])    : '';
  $pc = isset($data['billing_province']) ? \sanitize_text_field($data['billing_province']) : '';
  $dc = isset($data['billing_city'])     ? \sanitize_text_field($data['billing_city'])     : '';

  if ($rc === '') $errors->add('billing_state', __('Selecciona tu región.', 'theme'));
  if ($pc === '') $errors->add('billing_province', __('Selecciona tu provincia.', 'theme'));
  if ($dc === '') $errors->add('billing_city', __('Selecciona tu distrito.', 'theme'));

  if ($rc && $pc && $dc) {
    global $wpdb; $t = br_ubigeo_table();
    $ok = $wpdb->get_var($wpdb->prepare(
      "SELECT COUNT(*) FROM $t WHERE country_code='PE' AND is_active=1 AND region_code=%s AND province_code=%s AND district_code=%s",
      $rc, $pc, $dc
    ));
    if (!$ok) $errors->add('billing_city', __('La combinación Región/Provincia/Distrito no es válida.', 'theme'));
  }
}, 10, 2);

/* -------- Guardar nombres legibles + ubigeo en el pedido -------- */
add_action('woocommerce_checkout_create_order', function ($order, $data) {
  $rc = isset($data['billing_state'])    ? \sanitize_text_field($data['billing_state'])    : '';
  $pc = isset($data['billing_province']) ? \sanitize_text_field($data['billing_province']) : '';
  $dc = isset($data['billing_city'])     ? \sanitize_text_field($data['billing_city'])     : '';
  if (!$rc || !$pc || !$dc) return;

  global $wpdb; $t = br_ubigeo_table();
  $row = $wpdb->get_row($wpdb->prepare(
    "SELECT region_name, province_name, district_name, ubigeo_code
     FROM $t WHERE country_code='PE' AND is_active=1
     AND region_code=%s AND province_code=%s AND district_code=%s LIMIT 1",
    $rc, $pc, $dc
  ));
  if ($row) {
    $order->update_meta_data('_billing_state_name',    $row->region_name);
    $order->update_meta_data('_billing_province',      $row->province_name); // mantén compatibilidad
    $order->update_meta_data('_billing_city_name',     $row->district_name);
    $order->update_meta_data('_billing_ubigeo',        $row->ubigeo_code);
  }
}, 10, 2);

/* -------- JS: poblar selects desde AJAX y encadenar -------- */
add_action('wp_footer', function () {
  if (!\is_checkout() || \is_wc_endpoint_url('order-received')) return;
  $ajax = \esc_url(\admin_url('admin-ajax.php'));
  echo <<<HTML
<script>
(function(){
  if (!document.body.classList.contains('woocommerce-checkout')) return;
  var $ = window.jQuery || window.$; if(!$) return;

  var \$region   = $('#billing_state');    // value: region_code (CCDD)
  var \$province = $('#billing_province'); // value: province_code (CCPP)
  var \$district = $('#billing_city');     // value: district_code (CCDI)
  var ajaxUrl = '{$ajax}';

  function reset(\$el, ph){ \$el.empty().append(new Option(ph, '', true, false)); }
  function add(\$el, arr, textKey, valKey){ arr.forEach(function(it){ \$el.append(new Option(it[textKey], it[valKey])); }); }
  function upd(){ $('body').trigger('update_checkout'); }

  function loadRegions(){
    reset(\$region,'Seleccione su región');
    return $.post(ajaxUrl,{action:'br_regions'}).done(function(r){
      if(r && r.success){ add(\$region, r.data.regions, 'region_name', 'region_code'); }
      var prev = \$region.attr('value') || \$region.data('value') || \$region.val();
      if(prev) \$region.val(prev);
    });
  }
  function loadProvinces(rc){
    reset(\$province,'Seleccione su provincia'); reset(\$district,'Seleccione su distrito');
    if(!rc) return $.Deferred().resolve().promise();
    return $.post(ajaxUrl,{action:'br_provinces', region_code: rc}).done(function(r){
      if(r && r.success){ add(\$province, r.data.provinces, 'province_name', 'province_code'); }
      var prev = \$province.attr('value') || \$province.data('value') || \$province.val();
      if(prev) \$province.val(prev);
    });
  }
  function loadDistricts(rc, pc){
    reset(\$district,'Seleccione su distrito');
    if(!rc || !pc) return $.Deferred().resolve().promise();
    return $.post(ajaxUrl,{action:'br_districts', region_code: rc, province_code: pc}).done(function(r){
      if(r && r.success){ add(\$district, r.data.districts, 'district_name', 'district_code'); }
      var prev = \$district.attr('value') || \$district.data('value') || \$district.val();
      if(prev) \$district.val(prev);
    });
  }

  // Carga inicial (respeta valores guardados si existen)
  $.when(loadRegions()).then(function(){
    return loadProvinces(\$region.val());
  }).then(function(){
    return loadDistricts(\$region.val(), \$province.val());
  }).then(upd);

  // Cascada
  \$region.on('change', function(){ $.when(loadProvinces(this.value)).then(upd); });
  \$province.on('change', function(){ $.when(loadDistricts(\$region.val(), this.value)).then(upd); });
  \$district.on('change', upd);
})();
</script>
HTML;
}, 20);


















