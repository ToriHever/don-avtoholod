<?php
/**
 * Карточка товара — заголовок, артикул сразу под ним, кнопки в один ряд.
 */

defined('ABSPATH') || exit;

global $product;

if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('dah-product-single', $product); ?>>

    <?php do_action('woocommerce_before_single_product_summary'); ?>

    <div class="summary entry-summary">
        <h1 class="product_title entry-title"><?php the_title(); ?></h1>

        <?php $sku = $product->get_sku(); if ($sku): ?>
            <div class="dah-product-sku">Артикул: <?php echo esc_html($sku); ?></div>
        <?php endif; ?>

        <?php woocommerce_template_single_price(); ?>
        <?php woocommerce_template_single_excerpt(); ?>

        <div class="dah-product-actions">
            <?php woocommerce_template_single_add_to_cart(); ?>
            <?php dah_wc_single_ask_question_button(); ?>
        </div>

        <?php woocommerce_template_single_meta(); ?>
    </div>

    <?php do_action('woocommerce_after_single_product_summary'); ?>
</div>
