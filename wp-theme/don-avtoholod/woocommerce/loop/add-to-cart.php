<?php
/**
 * Кнопка "В корзину" в каталоге + поле количества (по просьбе — можно
 * выбрать количество прямо на карточке, не заходя в товар).
 *
 * Не полагаемся на точную структуру $args, которую передаёт
 * woocommerce_template_loop_add_to_cart() — в разных версиях WooCommerce
 * она может отличаться. Вместо этого считаем всё сами из $product,
 * с теми же значениями по умолчанию, что использует сам WooCommerce.
 */

defined('ABSPATH') || exit;

echo '<span style="background:red;color:#fff;font-size:10px;display:block;">DEBUG: product=' . (empty($product) ? 'EMPTY' : $product->get_id()) . ' purchasable=' . (empty($product) ? '-' : var_export($product->is_purchasable(), true)) . ' in_stock=' . (empty($product) ? '-' : var_export($product->is_in_stock(), true)) . '</span>';

if (empty($product) || !$product->is_purchasable()) {
    return;
}

echo wc_get_stock_html($product);

if (!$product->is_in_stock()) {
    return;
}

$dah_quantity = $args['quantity'] ?? 1;
$dah_class = $args['class'] ?? implode(' ', array_filter([
    'button',
    'product_type_' . $product->get_type(),
    'add_to_cart_button',
    $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
]));
$dah_attributes = $args['attributes'] ?? [
    'data-product_id' => $product->get_id(),
    'data-product_sku' => $product->get_sku(),
    'aria-label' => $product->add_to_cart_description(),
    'rel' => 'nofollow',
];
?>
<div class="dah-card-cart-row">
    <?php
    woocommerce_quantity_input(
        [
            'min_value' => apply_filters('woocommerce_quantity_input_min', 1, $product),
            'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
            'input_value' => 1,
        ],
        $product,
        true
    );
    ?>
    <?php
    echo apply_filters(
        'woocommerce_loop_add_to_cart_link',
        sprintf(
            '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
            esc_url($product->add_to_cart_url()),
            esc_attr($dah_quantity),
            esc_attr($dah_class),
            wc_implode_html_attributes($dah_attributes),
            esc_html($product->add_to_cart_text())
        ),
        $product,
        ['quantity' => $dah_quantity, 'class' => $dah_class, 'attributes' => $dah_attributes]
    );
    ?>
</div>
