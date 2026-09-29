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
 * Пагинация каталога должна оставаться параметром ?paged=N в адресе,
 * а не отдельным «путём» /page/N/ — WordPress по умолчанию сам
 * переписывает такие адреса в канонический /page/N/ вид, здесь это
 * отключаем именно для страниц магазина/категорий.
 */
add_filter('redirect_canonical', function ($redirect_url, $requested_url) {
    if ((is_shop() || is_product_category()) && get_query_var('paged')) {
        return false;
    }
    return $redirect_url;
}, 10, 2);

/**
 * Применяет фильтр по марке автомобиля (?filter_marka=slug) к запросу
 * товаров каталога — сам dah_wc_brand_filter() только рисует выпадающий
 * список, а не меняет выборку.
 */
add_action('pre_get_posts', function (WP_Query $query): void {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    if (!(is_shop() || is_product_category())) {
        return;
    }
    if (empty($_GET['filter_marka'])) {
        return;
    }
    $slug = sanitize_title(wp_unslash($_GET['filter_marka']));
    if (!$slug) {
        return;
    }
    $tax_query = $query->get('tax_query');
    if (!is_array($tax_query)) {
        $tax_query = [];
    }
    $tax_query[] = [
        'taxonomy' => 'pa_marka',
        'field' => 'slug',
        'terms' => $slug,
    ];
    $query->set('tax_query', $tax_query);
});

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

    $current = null;
    if (is_tax('product_cat')) {
        $current = get_queried_object();
    } elseif (is_product()) {
        $viewed_product = wc_get_product(get_queried_object_id());
        if ($viewed_product instanceof WC_Product) {
            $current = dah_wc_product_primary_category($viewed_product);
        }
    }
    $goto_icon = '<svg class="shop-sidebar__goto-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    ?>
    <nav class="shop-sidebar">
        <h3 class="shop-sidebar__title">Категории</h3>
        <ul class="shop-sidebar__list">
            <li class="shop-sidebar__item">
                <div class="shop-sidebar__row shop-sidebar__row--leaf">
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>"
                       class="shop-sidebar__link <?php echo (!$current) ? 'is-active' : ''; ?>">
                        <span class="shop-sidebar__name">Все товары</span>
                    </a>
                </div>
            </li>
            <?php foreach ($terms as $term):
                $children = get_terms([
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false,
                    'parent' => $term->term_id,
                ]);
                $has_children = !empty($children) && !is_wp_error($children);
                $is_current = $current && $current->term_id === $term->term_id;
                $has_active_child = $has_children && $current && in_array($current->term_id, wp_list_pluck($children, 'term_id'), true);
                $is_open = $is_current || $has_active_child;
                ?>
                <li class="shop-sidebar__item <?php echo $has_children ? 'has-children' : ''; ?> <?php echo $is_open ? 'is-open' : ''; ?>">
                    <div class="shop-sidebar__row">
                        <?php if ($has_children): ?>
                            <button type="button" class="shop-sidebar__toggle" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>">
                                <span class="shop-sidebar__chevron" aria-hidden="true"></span>
                                <span class="shop-sidebar__name"><?php echo esc_html($term->name); ?></span>
                                <span class="shop-sidebar__count"><?php echo (int) $term->count; ?></span>
                            </button>
                            <a href="<?php echo esc_url(get_term_link($term)); ?>"
                               class="shop-sidebar__goto <?php echo $is_current ? 'is-active' : ''; ?>"
                               aria-label="Перейти в раздел «<?php echo esc_attr($term->name); ?>»">
                                <?php echo $goto_icon; ?>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo esc_url(get_term_link($term)); ?>"
                               class="shop-sidebar__link <?php echo $is_current ? 'is-active' : ''; ?>">
                                <span class="shop-sidebar__name"><?php echo esc_html($term->name); ?></span>
                                <span class="shop-sidebar__count"><?php echo (int) $term->count; ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if ($has_children): ?>
                        <ul class="shop-sidebar__sublist">
                            <?php foreach ($children as $child): ?>
                                <li class="shop-sidebar__item">
                                    <div class="shop-sidebar__row shop-sidebar__row--leaf">
                                        <a href="<?php echo esc_url(get_term_link($child)); ?>"
                                           class="shop-sidebar__link <?php echo ($current && $current->term_id === $child->term_id) ? 'is-active' : ''; ?>">
                                            <span class="shop-sidebar__name"><?php echo esc_html($child->name); ?></span>
                                            <span class="shop-sidebar__count"><?php echo (int) $child->count; ?></span>
                                        </a>
                                    </div>
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

    $terms_args = ['taxonomy' => 'pa_marka', 'hide_empty' => true];

    // На странице конкретной категории список марок должен ограничиваться
    // товарами именно этой категории (и её подкатегорий), а не всем магазином —
    // иначе можно выбрать марку, для которой в этой категории нет ни одного товара.
    if (is_product_category()) {
        $category = get_queried_object();
        if ($category instanceof WP_Term) {
            $category_ids = [$category->term_id];
            $children = get_term_children($category->term_id, 'product_cat');
            if (!is_wp_error($children)) {
                $category_ids = array_merge($category_ids, $children);
            }
            $product_ids = get_objects_in_term($category_ids, 'product_cat');
            if (empty($product_ids) || is_wp_error($product_ids)) {
                return;
            }
            $terms_args['object_ids'] = $product_ids;
        }
    }

    $terms = get_terms($terms_args);
    if (empty($terms) || is_wp_error($terms)) {
        return;
    }
    $current = isset($_GET['filter_marka']) ? sanitize_title(wp_unslash($_GET['filter_marka'])) : '';
    $current_label = 'Все марки';
    foreach ($terms as $term) {
        if ($term->slug === $current) {
            $current_label = $term->name;
            break;
        }
    }
    ?>
    <div class="dah-brand-filter">
        <span class="dah-brand-filter__label">Марка автомобиля</span>
        <div class="dah-select" data-dah-select data-param="filter_marka">
            <button type="button" class="dah-select__button" aria-haspopup="listbox" aria-expanded="false">
                <span class="dah-select__value"><?php echo esc_html($current_label); ?></span>
                <span class="dah-select__chevron" aria-hidden="true"></span>
            </button>
            <ul class="dah-select__list" role="listbox" hidden>
                <li role="option" tabindex="0" data-value="" class="<?php echo $current === '' ? 'is-selected' : ''; ?>">Все марки</li>
                <?php foreach ($terms as $term): ?>
                    <li role="option" tabindex="0" data-value="<?php echo esc_attr($term->slug); ?>"
                        class="<?php echo $current === $term->slug ? 'is-selected' : ''; ?>">
                        <?php echo esc_html($term->name); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
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

// Счётчик товаров и сортировку теперь выводим вручную в archive-product.php
// (сразу под заголовком, в одну строку с фильтром по марке), а не через
// стандартный хук woocommerce_before_shop_loop — иначе они выводились бы дважды.
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

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

// Slug нового товара = его артикул (SKU), без кириллицы.
// (dah_translit() теперь объявлена в functions.php и используется глобально.)
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
