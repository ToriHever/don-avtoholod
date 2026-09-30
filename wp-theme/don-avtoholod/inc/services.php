<?php
/**
 * Каталог услуг: 3 посадочные страницы — по одной на категорию
 * ("Диагностика и ремонт систем климата", "Установка оборудования и
 * дополнительные услуги", "Автономные отопители"). Раньше на каждую
 * отдельную работу заводилась своя страница (19 штук) — это раздувало
 * структуру без пользы; конкретные работы теперь строки в таблице цен
 * (блок "Перечень работ и цены") внутри страницы своей категории.
 *
 * Тип записи "service" оставлен зарегистрированным (на случай, если
 * когда-то понадобится отдельная страница под одну услугу), но публично
 * не используется — контент/поля живут на самой таксономии.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * SEO: title и description страницы категории услуг должны включать город.
 */
add_filter('document_title_parts', function (array $parts): array {
    if (is_tax('service_category') && stripos($parts['title'] ?? '', 'ростов') === false) {
        $parts['title'] .= ' в Ростове-на-Дону';
    }
    return $parts;
});

add_action('wp_head', function (): void {
    if (!is_tax('service_category')) {
        return;
    }
    $term = get_queried_object();
    $desc = get_field('service_hero_note', $term) ?: ($term instanceof WP_Term ? $term->description : '');
    if ($desc) {
        echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($desc)) . '">' . "\n";
    }
}, 1);

/**
 * Тип записи "Услуга" — зарегистрирован для гибкости на будущее,
 * публично не используется (нет ссылок на отдельные услуги на сайте).
 */
function dah_register_service_post_type(): void {
    register_post_type('service', [
        'labels' => [
            'name' => 'Услуги (архив, не используется публично)',
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
 * Категория услуг — теперь это и есть посадочная страница. Отдельное
 * слово в базе ЧПУ ("uslugi-razdel"), чтобы не повторить историю с
 * совпадением баз категорий и товаров в магазине.
 */
function dah_register_service_category_taxonomy(): void {
    register_taxonomy('service_category', 'service', [
        'labels' => [
            'name' => 'Категории услуг (посадочные страницы)',
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
 * Цепочка хлебных крошек — используется и для нав-меню, и для
 * микроразметки BreadcrumbList.
 */
function dah_service_breadcrumb_items(): array {
    $crumbs = [['label' => 'Главная', 'url' => home_url('/')]];

    if (is_tax('service_category')) {
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
 * Микроразметка Schema.org для посадочной страницы категории услуг:
 * BreadcrumbList всегда, Service/Offer — если указана цена «от»,
 * FAQPage — если заполнен FAQ. Вызывается из taxonomy-service_category.php.
 */
function dah_service_schema(): void {
    if (!is_tax('service_category')) {
        return;
    }

    $term = get_queried_object();
    if (!($term instanceof WP_Term)) {
        return;
    }

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

    $term_link = get_term_link($term);
    $service = [
        '@type' => 'Service',
        'serviceType' => $term->name,
        'name' => $term->name,
        'url' => is_string($term_link) ? $term_link : home_url('/'),
        'areaServed' => 'Ростов-на-Дону',
        'provider' => [
            '@type' => 'AutoRepair',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
        ],
    ];

    $price_from = get_field('service_price_from', $term);
    if ($price_from && preg_match('/[\d\s]+/', $price_from, $m)) {
        $price = (int) preg_replace('/\D/', '', $m[0]);
        if ($price > 0) {
            $service['offers'] = [
                '@type' => 'Offer',
                'price' => $price,
                'priceCurrency' => 'RUB',
                'url' => $service['url'],
            ];
        }
    }
    $graph[] = $service;

    $faq_items = array_map(fn ($l) => dah_split_line($l, 2), dah_split_lines(get_field('service_faq', $term)));
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
