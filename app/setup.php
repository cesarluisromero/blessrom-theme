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



// Aplica clases Tailwind a TODOS los campos del checkout (billing, shipping y account).
add_filter('woocommerce_checkout_fields', function ($fields) {

  $style_input = 'w-full rounded-xl border border-slate-300 focus:border-slate-400 focus:ring-0 text-sm py-2.5 px-3 bg-white';
  $style_label = 'block mb-1 text-sm font-medium text-slate-700';
  $style_wrap  = 'form-row mb-4'; // Mantener "form-row" para compatibilidad Woo

  foreach (['billing','shipping','account'] as $group) {
    if (empty($fields[$group])) continue;

    foreach ($fields[$group] as $key => &$field) {
      // Wrapper <p>...</p>
      $field['class'] = isset($field['class']) ? array_unique(array_merge((array)$field['class'], explode(' ', $style_wrap))) : explode(' ', $style_wrap);

      // Label
      $field['label_class'] = isset($field['label_class']) ? array_unique(array_merge((array)$field['label_class'], explode(' ', $style_label))) : explode(' ', $style_label);

      // Inputs (input/select/textarea)
      $inputClasses = $style_input;

      // Ajustes por tipo
      $type = $field['type'] ?? 'text';
      if ($type === 'textarea') {
        $inputClasses .= ' min-h-[110px]';
      }
      if ($type === 'checkbox') {
        // Para checkbox: no uses w-full
        $inputClasses = 'h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-0';
      }
      if ($type === 'select') {
        // Woo usa Select2, el select original queda oculto, pero igual dejamos clases
        $inputClasses .= ' pr-8';
      }

      $field['input_class'] = isset($field['input_class'])
        ? array_unique(array_merge((array)$field['input_class'], explode(' ', $inputClasses)))
        : explode(' ', $inputClasses);
    }
  }

  return $fields;
}, 99);




/**
 * Campo DNI/RUC en facturación
 */
add_filter('woocommerce_checkout_fields', function ($fields) {
  // Define el nuevo campo
  $fields['billing']['billing_document'] = [
    'type'        => 'text',
    'label'       => __('DNI / RUC', 'woocommerce'),
    'placeholder' => __('Ingresa tu DNI (8) o RUC (11)', 'woocommerce'),
    'required'    => true,
    'class'       => ['form-row-wide'], // Woo necesita "form-row-*"
    'priority'    => 82,                // Ubicación relativa en el bloque de billing
    // Atributos del <input>
    'custom_attributes' => [
      'maxlength'   => '11',
      'inputmode'   => 'numeric',
      'autocomplete'=> 'off',
      'pattern'     => '[0-9]{8}|(10|15|17|20)[0-9]{9}', // 8 dígitos (DNI) o 11 empezando por 10/15/17/20 (RUC)
    ],
  ];

  return $fields;
}, 20);

//Validamos que sea DNI (8 dígitos) o RUC (11 empezando por 10/15/17/20):
add_action('woocommerce_after_checkout_validation', function ($data, $errors) {
  $doc = isset($data['billing_document']) ? preg_replace('/\D+/', '', $data['billing_document']) : '';

  if ($doc === '') {
    $errors->add('billing_document_empty', __('El campo DNI/RUC es obligatorio.', 'woocommerce'));
    return;
  }

  $is_dni = (bool) preg_match('/^\d{8}$/', $doc);
  $is_ruc = (bool) preg_match('/^(10|15|17|20)\d{9}$/', $doc);

  if (!$is_dni && !$is_ruc) {
    $errors->add('billing_document_invalid', __('Ingresa un DNI (8 dígitos) o RUC válido (11 dígitos empezando por 10/15/17/20).', 'woocommerce'));
  }
}, 10, 2);

//Guardar en el pedido (order meta)
add_action('woocommerce_checkout_create_order', function ($order, $data) {
  if (!empty($data['billing_document'])) {
    $order->update_meta_data('_billing_document', sanitize_text_field($data['billing_document']));
  }
}, 10, 2);

// Mostrar en el admin (datos de facturación)
add_action('woocommerce_admin_order_data_after_billing_address', function ($order) {
  $doc = $order->get_meta('_billing_document');
  if ($doc) {
    echo '<p><strong>' . esc_html__('DNI/RUC', 'woocommerce') . ':</strong> ' . esc_html($doc) . '</p>';
  }
});

// Mostrar en el email (datos de facturación)
add_filter('woocommerce_email_order_meta_fields', function ($fields, $sent_to_admin, $order) {
  $doc = $order->get_meta('_billing_document');
  if ($doc) {
    $fields['billing_document'] = [
      'label' => __('DNI/RUC', 'woocommerce'),
      'value' => $doc,
    ];
  }
  return $fields;
}, 10, 3);

// Guardar en el perfil del cliente (user meta)
// Cuando Woo actualiza los datos del cliente en checkout
add_action('woocommerce_checkout_update_customer', function ($customer, $data) {
  if (!empty($data['billing_document'])) {
    $customer->update_meta_data('billing_document', sanitize_text_field($data['billing_document']));
    $customer->save();
  }
}, 10, 2);


// Quitar "Nombre de empresa" y "Dirección 2" del checkout (FACTURACIÓN)
add_filter('woocommerce_checkout_fields', function ($fields) {

  // elimina por completo (no se renderizan ni se validan)
  unset($fields['billing']['billing_company']);
  unset($fields['billing']['billing_address_2']);

  return $fields;
}, 20);

// Quitar también en el formulario de edición de dirección de la cuenta
add_filter('woocommerce_billing_fields', function ($fields) {

  unset($fields['billing_company']);
  unset($fields['billing_address_2']);
  // unset($fields['billing_address_2']);

  return $fields;
}, 20);

add_filter('woocommerce_checkout_fields', function ($fields) {
  unset($fields['shipping']['shipping_company']);
  unset($fields['shipping']['shipping_address_2']);
  // unset($fields['shipping']['shipping_address_2']);
  return $fields;
}, 20);

add_filter('woocommerce_shipping_fields', function ($fields) {
  unset($fields['shipping_company']);
  unset($fields['shipping_address_2']);
  // unset($fields['shipping_address_2']);
  return $fields;
}, 20);

add_filter('woocommerce_checkout_fields', function ($fields) {
  if (isset($fields['billing']['billing_city'])) {
    $fields['billing']['billing_city']['label']       = __('Distrito / Ciudad', 'woocommerce');
    $fields['billing']['billing_city']['placeholder'] = __('Ej. Tarapoto, Morales…', 'woocommerce');
    // Si guardas código:
    // $fields['billing']['billing_city']['placeholder'] = __('Código de distrito (p. ej. 06)', 'woocommerce');
  }
  if (isset($fields['shipping']['shipping_city'])) {
    $fields['shipping']['shipping_city']['label']       = __('Distrito / Ciudad', 'woocommerce');
    $fields['shipping']['shipping_city']['placeholder'] = __('Ej. Tarapoto, Morales…', 'woocommerce');
  }
  return $fields;
}, 999);

add_filter('gettext', function ($translated, $text, $domain) {
  if ($domain === 'woocommerce') {
    if ($text === 'Town / City' || $text === 'Población') {
      return 'Distrito / Ciudad';
    }
  }
  return $translated;
}, 10, 3);


// app/setup.php
add_filter('woocommerce_checkout_fields', function ($fields) {

  $style_input = 'w-full rounded-xl border border-slate-300 focus:border-slate-400 focus:ring-0 text-sm py-2.5 px-3 bg-white';
  $style_label = 'block mb-1 text-sm font-medium text-slate-700';
  $style_wrap  = 'form-row mb-4';

  foreach (['billing','shipping','account','order'] as $group) { // <— añade 'order'
    if (empty($fields[$group])) continue;

    foreach ($fields[$group] as $key => &$field) {
      // wrapper <p>
      $field['class'] = array_values(array_unique(array_merge($field['class'] ?? [], explode(' ', $style_wrap))));
      // label
      $field['label_class'] = array_values(array_unique(array_merge($field['label_class'] ?? [], explode(' ', $style_label))));
      // input / textarea
      $inputClasses = $style_input;
      $type = $field['type'] ?? 'text';
      if ($type === 'textarea') $inputClasses .= ' min-h-[120px]'; // más alto para notas
      $field['input_class'] = array_values(array_unique(array_merge($field['input_class'] ?? [], explode(' ', $inputClasses))));
    }
  }

  return $fields;
}, 99); // prioridad alta para que afecte también a campos agregados después


// app/setup.php
add_filter('woocommerce_checkout_fields', function ($fields) {
  if (isset($fields['order']['order_comments'])) {
    $fields['order']['order_comments']['label']       = __('Notas del pedido', 'woocommerce');
    $fields['order']['order_comments']['placeholder'] = __('Ej.: referencia de entrega, horario preferido, indicaciones para el repartidor…', 'woocommerce');
    $fields['order']['order_comments']['priority']    = 30; // súbelo o bájalo según dónde lo quieras
    $fields['order']['order_comments']['custom_attributes'] = array_merge(
      $fields['order']['order_comments']['custom_attributes'] ?? [],
      [
        'rows'       => '4',
        'maxlength'  => '250',
        // (Opcional) contador con Alpine integrado:
        // 'x-data'     => '{len: $el.value.length, max: 250}',
        // 'x-on:input' => 'len = $el.value.length',
      ]
    );
  }
  return $fields;
}, 80);


add_filter('gettext', function($translated, $text, $domain){
  if ($domain === 'woocommerce' && ($text === 'Additional information' || $text === 'Información adicional')) {
    return __('Notas del pedido', 'woocommerce');
  }
  return $translated;
}, 10, 3);
