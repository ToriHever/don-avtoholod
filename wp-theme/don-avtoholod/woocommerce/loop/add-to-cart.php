<?php
/**
 * Кнопка "В корзину" в каталоге + поле количества (по просьбе — можно
 * выбрать количество прямо на карточке, не заходя в товар).
 */

defined('ABSPATH') || exit;

if (empty($product) || !$product->is_purchasable()) {
    return;
}

echo wc_get_stock_html($product);

if (!$product->is_in_stock()) {
    return;
}
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
            esc_attr($args['quantity']),
            esc_attr($args['class']),
            wc_implode_html_attributes($args['attributes']),
            esc_html($product->add_to_cart_text())
        ),
        $product,
        $args
    );
    ?>
</div>
