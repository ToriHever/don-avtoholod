<?php
/**
 * Категории/метки товара (артикул уже показан отдельно под заголовком — не дублируем).
 */

defined('ABSPATH') || exit;

global $product;
?>
<div class="product_meta">
    <?php echo wc_get_product_category_list($product->get_id(), ', ', '<span class="posted_in">' . _n('Категория:', 'Категории:', count($product->get_category_ids()), 'woocommerce') . ' ', '</span>'); ?>
</div>
