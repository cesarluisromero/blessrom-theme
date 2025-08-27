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


/**
 * UBIGEO desde BD (Región -> Provincia -> Distrito) para checkout WooCommerce.
 * - Tabla wp_br_ubigeo con filas por distrito (region, provincia, distrito)
 * - AJAX: regiones, provincias, distritos
 * - Campos: billing_state (región), billing_province (provincia), billing_city (distrito) como selects
 * - Validación/guardado y JS para encadenado
 */

/* ========== 0) Crear tabla + semillas mínimas (una vez) ========== */
add_action('after_setup_theme', function () {
    global $wpdb;
    $opt = 'br_ubigeo_schema_v1';
    if (get_option($opt)) return;

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table = $wpdb->prefix . 'br_ubigeo';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        country_code  VARCHAR(2)   NOT NULL DEFAULT 'PE',
        region_name   VARCHAR(191) NOT NULL,
        region_slug   VARCHAR(191) NOT NULL,
        province_name VARCHAR(191) NOT NULL,
        province_slug VARCHAR(191) NOT NULL,
        district_name VARCHAR(191) NOT NULL,
        district_slug VARCHAR(191) NOT NULL,
        ubigeo_code   VARCHAR(10)  DEFAULT NULL,
        sort          INT NOT NULL DEFAULT 0,
        is_active     TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (id),
        KEY region_slug (region_slug),
        KEY province_slug (province_slug),
        KEY district_slug (district_slug)
    ) $charset;";

    dbDelta($sql);

    // Semillas mínimas para probar (San Martín y Lima)
    $rows = [
        // San Martín / Moyobamba
        ['PE','San Martín','san-martin','Moyobamba','moyobamba','Moyobamba','moyobamba','220101'],
        ['PE','San Martín','san-martin','Moyobamba','moyobamba','Calzada','calzada','220102'],
        ['PE','San Martín','san-martin','Moyobamba','moyobamba','Habana','habana','220103'],
        // San Martín / Rioja
        ['PE','San Martín','san-martin','Rioja','rioja','Rioja','rioja','220201'],
        ['PE','San Martín','san-martin','Rioja','rioja','Nueva Cajamarca','nueva-cajamarca','220205'],
        // Lima / Lima
        ['PE','Lima','lima','Lima','lima','Lima','lima','150101'],
        ['PE','Lima','lima','Lima','lima','Ate','ate','150103'],
        ['PE','Lima','lima','Lima','lima','Miraflores','miraflores','150122'],
    ];
    foreach ($rows as $r) {
        [$cc,$rname,$rslug,$pname,$pslug,$dname,$dslug,$code] = $r;
        $wpdb->insert($table, [
            'country_code'  => $cc,
            'region_name'   => $rname,
            'region_slug'   => sanitize_title($rslug),
            'province_name' => $pname,
            'province_slug' => sanitize_title($pslug),
            'district_name' => $dname,
            'district_slug' => sanitize_title($dslug),
            'ubigeo_code'   => $code,
            'sort'          => 0,
            'is_active'     => 1,
        ]);
    }

    update_option($opt, time());
});

/* Helpers */
function br_norm_slug($s) {
    $s = remove_accents(strtolower(trim((string)$s)));
    return sanitize_title($s);
}
function br_db_table(){ global $wpdb; return $wpdb->prefix.'br_ubigeo'; }

/* ========== 1) AJAX: regiones, provincias, distritos ========== */
add_action('wp_ajax_br_regions', function () {
    global $wpdb;
    $t = br_db_table();
    $rows = $wpdb->get_col("SELECT DISTINCT region_name FROM $t WHERE is_active=1 AND country_code='PE' ORDER BY region_name ASC");
    wp_send_json_success(['regions'=> $rows ?: []]);
});
add_action('wp_ajax_nopriv_br_regions', function(){ do_action('wp_ajax_br_regions'); });

add_action('wp_ajax_br_provinces', function () {
    global $wpdb;
    $t = br_db_table();
    $region = isset($_POST['region']) ? wp_unslash($_POST['region']) : '';
    $rslug  = br_norm_slug($region);
    $sql = $wpdb->prepare("SELECT DISTINCT province_name FROM $t WHERE is_active=1 AND country_code='PE' AND region_slug=%s ORDER BY province_name ASC",$rslug);
    $rows = $wpdb->get_col($sql);
    wp_send_json_success(['provinces'=> $rows ?: []]);
});
add_action('wp_ajax_nopriv_br_provinces', function(){ do_action('wp_ajax_br_provinces'); });

add_action('wp_ajax_br_districts', function () {
    global $wpdb;
    $t = br_db_table();
    $region   = isset($_POST['region'])   ? wp_unslash($_POST['region'])   : '';
    $province = isset($_POST['province']) ? wp_unslash($_POST['province']) : '';
    $rslug = br_norm_slug($region);
    $pslug = br_norm_slug($province);
    $sql = $wpdb->prepare("SELECT DISTINCT district_name FROM $t WHERE is_active=1 AND country_code='PE' AND region_slug=%s AND province_slug=%s ORDER BY district_name ASC",$rslug,$pslug);
    $rows = $wpdb->get_col($sql);
    wp_send_json_success(['districts'=> $rows ?: []]);
});
add_action('wp_ajax_nopriv_br_districts', function(){ do_action('wp_ajax_br_districts'); });

/* ========== 2) Campos checkout: convertir a SELECTs dependientes ========== */
add_filter('woocommerce_checkout_fields', function ($fields) {
    // REGION / Departamento
    $fields['billing']['billing_state']['type']     = 'select';
    $fields['billing']['billing_state']['label']    = __('Región / Departamento *','theme');
    $fields['billing']['billing_state']['required'] = true;
    $fields['billing']['billing_state']['options']  = ['' => __('Seleccione su región','theme')];
    $fields['billing']['billing_state']['priority'] = 60;

    // PROVINCIA (nuevo)
    $fields['billing']['billing_province'] = [
        'type'        => 'select',
        'label'       => __('Provincia *','theme'),
        'required'    => true,
        'class'       => ['form-row-wide'],
        'priority'    => 61,
        'options'     => ['' => __('Seleccione su provincia','theme')],
    ];

    // DISTRITO: usar billing_city como select (Woo lo guarda de serie)
    $fields['billing']['billing_city']['type']     = 'select';
    $fields['billing']['billing_city']['label']    = __('Distrito *','theme');
    $fields['billing']['billing_city']['required'] = true;
    $fields['billing']['billing_city']['options']  = ['' => __('Seleccione su distrito','theme')];
    $fields['billing']['billing_city']['priority'] = 62;

    return $fields;
}, 20);

// Validación: provincia requerida (city ya es requerida)
add_action('woocommerce_after_checkout_validation', function ($data, $errors) {
    if (empty($data['billing_province'])) {
        $errors->add('billing_province', __('Por favor selecciona tu provincia.','theme'));
    }
}, 10, 2);

// Guardar provincia en el pedido
add_action('woocommerce_checkout_create_order', function ($order, $data) {
    if (!empty($data['billing_province'])) {
        $order->update_meta_data('_billing_province', sanitize_text_field($data['billing_province']));
    }
}, 10, 2);

// Mostrar provincia en emails
add_filter('woocommerce_email_order_meta_fields', function ($fields, $sent_to_admin, $order) {
    $prov = $order->get_meta('_billing_province');
    if ($prov) {
        $fields['billing_province'] = ['label'=>__('Provincia','theme'),'value'=>$prov];
    }
    return $fields;
}, 10, 3);

/* ========== 3) JS en footer: poblar selects desde AJAX y encadenar ========== */
add_action('wp_footer', function () {
    if (!is_checkout() || is_wc_endpoint_url('order-received')) return;

    $ajax = esc_url(admin_url('admin-ajax.php'));
    $script = <<<HTML
<script>
(function(){
  if (!document.body.classList.contains('woocommerce-checkout')) return;
  var $ = window.jQuery || window.$; if(!$) return;

  var \$region   = $('#billing_state');
  var \$province = $('#billing_province');
  var \$district = $('#billing_city');

  if (!\$region.length || !\$province.length || !\$district.length) return;

  var ajaxUrl = '{$ajax}';

  function opt(text, selected){ return new Option(text, text, !!selected, !!selected); }
  function resetSelect(\$el, placeholder){
    \$el.empty().append(new Option(placeholder || 'Seleccione', '', true, false));
  }

  function loadRegions(){
    return $.post(ajaxUrl, { action: 'br_regions' }).done(function(resp){
      resetSelect(\$region, 'Seleccione su región');
      if (resp && resp.success && Array.isArray(resp.data.regions)){
        resp.data.regions.forEach(function(r){ \$region.append(opt(r)); });
      }
      // Restaurar valores previos si existen
      var prev = \$region.attr('value') || \$region.data('value') || \$region.val();
      if (prev){ \$region.val(prev); }
    });
  }

  function loadProvinces(regionText){
    resetSelect(\$province, 'Seleccione su provincia');
    resetSelect(\$district, 'Seleccione su distrito'); // limpiar distritos cuando cambie región
    if (!regionText) return $.Deferred().resolve().promise();
    return $.post(ajaxUrl, { action:'br_provinces', region: regionText }).done(function(resp){
      if (resp && resp.success && Array.isArray(resp.data.provinces)){
        resp.data.provinces.forEach(function(p){ \$province.append(opt(p)); });
        var prev = \$province.attr('value') || \$province.data('value') || \$province.val();
        if (prev){ \$province.val(prev); }
      }
    });
  }

  function loadDistricts(regionText, provinceText){
    resetSelect(\$district, 'Seleccione su distrito');
    if (!regionText || !provinceText) return;
    return $.post(ajaxUrl, { action:'br_districts', region: regionText, province: provinceText }).done(function(resp){
      if (resp && resp.success && Array.isArray(resp.data.districts)){
        resp.data.districts.forEach(function(d){ \$district.append(opt(d)); });
        var prev = \$district.attr('value') || \$district.data('value') || \$district.val();
        if (prev){ \$district.val(prev); }
      }
    });
  }

  function updateCheckout(){ $('body').trigger('update_checkout'); }

  // Carga inicial en cadena (intenta restaurar valores si ya existían)
  $.when(loadRegions()).then(function(){
    var rtxt = \$region.find('option:selected').text();
    return loadProvinces(rtxt);
  }).then(function(){
    var rtxt = \$region.find('option:selected').text();
    var ptxt = \$province.find('option:selected').text();
    return loadDistricts(rtxt, ptxt);
  }).then(function(){ updateCheckout(); });

  // Cambios en cascada
  \$region.on('change', function(){
    var rtxt = \$(this).find('option:selected').text();
    $.when(loadProvinces(rtxt)).then(function(){ updateCheckout(); });
  });

  \$province.on('change', function(){
    var rtxt = \$region.find('option:selected').text();
    var ptxt = \$(this).find('option:selected').text();
    $.when(loadDistricts(rtxt, ptxt)).then(function(){ updateCheckout(); });
  });

  \$district.on('change', updateCheckout);
})();
</script>
HTML;
    echo $script;
}, 20);


















