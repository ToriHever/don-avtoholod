<?php
/**
 * Template Name: Обучение — раздел
 * Хаб-страница "Обучение": вступление/программа курса + ссылки на подстраницы.
 */

defined('ABSPATH') || exit;

get_header();

$dah_children = get_pages([
    'child_of' => get_the_ID(),
    'sort_column' => 'menu_order',
]);

$dah_benefits = [
    ['20 часов', 'Программа курса', 'Полный практический курс по ремонту и заправке автокондиционеров.'],
    ['12 дней', 'Формат занятий', 'Обучение в удобном для вас темпе, время согласовывается индивидуально.'],
    ['До 5 человек', 'Размер группы', 'Небольшие группы — больше практики и внимания каждому ученику.'],
    ['8 модулей', 'Программа', 'От физических основ работы кондиционера до заправки и промывки системы.'],
];
?>

<main>

<section class="section dah-training-hero">
  <div class="container dah-training-hero__inner">
    <div class="dah-training-hero__content">
      <span class="section__eyebrow">Обучение</span>
      <h1 class="section__title"><?php the_title(); ?></h1>
      <p class="section__lead">
        Готовим специалистов по ремонту и заправке автокондиционеров — от физических основ работы систем климат-контроля до практики диагностики, заправки и промывки.
      </p>
      <div class="dah-training-hero__actions">
        <button type="button" class="btn btn--primary" data-modal-open="dah-question-modal">Записаться на обучение</button>
        <a class="btn btn--secondary" href="tel:+79287753852">Позвонить нам</a>
      </div>
    </div>
  </div>
</section>

<section class="section dah-training-benefits">
  <div class="container">
    <div class="dah-training-benefits__grid">
      <?php foreach ($dah_benefits as [$value, $title, $text]): ?>
        <div class="dah-training-benefit">
          <div class="dah-training-benefit__value"><?php echo esc_html($value); ?></div>
          <div class="dah-training-benefit__title"><?php echo esc_html($title); ?></div>
          <p class="dah-training-benefit__text"><?php echo esc_html($text); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="obuchenie-nav">
  <div class="container dah-shop-page">
    <nav class="dah-breadcrumb woocommerce-breadcrumb">
      <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a> / Обучение
    </nav>

    <h2 class="dah-shop-section-title">Программа курса</h2>
    <div class="dah-training-content">
      <?php while (have_posts()): the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; ?>
    </div>

    <?php if (!empty($dah_children)): ?>
      <h2 class="dah-shop-section-title">Разделы обучения</h2>
      <ul class="dah-service-grid">
          <?php foreach ($dah_children as $child): ?>
              <li class="dah-service-card">
                  <a class="dah-service-card__link" href="<?php echo esc_url(get_permalink($child)); ?>">
                      <h3 class="dah-service-card__title"><?php echo esc_html(get_the_title($child)); ?></h3>
                      <?php if ($child->post_excerpt): ?>
                          <p class="dah-service-card__excerpt"><?php echo esc_html($child->post_excerpt); ?></p>
                      <?php endif; ?>
                      <span class="dah-service-card__more">Подробнее →</span>
                  </a>
              </li>
          <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</section>

<section class="section dah-training-cta">
  <div class="container dah-training-cta__inner">
    <div>
      <h2 class="dah-training-cta__title">Готовы начать обучение?</h2>
      <p class="dah-training-cta__text">Оставьте заявку, и мы подберём удобное время для занятий.</p>
    </div>
    <div class="dah-training-cta__actions">
      <button type="button" class="btn btn--outline" data-modal-open="dah-question-modal">Записаться на обучение</button>
      <a class="btn btn--outline" href="tel:+79287753852">8-928-775-38-52</a>
    </div>
  </div>
</section>

</main>

<?php get_footer(); ?>
