<?php
/**
 * Каталог услуг: свой тип записи + категория, отдельно от товаров WooCommerce.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * SEO: title и description страницы услуги должны включать город.
 */
add_filter('document_title_parts', function (array $parts): array {
    if (is_singular('service') && stripos($parts['title'] ?? '', 'ростов') === false) {
        $parts['title'] .= ' в Ростове-на-Дону';
    }
    return $parts;
});

add_action('wp_head', function (): void {
    if (!is_singular('service')) {
        return;
    }
    $post_id = get_the_ID();
    $desc = get_field('service_hero_note', $post_id) ?: get_the_excerpt($post_id);
    if ($desc) {
        echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($desc)) . '">' . "\n";
    }
}, 1);

/**
 * Тип записи "Услуга". Архив на /uslugi/, отдельная услуга на /uslugi/{slug}/.
 */
function dah_register_service_post_type(): void {
    register_post_type('service', [
        'labels' => [
            'name' => 'Услуги',
            'singular_name' => 'Услуга',
            'add_new_item' => 'Добавить услугу',
            'edit_item' => 'Редактировать услугу',
            'all_items' => 'Все услуги',
            'search_items' => 'Найти услугу',
            'not_found' => 'Услуги не найдены',
        ],
        'public' => true,
        'has_archive' => 'uslugi',
        'rewrite' => ['slug' => 'uslugi', 'with_front' => false],
        'menu_icon' => 'dashicons-hammer',
        'menu_position' => 21,
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'dah_register_service_post_type');

/**
 * Категория услуг. Отдельное слово в базе ЧПУ ("uslugi-razdel"), чтобы не
 * повторить историю с совпадением баз категорий и товаров в магазине.
 */
function dah_register_service_category_taxonomy(): void {
    register_taxonomy('service_category', 'service', [
        'labels' => [
            'name' => 'Категории услуг',
            'singular_name' => 'Категория услуги',
            'add_new_item' => 'Добавить категорию',
            'edit_item' => 'Редактировать категорию',
            'all_items' => 'Все категории',
        ],
        'public' => true,
        'hierarchical' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug' => 'uslugi-razdel', 'with_front' => false],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'dah_register_service_category_taxonomy');

/**
 * Цепочка хлебных крошек для текущей страницы услуг — используется и для
 * вывода нав-меню, и для микроразметки BreadcrumbList.
 */
function dah_service_breadcrumb_items(): array {
    $crumbs = [['label' => 'Главная', 'url' => home_url('/')]];

    if (is_singular('service')) {
        $crumbs[] = ['label' => 'Услуги', 'url' => get_post_type_archive_link('service')];
        $terms = get_the_terms(get_the_ID(), 'service_category');
        if (!empty($terms) && !is_wp_error($terms)) {
            $crumbs[] = ['label' => $terms[0]->name, 'url' => get_term_link($terms[0])];
        }
        $crumbs[] = ['label' => get_the_title()];
    } elseif (is_tax('service_category')) {
        $crumbs[] = ['label' => 'Услуги', 'url' => get_post_type_archive_link('service')];
        $crumbs[] = ['label' => single_term_title('', false)];
    } elseif (is_post_type_archive('service')) {
        $crumbs[] = ['label' => 'Услуги'];
    }

    return $crumbs;
}

/**
 * Хлебные крошки на страницах услуг — та же вёрстка, что и в магазине.
 */
function dah_service_breadcrumb(): void {
    $crumbs = dah_service_breadcrumb_items();

    echo '<nav class="dah-breadcrumb woocommerce-breadcrumb">';
    foreach ($crumbs as $i => $crumb) {
        if ($i > 0) {
            echo ' / ';
        }
        if ($i === count($crumbs) - 1 || empty($crumb['url'])) {
            echo esc_html($crumb['label']);
        } else {
            echo '<a href="' . esc_url($crumb['url']) . '">' . esc_html($crumb['label']) . '</a>';
        }
    }
    echo '</nav>';
}

/**
 * Категории услуг для бокового меню на страницах каталога услуг —
 * та же вёрстка, что и в сайдбаре магазина (shop-sidebar).
 */
function dah_service_category_sidebar(): void {
    $terms = get_terms(['taxonomy' => 'service_category', 'hide_empty' => false]);
    if (empty($terms) || is_wp_error($terms)) {
        return;
    }

    $current = is_tax('service_category') ? get_queried_object() : null;
    ?>
    <nav class="shop-sidebar">
        <h3 class="shop-sidebar__title">Категории услуг</h3>
        <ul class="shop-sidebar__list">
            <li class="shop-sidebar__item">
                <div class="shop-sidebar__row shop-sidebar__row--leaf">
                    <a href="<?php echo esc_url(get_post_type_archive_link('service')); ?>"
                       class="shop-sidebar__link <?php echo (!$current) ? 'is-active' : ''; ?>">
                        <span class="shop-sidebar__name">Все услуги</span>
                    </a>
                </div>
            </li>
            <?php foreach ($terms as $term): ?>
                <li class="shop-sidebar__item">
                    <div class="shop-sidebar__row shop-sidebar__row--leaf">
                        <a href="<?php echo esc_url(get_term_link($term)); ?>"
                           class="shop-sidebar__link <?php echo ($current && $current->term_id === $term->term_id) ? 'is-active' : ''; ?>">
                            <span class="shop-sidebar__name"><?php echo esc_html($term->name); ?></span>
                            <span class="shop-sidebar__count"><?php echo (int) $term->count; ?></span>
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php
}

/**
 * Микроразметка Schema.org для страницы услуги: BreadcrumbList всегда,
 * Service/Offer — если указана цена «от», FAQPage — если заполнен FAQ.
 * Вызывается из single-service.php.
 */
function dah_service_schema(): void {
    if (!is_singular('service')) {
        return;
    }

    $post_id = get_the_ID();
    $graph = [];

    $crumbs = dah_service_breadcrumb_items();
    $item_list = [];
    foreach ($crumbs as $i => $crumb) {
        $entry = [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $crumb['label'],
        ];
        if (!empty($crumb['url'])) {
            $entry['item'] = $crumb['url'];
        }
        $item_list[] = $entry;
    }
    $graph[] = [
        '@type' => 'BreadcrumbList',
        'itemListElement' => $item_list,
    ];

    $service = [
        '@type' => 'Service',
        'serviceType' => get_the_title($post_id),
        'name' => get_the_title($post_id),
        'url' => get_permalink($post_id),
        'areaServed' => 'Ростов-на-Дону',
        'provider' => [
            '@type' => 'AutoRepair',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
        ],
    ];

    $price_from = get_field('service_price_from', $post_id);
    if ($price_from && preg_match('/[\d\s]+/', $price_from, $m)) {
        $price = (int) preg_replace('/\D/', '', $m[0]);
        if ($price > 0) {
            $service['offers'] = [
                '@type' => 'Offer',
                'price' => $price,
                'priceCurrency' => 'RUB',
                'url' => get_permalink($post_id),
            ];
        }
    }
    $graph[] = $service;

    $faq_items = array_map(fn ($l) => dah_split_line($l, 2), dah_split_lines(get_field('service_faq', $post_id)));
    if (!empty($faq_items)) {
        $questions = [];
        foreach ($faq_items as [$question, $answer]) {
            if ($question === '' || $answer === '') {
                continue;
            }
            $questions[] = [
                '@type' => 'Question',
                'name' => $question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $answer,
                ],
            ];
        }
        if (!empty($questions)) {
            $graph[] = [
                '@type' => 'FAQPage',
                'mainEntity' => $questions,
            ];
        }
    }

    $data = [
        '@context' => 'https://schema.org',
        '@graph' => $graph,
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}
