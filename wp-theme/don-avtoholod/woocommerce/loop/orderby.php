<?php
/**
 * Сортировка каталога — тот же кастомный дропдаун, что и у фильтра по марке.
 * Логика формирования списка вариантов сортировки скопирована из
 * оригинального шаблона WooCommerce (templates/loop/orderby.php).
 */

defined('ABSPATH') || exit;

if (!woocommerce_products_will_display()) {
    return;
}

$show_default_orderby = 'menu_order' === apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', ''));

$catalog_orderby_options = apply_filters(
    'woocommerce_catalog_orderby',
    [
        'menu_order' => __('По умолчанию', 'woocommerce'),
        'popularity' => __('По популярности', 'woocommerce'),
        'rating' => __('По рейтингу', 'woocommerce'),
        'date' => __('Сначала новые', 'woocommerce'),
        'price' => __('Сначала дешевле', 'woocommerce'),
        'price-desc' => __('Сначала дороже', 'woocommerce'),
    ]
);

$default_orderby = wc_get_loop_prop('is_search') ? 'relevance' : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', ''));
$orderby = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) : $default_orderby;

if (wc_get_loop_prop('is_search')) {
    $catalog_orderby_options = array_merge(['relevance' => __('Релевантность', 'woocommerce')], $catalog_orderby_options);
    unset($catalog_orderby_options['menu_order']);
}

if ($show_default_orderby) {
    unset($catalog_orderby_options['menu_order']);
} else {
    unset($catalog_orderby_options['desc' === strtolower(get_option('woocommerce_default_sort_direction', 'desc')) ? 'price' : 'price-desc']);
}

if (!wc_review_ratings_enabled()) {
    unset($catalog_orderby_options['rating']);
}

if (!array_key_exists($orderby, $catalog_orderby_options)) {
    $orderby = current(array_keys($catalog_orderby_options));
}

$current_label = $catalog_orderby_options[$orderby] ?? current($catalog_orderby_options);
?>
<div class="dah-select dah-select--ordering" data-dah-select data-param="orderby">
    <button type="button" class="dah-select__button" aria-haspopup="listbox" aria-expanded="false" aria-label="<?php esc_attr_e('Сортировка', 'woocommerce'); ?>">
        <span class="dah-select__value"><?php echo esc_html($current_label); ?></span>
        <span class="dah-select__chevron" aria-hidden="true"></span>
    </button>
    <ul class="dah-select__list" role="listbox" hidden>
        <?php foreach ($catalog_orderby_options as $id => $name): ?>
            <li role="option" tabindex="0" data-value="<?php echo esc_attr($id); ?>"
                class="<?php echo $orderby === $id ? 'is-selected' : ''; ?>">
                <?php echo esc_html($name); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
