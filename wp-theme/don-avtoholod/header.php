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

      <div class="header__cta-group">
        <a class="btn btn--secondary header__cta" href="<?php echo esc_url(home_url('/#question-form')); ?>">Задать вопрос</a>
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
      <a class="btn btn--primary header__nav-question" href="<?php echo esc_url(home_url('/#question-form')); ?>">Задать вопрос</a>
    </div>
  </nav>
</header>
