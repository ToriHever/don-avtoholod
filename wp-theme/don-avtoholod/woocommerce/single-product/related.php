<?php
/**
 * Похожие товары — карусель вместо статичной сетки.
 */

defined('ABSPATH') || exit;

if (empty($related_products)) {
    return;
}
?>
<section class="related products dah-carousel-section">
    <h2><?php echo esc_html($heading); ?></h2>

    <div class="dah-carousel">
        <button type="button" class="dah-carousel__nav dah-carousel__nav--prev" aria-label="Назад">‹</button>

        <ul class="products dah-carousel__track">
            <?php foreach ($related_products as $related_product):
                $post_object = get_post($related_product->get_id());
                setup_postdata($GLOBALS['post'] = $post_object);
                global $product;
                $product = wc_get_product($post_object->ID);
                wc_get_template_part('content', 'product');
            endforeach;
            wp_reset_postdata(); ?>
        </ul>

        <button type="button" class="dah-carousel__nav dah-carousel__nav--next" aria-label="Вперёд">›</button>
    </div>
</section>
