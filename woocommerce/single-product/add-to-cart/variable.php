<?php
defined('ABSPATH') || exit;

global $product;

// Filtrar variaciones con disponibilidad válida (no vacía y stock > 0)
$filtered_variations = array_filter($available_variations, function ($variation) {
    if (empty($variation['availability_html'])) return false;

    if (preg_match('/(\d+)/', $variation['availability_html'], $matches)) {
        return intval($matches[1]) > 0;
    }

    return false;
});

$filtered_variations_json = wp_json_encode(array_values($filtered_variations));
$variations_attr = function_exists('wc_esc_json')
    ? wc_esc_json($filtered_variations_json)
    : _wp_specialchars($filtered_variations_json, ENT_QUOTES, 'UTF-8', true);

// Calcular cantidades actuales en el carrito por ID de variación
$cart_quantities = [];
if (WC()->cart) { // Asegurarse de que el carrito esté inicializado
    foreach (WC()->cart->get_cart() as $item) {
        $variation_id = $item['variation_id'];
        $cart_quantities[$variation_id] = ($cart_quantities[$variation_id] ?? 0) + $item['quantity'];
    }
}

$attributes = $product->get_variation_attributes();

$alpine_data = [
    'maxQty' => 10,
    'quantity' => 1,
    'availableVariations' => json_decode($filtered_variations_json),
    'errorMessage' => '',
    'cartQuantities' => $cart_quantities,
    // Inicializar selected_pa_talla y selected_pa_color con valores vacíos
    'selected_pa_talla' => '',
    'selected_pa_color' => ''
];

// Si hay un solo atributo, seleccionarlo por defecto
foreach ($attributes as $attribute_name => $options) {
    $slug = sanitize_title($attribute_name);
    // Verificar si el atributo es 'pa_talla' o 'pa_color'
    if ($slug === 'pa_talla' || $slug === 'pa_color') {
        $alpine_data["selected_{$slug}"] = count($options) === 1 ? esc_attr($options[0]) : '';
    }
}

// Mapa dinámico slug => hex a partir de términos usados en este producto
$color_terms = wc_get_product_terms($product->get_id(), 'pa_color', ['fields' => 'all']);
$color_map = [];

foreach ($color_terms as $t) {
    // 1) Leer del term meta estándar
    $hex = get_term_meta($t->term_id, 'color_hex', true);

    // 2) (Opcional) Fallback si usas ACF:
    if (!$hex && function_exists('get_field')) {
        $hex = get_field('color_hex', 'pa_color_' . $t->term_id);
    }

    $color_map[$t->slug] = $hex ?: '#cccccc';
}
?>
<?php
// === Medidas por talla (desde variaciones) ===
// Cambia estos nombres si tus campos se llaman distinto en Woo/ACF:
$KEY_ANCHO = 'ancho_cm';
$KEY_ALTO  = 'alto_cm';
$KEY_LARGO = 'largo_cm';

$measures_by_talla = [];   // slug_talla => ['ancho','alto','largo']
$talla_display     = [];   // slug_talla => 'Nombre legible'
$talla_order = wc_get_product_terms($product->get_id(), 'pa_talla', ['fields' => 'slugs']);
$store_unit = get_option('woocommerce_dimension_unit', 'cm');

function br_dim_to_cm($val, $store_unit){
    if ($val === '' || $val === null) return '';
    // wc_get_dimension convierte entre unidades
    return wc_get_dimension( (float) $val, 'cm', $store_unit );
}

foreach ($available_variations as $v) {
    $talla = $v['attributes']['attribute_pa_talla'] ?? '';
    if (!$talla) continue;

    $vid = (int)($v['variation_id'] ?? 0);
    $var_obj = $vid ? wc_get_product($vid) : null;

    // Lee dimensiones nativas de Woo
    $length = $var_obj ? $var_obj->get_length() : ''; // LARGO
    $width  = $var_obj ? $var_obj->get_width()  : ''; // ANCHO
    $height = $var_obj ? $var_obj->get_height() : ''; // ALTO

    // Convierte a cm si hace falta
    $row = [
        'ancho' => br_dim_to_cm($width,  $store_unit),
        'alto'  => br_dim_to_cm($height, $store_unit),
        'largo' => br_dim_to_cm($length, $store_unit),
    ];

    // Fallback ACF si lo usas
    if (function_exists('get_field')) {
        if ($row['ancho'] === '' || $row['ancho'] === null) $row['ancho'] = get_field($KEY_ANCHO, $vid);
        if ($row['alto']  === '' || $row['alto']  === null) $row['alto']  = get_field($KEY_ALTO,  $vid);
        if ($row['largo'] === '' || $row['largo'] === null) $row['largo'] = get_field($KEY_LARGO, $vid);
    }

    
    // Guarda/Completa por talla
    if (!isset($measures_by_talla[$talla])) {
        $measures_by_talla[$talla] = $row;
    } else {
        foreach ($row as $k => $val) {
            if (($measures_by_talla[$talla][$k] ?? '') === '' && $val !== '') {
                $measures_by_talla[$talla][$k] = $val;
            }
        }
    }

    // Nombre legible de la talla
    if (!isset($talla_display[$talla])) {
        $term = get_term_by('slug', $talla, 'pa_talla');
        $talla_display[$talla] = $term ? $term->name : $talla;
    }
}
?>





<form
    x-ref="form"
    x-data="alpineCart()"
    x-init="setTimeout(() => updateMaxQty(), 50)"
    class="variations_form cart"
    action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
    method="post"
    enctype="multipart/form-data"
    data-product_id="<?php echo absint($product->get_id()); ?>"
    data-product_variations='<?= $variations_attr ?>'
    data-cart_quantities='<?= json_encode($cart_quantities, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>'
>
    <?php wp_nonce_field( 'woocommerce-add-to-cart', '_wpnonce' ); ?>
    <input type="hidden" name="_wp_http_referer" value="<?php echo esc_attr( wp_unslash( $_SERVER['REQUEST_URI'] ) ); ?>" />

    <div class="mb-4">
        <label class="block text-sm font-semibold mb-2 text-gray-800">
            <?php echo wc_attribute_label('pa_talla'); ?>
        </label>
        <div class="flex flex-wrap gap-2">
            <?php
            $terms_talla = wc_get_product_terms($product->get_id(), 'pa_talla', ['fields' => 'all']);
            foreach ($terms_talla as $term) :
                $label = $term->name;
                $slug_value = $term->slug;
            ?>
                <button
                    type="button"
                    @click="selected_pa_talla = '<?= esc_js($slug_value) ?>'; selected_pa_color = ''; errorMessage = ''; updateMaxQty()"
                    :class="selected_pa_talla === '<?= esc_js($slug_value) ?>'
                        ? 'ring-2 ring-blue-500 border-blue-500 text-white bg-blue-600'
                        : 'bg-white text-gray-800 border-gray-300'"
                    class="transition text-sm border rounded px-3 py-1 duration-150 ease-in-out"
                >
                    <?= esc_html($label) ?>
                </button>
            <?php endforeach; ?>
        </div>
        <input type="hidden" name="attribute_pa_talla" :value="selected_pa_talla" required>
    </div>

    <div class="mb-4" x-show="selected_pa_talla" x-transition>
        <label class="block text-sm font-semibold mb-2 text-gray-800">
            <?php echo wc_attribute_label('pa_color'); ?>
        </label>
        <div class="flex flex-wrap gap-2"
            x-data='{ "colorMap": <?= wp_json_encode($color_map, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?> }'
            x-init="console.log('colorMap:', colorMap)">
            <template x-for="color in validColors()" :key="color">
                <button
                type="button"
                @click=" 
                    selected_pa_color = color;
                    quantity = 1;
                    errorMessage = '';
                    updateMaxQty();
                    if ($store.product?.colorImages?.[color]) {
                    $store.product.currentImage = $store.product.colorImages[color];
                    if ($store.product.slideToImage) $store.product.slideToImage($store.product.colorImages[color]);
                    }
                "
                class="transition text-sm border rounded duration-150 ease-in-out w-8 h-8 rounded-full"
                :class="selected_pa_color === color ? 'ring-2 ring-blue-500 border-blue-500' : 'border-gray-300'"
                :style="`background-color: ${colorMap[color] ?? '#ccc'}`"
                :title="`${color} → ${colorMap[color] ?? '#ccc'}`"
                ></button>
            </template>
        </div>
        <input type="hidden" name="attribute_pa_color" :value="selected_pa_color" required>
    </div>

    <div class="single_variation_wrap mt-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div x-show="maxQty > 0" x-transition>
                <label for="quantity" class="sr-only">Cantidad</label>
                <input type="hidden" name="maxQty" value="0" x-ref="maxQty">
                <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>">

                <input type="hidden" name="variation_id" :value="selectedVariationId()" x-ref="variationId">
                <input
                    type="number"
                    id="quantity"
                    class="w-20 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    name="quantity"
                    x-model="quantity"
                    :max="maxQty"
                    min="1"
                    onkeydown="return false;"     
                >
            </div>
            <div class="w-full">
                
                    <template x-if="!selected_pa_talla || !selected_pa_color">
                        <button
                            type="button"
                            @click="validateBeforeSubmit($refs.form)"
                            :disabled="maxQty === 0"
                            :class="maxQty === 0 ? 'opacity-50 cursor-not-allowed bg-gray-400 hover:bg-gray-400' : 'bg-yellow-400 hover:bg-yellow-500'"
                            class="inline-flex items-center justify-center px-6 py-3 text-white rounded text-sm font-medium shadow transition duration-300 ease-in-out w-full sm:w-auto"
                        >
                            <img src="<?php echo get_template_directory_uri(); ?>/resources/images/cart-icon.png" alt="Carrito" class="w-5 h-5 mr-2">
                            Agregar al carrito
                        </button>
                    </template>

                    <template x-if="selected_pa_talla && selected_pa_color">
                        <button
                            type="submit"
                            @click.prevent="addToCartAjax($refs.form)"
                            :disabled="maxQty === 0"
                            :class="maxQty === 0 ? 'opacity-50 cursor-not-allowed bg-gray-400 hover:bg-gray-400' : 'bg-yellow-400 hover:bg-yellow-500'"
                            class="inline-flex items-center justify-center px-6 py-3 text-white rounded text-sm font-medium shadow transition duration-300 ease-in-out w-full sm:w-auto">
                            <img src="<?php echo get_template_directory_uri(); ?>/resources/images/cart-icon.png" alt="Carrito" class="w-5 h-5 mr-2">
                            Agregar al carrito
                        </button>

                    </template>
            </div>
        </div>

        <div x-show="errorMessage" class="text-sm mt-4 space-y-3" x-transition>
            <p class="text-red-600 font-semibold" x-text="errorMessage"></p>
            <template x-if="errorMessage === 'Ya tienes en el carrito toda la cantidad disponible de este producto.'">
                <div class="flex gap-2 flex-col sm:flex-row">
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>"
                       class="bg-gray-800 text-white text-center px-4 py-2 rounded hover:bg-gray-700 transition text-sm font-medium w-full sm:w-auto">
                        Ver carrito
                    </a>
                    <a href="<?php echo esc_url(home_url('/tienda/')); ?>"
                       class="bg-yellow-400 text-gray-900 text-center px-4 py-2 rounded hover:brightness-90 transition text-sm font-medium w-full sm:w-auto">
                        Seguir comprando
                    </a>
                </div>
            </template>
        </div>
    </div>
    <?php if (!empty($measures_by_talla)) : ?>
        <div class="mt-6">
            <h3 class="text-base font-semibold mb-2">Tabla de medidas (cm)</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium text-gray-700">Talla</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-700">Ancho</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-700">Alto</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-700">Largo</th>
                        </tr>
                    </thead>

                    <tbody x-data="{ st: $root.closest('form')?.__x?.$data }">
                        <!-- Mensaje hasta que se elija talla Y color -->
                        <tr x-show="!(st?.selected_pa_talla && st?.selected_pa_color)">
                            <td colspan="4" class="px-3 py-3 text-gray-500 border-t border-gray-200">
                            Selecciona una talla <strong>y</strong> un color para ver sus medidas.
                            </td>
                        </tr>

                        <?php foreach ($talla_order as $slug) :
                            echo '<pre style="background:#f8fafc;padding:8px;border:1px dashed #ccc">';
                            echo esc_html(print_r(['slug'=>$slug, 'row'=>$row], true));
                            echo '</pre>';
                            if (!isset($measures_by_talla[$slug])) continue;
                            $row    = $measures_by_talla[$slug];
                            $nombre = $talla_display[$slug] ?? $slug;
                            $ancho  = $row['ancho'] !== '' ? esc_html($row['ancho']) : '-';
                            $alto   = $row['alto']  !== '' ? esc_html($row['alto'])  : '-';
                            $largo  = $row['largo'] !== '' ? esc_html($row['largo']) : '-';
                        ?>

                               
                            <!-- Muestra la fila SOLO si hay talla seleccionada = esta y además hay color -->
                            <tr x-show="st?.selected_pa_talla === '<?= esc_js($slug) ?>' && !!st?.selected_pa_color"
                                :class="st?.selected_pa_talla === '<?= esc_js($slug) ?>' ? 'bg-blue-50' : ''">
                            <td class="px-3 py-2 border-t border-gray-200 font-medium text-gray-800"><?= esc_html($nombre) ?></td>
                            <td class="px-3 py-2 border-t border-gray-200"><?= $ancho ?></td>
                            <td class="px-3 py-2 border-t border-gray-200"><?= $alto  ?></td>
                            <td class="px-3 py-2 border-t border-gray-200"><?= $largo ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

            <p class="text-xs text-gray-500 mt-2">
            Medidas tomadas sobre prenda en cm. Pueden variar ±1–2&nbsp;cm.
            </p>
        </div>
    <?php endif; ?>


</form>
