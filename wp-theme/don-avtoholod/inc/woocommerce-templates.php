<?php
/**
 * Кастомные хуки и вспомогательные функции для шаблонов каталога.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WooCommerce')) {
    return;
}

/**
 * Дерево категорий товаров для бокового меню каталога.
 */
function dah_wc_category_sidebar(): void {
    $terms = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0,
    ]);

    if (empty($terms) || is_wp_error($terms)) {
        return;
    }

    $current = is_tax('product_cat') ? get_queried_object() : null;
    ?>
    <nav class="shop-sidebar">
        <h3 class="shop-sidebar__title">Категории</h3>
        <ul class="shop-sidebar__list">
            <li>
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>"
                   class="<?php echo (!$current) ? 'is-active' : ''; ?>">Все товары</a>
            </li>
            <?php foreach ($terms as $term): ?>
                <li>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>"
                       class="<?php echo ($current && $current->term_id === $term->term_id) ? 'is-active' : ''; ?>">
                        <?php echo esc_html($term->name); ?>
                        <span class="shop-sidebar__count"><?php echo (int) $term->count; ?></span>
                    </a>
                    <?php
                    $children = get_terms([
                        'taxonomy' => 'product_cat',
                        'hide_empty' => true,
                        'parent' => $term->term_id,
                    ]);
                    if (!empty($children) && !is_wp_error($children)):
                        ?>
                        <ul class="shop-sidebar__sublist">
                            <?php foreach ($children as $child): ?>
                                <li>
                                    <a href="<?php echo esc_url(get_term_link($child)); ?>"
                                       class="<?php echo ($current && $current->term_id === $child->term_id) ? 'is-active' : ''; ?>">
                                        <?php echo esc_html($child->name); ?>
                                        <span class="shop-sidebar__count"><?php echo (int) $child->count; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php
}

/**
 * Первая (самая специфичная) категория товара — для бейджа на карточке.
 */
function dah_wc_product_primary_category(WC_Product $product): ?WP_Term {
    $terms = get_the_terms($product->get_id(), 'product_cat');
    if (empty($terms) || is_wp_error($terms)) {
        return null;
    }
    usort($terms, fn($a, $b) => $b->parent <=> $a->parent);
    return $terms[0];
}

// Артикул вместо рейтинга на карточке товара в каталоге.
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
add_action('woocommerce_after_shop_loop_item_title', 'dah_wc_loop_sku', 5);
function dah_wc_loop_sku(): void {
    global $product;
    if (!$product instanceof WC_Product) {
        return;
    }
    $sku = $product->get_sku();
    if ($sku) {
        echo '<div class="dah-product-sku">Артикул: ' . esc_html($sku) . '</div>';
    }
}

// Кнопка «Задать вопрос» на странице товара.
add_action('woocommerce_single_product_summary', 'dah_wc_single_ask_question_button', 35);
function dah_wc_single_ask_question_button(): void {
    echo '<a href="' . esc_url(home_url('/#question-form')) . '" class="btn btn--secondary dah-product-ask">Задать вопрос</a>';
}

// Свои хлебные крошки вместо стандартных, с нашей вёрсткой.
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
add_action('woocommerce_before_main_content', 'dah_wc_breadcrumb', 20);
function dah_wc_breadcrumb(): void {
    woocommerce_breadcrumb([
        'delimiter' => ' / ',
        'wrap_before' => '<nav class="dah-breadcrumb woocommerce-breadcrumb">',
        'wrap_after' => '</nav>',
        'home' => 'Главная',
    ]);
}
