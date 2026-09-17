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
