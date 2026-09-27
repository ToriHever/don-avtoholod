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
 * Хаб-шапка страницы категории: фото категории + описание.
 */
function dah_wc_category_hub_header(): void {
    if (!is_product_category()) {
        return;
    }
    $term = get_queried_object();
    if (!$term instanceof WP_Term) {
        return;
    }
    $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
    if (!$thumbnail_id) {
        return;
    }
    echo '<div class="dah-category-hub__image">';
    echo wp_get_attachment_image($thumbnail_id, 'medium');
    echo '</div>';
}

/**
 * Карточки подкатегорий на странице категории — до сетки товаров.
 */
function dah_wc_category_subcategories(): void {
    if (!is_product_category()) {
        return;
    }
    $term = get_queried_object();
    if (!$term instanceof WP_Term) {
        return;
    }
    $children = get_terms([
        'taxonomy' => 'product_cat',
        'parent' => $term->term_id,
        'hide_empty' => false,
    ]);
    if (empty($children) || is_wp_error($children)) {
        return;
    }
    echo '<div class="dah-subcats">';
    foreach ($children as $child) {
        $thumbnail_id = get_term_meta($child->term_id, 'thumbnail_id', true);
        echo '<a class="dah-subcats__item" href="' . esc_url(get_term_link($child)) . '">';
        echo '<span class="dah-subcats__image">';
        if ($thumbnail_id) {
            echo wp_get_attachment_image($thumbnail_id, 'thumbnail');
        } else {
            echo '<span class="dah-subcats__placeholder">' . esc_html(mb_substr($child->name, 0, 1)) . '</span>';
        }
        echo '</span>';
        echo '<span class="dah-subcats__name">' . esc_html($child->name) . '</span>';
        echo '</a>';
    }
    echo '</div>';
}

/**
 * Главная страница магазина: сетка карточек всех верхнеуровневых категорий.
 */
function dah_wc_shop_category_grid(): void {
    $terms = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent' => 0,
    ]);
    if (empty($terms) || is_wp_error($terms)) {
        return;
    }
    echo '<h2 class="dah-shop-section-title">Разделы каталога</h2>';
    echo '<div class="dah-cat-grid">';
    foreach ($terms as $term) {
        $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
        echo '<a class="dah-cat-grid__item" href="' . esc_url(get_term_link($term)) . '">';
        echo '<span class="dah-cat-grid__image">';
        if ($thumbnail_id) {
            echo wp_get_attachment_image($thumbnail_id, 'medium');
        } else {
            echo '<span class="dah-cat-grid__placeholder">' . esc_html(mb_substr($term->name, 0, 1)) . '</span>';
        }
        echo '</span>';
        echo '<span class="dah-cat-grid__name">' . esc_html($term->name) . '</span>';
        echo '<span class="dah-cat-grid__count">' . (int) $term->count . ' товаров</span>';
        echo '</a>';
    }
    echo '</div>';
}

/**
 * Главная страница магазина: подборка популярных/рекомендуемых товаров.
 */
function dah_wc_shop_featured_products(): void {
    $products = wc_get_products(['status' => 'publish', 'featured' => true, 'limit' => 8]);
    if (empty($products)) {
        $products = wc_get_products(['status' => 'publish', 'orderby' => 'date', 'order' => 'DESC', 'limit' => 8]);
    }
    if (empty($products)) {
        return;
    }
    echo '<h2 class="dah-shop-section-title">Популярные товары</h2>';
    echo '<ul class="products">';
    foreach ($products as $product) {
        global $post;
        $post = get_post($product->get_id());
        setup_postdata($post);
        wc_get_template_part('content', 'product');
    }
    wp_reset_postdata();
    echo '</ul>';
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

// Форма отзыва скрыта за кнопкой «Оставить отзыв» — раскрывается по клику (см. footer.php).
add_action('comment_form_before', function (): void {
    echo '<button type="button" class="btn btn--secondary" id="dah-toggle-review-form">Оставить отзыв</button>';
});

// Свои хлебные крошки вместо стандартных, с нашей вёрсткой.
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
add_action('woocommerce_before_main_content', 'dah_wc_breadcrumb', 20);
// Крошка "Страница N" на пагинированных страницах каталога не нужна.
add_filter('woocommerce_get_breadcrumb', function (array $crumbs): array {
    $last = end($crumbs);
    if ($last && isset($last[0]) && preg_match('/^(Страница|Page)\s+\d+$/u', trim($last[0]))) {
        array_pop($crumbs);
    }
    return $crumbs;
});

function dah_wc_breadcrumb(): void {
    woocommerce_breadcrumb([
        'delimiter' => ' / ',
        'wrap_before' => '<nav class="dah-breadcrumb woocommerce-breadcrumb">',
        'wrap_after' => '</nav>',
        'home' => 'Главная',
    ]);
}
