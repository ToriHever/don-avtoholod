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
 * Оценка времени чтения записи (используется в списке и в статье).
 */
function dah_reading_time(string $content): string {
    $words = str_word_count(wp_strip_all_tags($content));
    $minutes = max(1, (int) ceil($words / 180));
    return $minutes . ' мин чтения';
}

function dah_news_card_meta(): void {
    ?>
    <span class="dah-news-meta">
        <?php echo esc_html(get_the_author()); ?>
        <span class="dah-news-meta__dot">•</span>
        <?php echo esc_html(get_the_date()); ?>
        <span class="dah-news-meta__dot">•</span>
        <?php echo esc_html(dah_reading_time(get_the_content())); ?>
    </span>
    <?php
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

require_once get_template_directory() . '/inc/acf-fields.php';
require_once get_template_directory() . '/inc/services.php';
require_once get_template_directory() . '/inc/woocommerce.php';
require_once get_template_directory() . '/inc/woocommerce-catalog-hooks.php';
