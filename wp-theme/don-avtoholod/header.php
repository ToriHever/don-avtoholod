<?php
/**
 * Шапка сайта.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="<?php echo esc_url(get_template_directory_uri() . '/assets/favicon.ico'); ?>">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="top"></div>

<header class="header">
  <div class="header__top">
    <div class="container header__top-inner">
      <a class="header__logo" href="<?php echo esc_url(home_url('/')); ?>">
        <img class="header__logo-icon" src="<?php echo esc_url(get_template_directory_uri() . '/assets/logo.jpg'); ?>" alt="Дон Авто Холод">
        <span class="header__logo-text">
          Дон Авто Холод
          <small>Ремонт и установка кондиционеров и рефрижераторов</small>
        </span>
      </a>

      <div class="header__contacts">
        <a class="header__phone" href="tel:+78632265846">8 (863) 226-58-46</a>
        <a class="header__phone" href="tel:+79287753852">8-928-775-38-52</a>
        <a class="header__email" href="mailto:info@donavtoholod.ru">info&#64;donavtoholod.ru</a>
      </div>

      <?php if (function_exists('WC')): ?>
        <a class="header__cart" href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" aria-label="<?php echo is_user_logged_in() ? 'Личный кабинет' : 'Войти'; ?>">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="12" cy="8" r="3.6" stroke="currentColor" stroke-width="1.8"/>
            <path d="M4.5 20c1.2-3.6 4-5.5 7.5-5.5s6.3 1.9 7.5 5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
        </a>
        <a class="header__cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="Корзина">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M3 4h2l2.4 12.2a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L21 8H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="10" cy="21" r="1.4" fill="currentColor"/>
            <circle cx="18" cy="21" r="1.4" fill="currentColor"/>
          </svg>
          <?php $dah_cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
          <?php if ($dah_cart_count > 0): ?>
            <span class="header__cart-count"><?php echo esc_html($dah_cart_count); ?></span>
          <?php endif; ?>
        </a>
      <?php endif; ?>

      <div class="header__cta-group">
        <button type="button" class="btn btn--secondary header__cta" data-modal-open="dah-question-modal">Задать вопрос</button>
        <a class="btn btn--primary header__cta" href="tel:+79287753852">Заказать звонок</a>
      </div>

      <button class="header__burger" type="button" id="dah-burger" aria-label="Меню">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>

  <nav class="header__nav" id="dah-nav">
    <div class="container header__nav-inner">
      <ul class="header__nav-list">
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Главная</a></li>
        <?php if (function_exists('wc_get_page_id')): ?>
          <li><a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Магазин</a></li>
        <?php endif; ?>
        <li><a href="<?php echo esc_url(home_url('/#services')); ?>">Услуги</a></li>
        <li><a href="<?php echo esc_url(home_url('/#heaters')); ?>">Автономные отопители</a></li>
        <li><a href="<?php echo esc_url(home_url('/#about')); ?>">О компании</a></li>
        <li><a href="<?php echo esc_url(home_url('/#faq')); ?>">FAQ</a></li>
        <li><a href="<?php echo esc_url(home_url('/#contacts')); ?>">Контакты</a></li>
      </ul>
      <a
        class="header__nav-address"
        href="https://yandex.ru/maps/-/CTxSIHkb"
        target="_blank"
        rel="noopener"
      >г. Ростов-на-Дону, ул. Вавилова, 58, АТП-3</a>
      <button type="button" class="btn btn--primary header__nav-question" data-modal-open="dah-question-modal">Задать вопрос</button>
    </div>
  </nav>
</header>
