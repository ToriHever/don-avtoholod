<?php
/**
 * Карточка товара в каталоге/списке категории.
 */

defined('ABSPATH') || exit;

global $product;

if (empty($product) || !$product->is_visible()) {
    return;
}

$primary_cat = dah_wc_product_primary_category($product);
?>
<li <?php wc_product_class('', $product); ?>>
    <a href="<?php echo esc_url(get_the_permalink()); ?>" class="dah-product-card__link">
        <div class="dah-product-card__image">
            <?php echo woocommerce_get_product_thumbnail(); ?>
            <?php if ($primary_cat): ?>
                <span class="dah-product-card__tag"><?php echo esc_html($primary_cat->name); ?></span>
            <?php endif; ?>
        </div>

        <?php do_action('woocommerce_shop_loop_item_title'); ?>
        <?php do_action('woocommerce_after_shop_loop_item_title'); ?>
    </a>

    <?php woocommerce_template_loop_price(); ?>
    <?php woocommerce_template_loop_add_to_cart(); ?>
</li>
