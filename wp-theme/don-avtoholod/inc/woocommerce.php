<?php
/**
 * Интеграция WooCommerce: магазин работает как каталог с заявками, без онлайн-оплаты.
 * Требуется бесплатный плагин WooCommerce.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WooCommerce')) {
    return;
}

function dah_woocommerce_setup(): void {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}

/**
 * Счётчик товаров в иконке корзины в шапке обновляется через AJAX
 * (без перезагрузки страницы) — WooCommerce сам подставит эту разметку
 * везде, где на странице есть элемент .header__cart-count.
 */
add_filter('woocommerce_add_to_cart_fragments', function (array $fragments): array {
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    ob_start();
    ?>
    <span class="header__cart-count"><?php echo $count > 0 ? esc_html($count) : ''; ?></span>
    <?php
    $fragments['.header__cart-count'] = ob_get_clean();
    return $fragments;
});

add_action('after_setup_theme', 'dah_woocommerce_setup');

// Свою вёрстку каталога/карточек пишем сами — стандартные стили WooCommerce не подключаем.
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

function dah_woocommerce_assets(): void {
    wp_enqueue_style(
        'dah-woocommerce',
        get_template_directory_uri() . '/assets/woocommerce.css',
        ['dah-style'],
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'dah_woocommerce_assets');

/**
 * Оформление заявки вместо оплаты: оставляем только имя и телефон,
 * убираем адрес доставки/выставления счёта — они тут не нужны.
 */
function dah_woocommerce_checkout_fields(array $fields): array {
    unset(
        $fields['billing']['billing_company'],
        $fields['billing']['billing_address_1'],
        $fields['billing']['billing_address_2'],
        $fields['billing']['billing_city'],
        $fields['billing']['billing_state'],
        $fields['billing']['billing_postcode'],
        $fields['billing']['billing_country'],
        $fields['billing']['billing_email']
    );

    if (isset($fields['billing']['billing_phone'])) {
        $fields['billing']['billing_phone']['required'] = true;
    }

    unset($fields['order']['order_comments']);
    $fields['order']['order_comments'] = [
        'type' => 'textarea',
        'label' => 'Комментарий к заявке',
        'required' => false,
        'class' => ['form-row-wide'],
    ];

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'dah_woocommerce_checkout_fields');

// Доставка/самовывоз обсуждаются по телефону — не считаем на сайте.
add_filter('woocommerce_cart_needs_shipping', '__return_false');

// Заявка есть заявка — налог на неё не считаем.
add_filter('woocommerce_calc_taxes', '__return_false');

function dah_woocommerce_order_button_text(): string {
    return 'Отправить заявку';
}
add_filter('woocommerce_order_button_text', 'dah_woocommerce_order_button_text');
