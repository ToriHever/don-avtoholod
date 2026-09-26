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
        'hide_empty' => false,
        'parent' => 0,
        'exclude' => [get_option('default_product_cat')],
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
                        'hide_empty' => false,
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
 * Фильтр по марке автомобиля (атрибут pa_marka) над сеткой каталога.
 */
function dah_wc_brand_filter(): void {
    if (!taxonomy_exists('pa_marka')) {
        return;
    }
    $terms = get_terms(['taxonomy' => 'pa_marka', 'hide_empty' => true]);
    if (empty($terms) || is_wp_error($terms)) {
        return;
    }
    $current = isset($_GET['filter_marka']) ? sanitize_title(wp_unslash($_GET['filter_marka'])) : '';
    ?>
    <form class="dah-brand-filter" method="get">
        <label for="dah-brand-filter-select">Марка автомобиля</label>
        <select name="filter_marka" id="dah-brand-filter-select" onchange="this.form.submit()">
            <option value="">Все марки</option>
            <?php foreach ($terms as $term): ?>
                <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($current, $term->slug); ?>>
                    <?php echo esc_html($term->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
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
// Цену выводим один раз, отдельно от заголовка (см. content-product.php) — убираем из-под ссылки.
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
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

// Кнопка «Задать вопрос» на странице товара (вызывается напрямую из content-single-product.php).
function dah_wc_single_ask_question_button(): void {
    echo '<button type="button" class="btn btn--secondary dah-product-ask" data-modal-open="dah-question-modal">Задать вопрос</button>';
}

/**
 * Транслитерация кириллицы для ЧПУ (адреса категорий не должны содержать кириллицу).
 */
function dah_translit(string $text): string {
    $map = [
        'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'zh','з'=>'z','и'=>'i',
        'й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t',
        'у'=>'u','ф'=>'f','х'=>'h','ц'=>'ts','ч'=>'ch','ш'=>'sh','щ'=>'sch','ъ'=>'','ы'=>'y','ь'=>'',
        'э'=>'e','ю'=>'yu','я'=>'ya',
    ];
    $text = mb_strtolower($text);
    $result = '';
    foreach (preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY) as $char) {
        $result .= $map[$char] ?? $char;
    }
    return $result;
}

// Slug нового товара = его артикул (SKU), без кириллицы.
add_action('save_post_product', 'dah_wc_slug_from_sku', 20, 3);
function dah_wc_slug_from_sku(int $post_id, WP_Post $post, bool $update): void {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    remove_action('save_post_product', 'dah_wc_slug_from_sku', 20);
    $sku = get_post_meta($post_id, '_sku', true);
    if ($sku) {
        $desired = sanitize_title(dah_translit($sku));
        if ($post->post_name !== $desired) {
            wp_update_post(['ID' => $post_id, 'post_name' => $desired]);
        }
    }
    add_action('save_post_product', 'dah_wc_slug_from_sku', 20, 3);
}

// Плейсхолдеры вместо подписей у полей формы отзыва (имя/email/текст).
add_filter('comment_form_default_fields', function (array $fields): array {
    if (isset($fields['author'])) {
        $fields['author'] = str_replace('<input', '<input placeholder="Ваше имя"', $fields['author']);
    }
    if (isset($fields['email'])) {
        $fields['email'] = str_replace('<input', '<input placeholder="Email"', $fields['email']);
    }
    return $fields;
});
add_filter('comment_form_field_comment', function (string $field): string {
    return str_replace('<textarea', '<textarea placeholder="Ваш отзыв"', $field);
});

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
