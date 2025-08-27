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
 * Provincias desde Base de Datos para el checkout (dependientes de Región/Departamento).
 * - Crea tabla wp_br_provinces en la primera carga.
 * - Inserta semillas (San Martín, Lima) para prueba.
 * - AJAX para traer provincias por región.
 * - Campo billing_province en checkout (select), validación, guardado, emails.
 */

// =============== 0) CREAR TABLA + SEMILLAS (una sola vez) ===============
add_action('after_setup_theme', function () {
    global $wpdb;
    $opt = 'br_provinces_schema_v1';
    if (get_option($opt)) return; // ya instalado

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table = $wpdb->prefix . 'br_provinces';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        country_code VARCHAR(2) NOT NULL DEFAULT 'PE',
        state_name   VARCHAR(191) NOT NULL,
        state_slug   VARCHAR(191) NOT NULL,
        province_name VARCHAR(191) NOT NULL,
        province_slug VARCHAR(191) NOT NULL,
        sort INT NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (id),
        KEY state_slug (state_slug),
        KEY province_slug (province_slug)
    ) $charset_collate;";

    dbDelta($sql);

    // Semillas mínimas para probar (San Martín, Lima)
    $rows = [
        // San Martín
        ['PE', 'San Martín', 'san-martin', 'Moyobamba'],
        ['PE', 'San Martín', 'san-martin', 'Rioja'],
        ['PE', 'San Martín', 'san-martin', 'Lamas'],
        ['PE', 'San Martín', 'san-martin', 'San Martín'],
        ['PE', 'San Martín', 'san-martin', 'Picota'],
        ['PE', 'San Martín', 'san-martin', 'El Dorado'],
        ['PE', 'San Martín', 'san-martin', 'Huallaga'],
        ['PE', 'San Martín', 'san-martin', 'Bellavista'],
        ['PE', 'San Martín', 'san-martin', 'Mariscal Cáceres'],
        ['PE', 'San Martín', 'san-martin', 'Tocache'],
        // Lima (región)
        ['PE', 'Lima', 'lima', 'Lima'],
        ['PE', 'Lima', 'lima', 'Barranca'],
        ['PE', 'Lima', 'lima', 'Cajatambo'],
        ['PE', 'Lima', 'lima', 'Canta'],
        ['PE', 'Lima', 'lima', 'Cañete'],
        ['PE', 'Lima', 'lima', 'Huaral'],
        ['PE', 'Lima', 'lima', 'Huarochirí'],
        ['PE', 'Lima', 'lima', 'Huaura'],
        ['PE', 'Lima', 'lima', 'Oyón'],
        ['PE', 'Lima', 'lima', 'Yauyos'],
    ];

    foreach ($rows as $r) {
        [$cc, $state_name, $state_slug, $prov_name] = $r;
        $wpdb->insert($table, [
            'country_code'   => $cc,
            'state_name'     => $state_name,
            'state_slug'     => sanitize_title($state_slug),
            'province_name'  => $prov_name,
            'province_slug'  => sanitize_title($prov_name),
            'sort'           => 0,
            'is_active'      => 1,
        ]);
    }

    update_option($opt, time());
});

// Helper: obtener provincias por región (por slug o nombre)
function br_get_provinces_by_state($state_text) {
    global $wpdb;
    $table = $wpdb->prefix . 'br_provinces';
    $state_slug = sanitize_title(remove_accents(wp_strip_all_tags((string) $state_text)));
    $sql = $wpdb->prepare(
        "SELECT province_name FROM $table WHERE country_code = 'PE' AND state_slug = %s AND is_active = 1 ORDER BY sort ASC, province_name ASC",
        $state_slug
    );
    return $wpdb->get_col($sql);
}

// =============== 1) CAMPO billing_province en checkout ===============
add_filter('woocommerce_checkout_fields', function ($fields) {
    $fields['billing']['billing_province'] = [
        'type'        => 'select',
        'label'       => __('Provincia *', 'theme-textdomain'),
        'required'    => true,
        'class'       => ['form-row-wide'],
        'priority'    => 61,
        'options'     => [
            '' => __('Seleccione su provincia', 'theme-textdomain'),
        ],
    ];
    return $fields;
});

// Validación
add_action('woocommerce_after_checkout_validation', function ($data, $errors) {
    if (empty($data['billing_province'])) {
        $errors->add('billing_province', __('Por favor selecciona tu provincia.', 'theme-textdomain'));
    }
}, 10, 2);

// Guardar en pedido
add_action('woocommerce_checkout_create_order', function ($order, $data) {
    if (!empty($data['billing_province'])) {
        $order->update_meta_data('_billing_province', sanitize_text_field($data['billing_province']));
    }
}, 10, 2);

// (Opcional) Mostrar en emails
add_filter('woocommerce_email_order_meta_fields', function ($fields, $sent_to_admin, $order) {
    $prov = $order->get_meta('_billing_province');
    if (!empty($prov)) {
        $fields['billing_province'] = [
            'label' => __('Provincia', 'theme-textdomain'),
            'value' => $prov,
        ];
    }
    return $fields;
}, 10, 3);

// =============== 2) AJAX: devolver provincias por región ===============
add_action('wp_ajax_br_get_provinces', function () {
    $state_text = isset($_POST['state_text']) ? wp_unslash($_POST['state_text']) : '';
    $provs = br_get_provinces_by_state($state_text);
    wp_send_json_success(['provinces' => array_values(array_unique(array_map('wp_strip_all_tags', $provs)))]);
});
add_action('wp_ajax_nopriv_br_get_provinces', function () {
    $state_text = isset($_POST['state_text']) ? wp_unslash($_POST['state_text']) : '';
    $provs = br_get_provinces_by_state($state_text);
    wp_send_json_success(['provinces' => array_values(array_unique(array_map('wp_strip_all_tags', $provs)))]);
});

// =============== 3) JS: poblar provincias según Región (billing_state) ===============
add_action('wp_footer', function () {
    if (!is_checkout() || is_wc_endpoint_url('order-received')) return;

    $ajax_url = esc_url(admin_url('admin-ajax.php'));
    $script = <<<HTML
<script>
(function(){
  if (!document.body.classList.contains('woocommerce-checkout')) return;
  var $ = window.jQuery || window.$; if(!$) return;

  var \$state    = $('#billing_state');     // Región/Departamento (select)
  var \$province = $('#billing_province');  // Provincia (select)
  if (!\$state.length || !\$province.length) return;

  function fillProvinces(stateText) {
    if (!stateText) {
      \$province.empty().append(new Option('Seleccione su provincia', '', true, false)).trigger('change');
      return;
    }
    $.post('{$ajax_url}', { action: 'br_get_provinces', state_text: stateText }, function(resp){
      \$province.empty().append(new Option('Seleccione su provincia', '', true, false));
      if (resp && resp.success && Array.isArray(resp.data.provinces)) {
        resp.data.provinces.forEach(function(p){
          \$province.append(new Option(p, p, false, false));
        });
      }
      // Si el usuario ya tenía seleccionada una provincia y existe, se restaura automáticamente por el navegador.
      \$province.trigger('change');
    });
  }

  function getStateText() {
    var txt = \$state.find('option:selected').text() || \$state.val() || '';
    return txt.trim();
  }

  // Inicial
  fillProvinces(getStateText());

  // Cambio de región
  \$state.on('change', function(){ fillProvinces(getStateText()); });

  // Si checkout se actualiza por otros motivos (p.ej. método de pago), reintentar
  jQuery(document.body).on('updated_checkout', function(){
    if (!\$province.children('option[value!=""]').length) {
      fillProvinces(getStateText());
    }
  });
})();
</script>
HTML;

    echo $script;
}, 20);


















