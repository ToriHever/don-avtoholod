<?php
/**
 * Дон Авто Холод — functions.php
 */

if (!defined('ABSPATH')) {
    exit;
}

function dah_setup(): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus([
        'primary' => 'Главное меню',
    ]);
}
add_action('after_setup_theme', 'dah_setup');

function dah_enqueue_assets(): void {
    wp_enqueue_style('dah-style', get_stylesheet_uri(), [], '1.0.0');
}
add_action('wp_enqueue_scripts', 'dah_enqueue_assets');

/**
 * Транслитерация кириллицы для ЧПУ (адреса не должны содержать кириллицу
 * и прочие не латинские символы).
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

/**
 * Глобально: любой ЧПУ (записи, страницы, товары, категории и т.д.)
 * автоматически транслитерируется из кириллицы, а не превращается
 * в проценто-кодированную абракадабру вида %d0%b0%d0%b1... — так было
 * до этого правила и приводило к путанице в ссылках и фильтрах.
 * Приоритет 5, чтобы отработать до стандартного sanitize_title_with_dashes (10).
 */
add_filter('sanitize_title', function ($title) {
    return dah_translit($title);
}, 5);

/**
 * Оценка времени чтения записи (используется в списке и в статье).
 */
function dah_reading_time(string $content): string {
    $words = str_word_count(wp_strip_all_tags($content));
    $minutes = max(1, (int) ceil($words / 180));
    return $minutes . ' мин чтения';
}

/**
 * Заглушки для новостей без своей обложки: пул картинок из медиабиблиотеки
 * (id хранятся в опции, заполняется одноразовым скриптом). Картинка
 * выбирается по ID записи — выглядит случайно, но закреплена за
 * конкретной новостью (не "мигает" другой при каждом обновлении страницы).
 */
function dah_news_placeholder_ids(): array {
    return array_values(array_filter(array_map('intval', (array) get_option('dah_news_placeholder_ids', []))));
}

function dah_has_post_thumbnail_or_placeholder(int $post_id = 0): bool {
    $post_id = $post_id ?: get_the_ID();
    return has_post_thumbnail($post_id) || !empty(dah_news_placeholder_ids());
}

function dah_the_post_thumbnail_or_placeholder(string $size = 'medium', int $post_id = 0): void {
    $post_id = $post_id ?: get_the_ID();
    if (has_post_thumbnail($post_id)) {
        the_post_thumbnail($size);
        return;
    }
    $ids = dah_news_placeholder_ids();
    if (empty($ids)) {
        return;
    }
    $id = $ids[$post_id % count($ids)];
    echo wp_get_attachment_image($id, $size);
}


/**
 * Вопрос с сайта отправляется на email через стандартную форму (без JS-модалки).
 * Обрабатываем POST от формы "Задать вопрос" и отправляем письмо администратору.
 */
function dah_handle_question_form(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['dah_question_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['dah_question_nonce'], 'dah_question_form')) {
        return;
    }

    $name = sanitize_text_field($_POST['name'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $question = sanitize_textarea_field($_POST['question'] ?? '');

    if (empty($phone) || empty($question)) {
        return;
    }

    $to = get_option('admin_email');
    $subject = 'Вопрос с сайта от ' . ($name ?: 'клиента');
    $body = "Имя: {$name}\nТелефон: {$phone}\nEmail: {$email}\n\nВопрос:\n{$question}";

    wp_mail($to, $subject, $body);

    wp_safe_redirect(add_query_arg('question_sent', '1', wp_get_referer() ?: home_url('/')));
    exit;
}
add_action('init', 'dah_handle_question_form');

/**
 * Ссылки на новости должны лежать в разделе /новости/, а не в корне сайта
 * (по умолчанию WordPress ставит записи в корень, даже если назначена
 * отдельная "страница записей" — это не влияет на структуру ссылок).
 */
function dah_news_base_slug(): string {
    $page_id = (int) get_option('page_for_posts');
    if ($page_id) {
        $slug = get_post_field('post_name', $page_id);
        if ($slug) {
            return $slug;
        }
    }
    return 'novosti';
}

add_filter('post_link', function (string $url, WP_Post $post): string {
    if ($post->post_type !== 'post') {
        return $url;
    }
    return home_url('/' . dah_news_base_slug() . '/' . $post->post_name . '/');
}, 10, 2);

add_action('init', function (): void {
    add_rewrite_rule(
        '^' . dah_news_base_slug() . '/([^/]+)/?$',
        'index.php?post_type=post&name=$matches[1]',
        'top'
    );
});

// Одноразовый сброс правил ЧПУ, чтобы новое правило заработало без похода в Настройки → Постоянные ссылки.
add_action('init', function (): void {
    if (get_option('dah_news_rewrite_flushed_v1') !== '1') {
        update_option('dah_news_rewrite_flushed_v1', '1');
        add_action('shutdown', 'flush_rewrite_rules');
    }
}, 20);

require_once get_template_directory() . '/inc/acf-fields.php';
require_once get_template_directory() . '/inc/services.php';
require_once get_template_directory() . '/inc/woocommerce.php';
require_once get_template_directory() . '/inc/woocommerce-catalog-hooks.php';
