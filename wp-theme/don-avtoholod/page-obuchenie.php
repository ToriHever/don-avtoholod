<?php
/**
 * Template Name: Обучение — раздел
 * Хаб-страница "Обучение": вступление/программа курса + ссылки на подстраницы.
 * Всё содержимое редактируется полями ACF (см. inc/acf-fields.php),
 * как и на главной странице — без блока произвольного текста.
 */

defined('ABSPATH') || exit;

get_header();

$dah_children = get_pages([
    'child_of' => get_the_ID(),
    'sort_column' => 'menu_order',
]);

$dah_benefits = array_map(
    fn ($line) => dah_split_line($line, 3),
    dah_split_lines(get_field('training_benefits'))
);

$dah_modules = array_map(
    fn ($line) => dah_split_line($line, 4),
    dah_split_lines(get_field('training_modules'))
);
?>

<main>

<div class="container dah-shop-page">
  <nav class="dah-breadcrumb woocommerce-breadcrumb">
    <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a> / Обучение
  </nav>
</div>

<section class="section dah-training-hero">
  <div class="container dah-training-hero__inner">
    <div class="dah-training-hero__content">
      <?php if ($dah_eyebrow = get_field('training_eyebrow')): ?>
        <span class="section__eyebrow"><?php echo esc_html($dah_eyebrow); ?></span>
      <?php endif; ?>
      <h1 class="section__title"><?php the_title(); ?></h1>
      <?php if ($dah_lead = get_field('training_lead')): ?>
        <p class="section__lead"><?php echo esc_html($dah_lead); ?></p>
      <?php endif; ?>
      <div class="dah-training-hero__actions">
        <button type="button" class="btn btn--primary" data-modal-open="dah-question-modal">Записаться на обучение</button>
        <a class="btn btn--secondary" href="tel:+79287753852">Позвонить нам</a>
      </div>
    </div>
  </div>
</section>

<?php if (!empty($dah_benefits)): ?>
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
<?php endif; ?>

<section class="section" id="obuchenie-nav">
  <div class="container">
    <?php if (!empty($dah_modules)): ?>
      <h2 class="dah-shop-section-title">Программа курса</h2>
      <div class="dah-modules" data-dah-modules>
        <ul class="dah-modules__list">
          <?php foreach ($dah_modules as $dah_i => [$dah_title, $dah_intro, $dah_learn, $dah_result]): $dah_num = $dah_i + 1; ?>
            <li>
              <button type="button" class="dah-modules__item<?php echo $dah_i === 0 ? ' is-active' : ''; ?>" data-dah-module-btn="<?php echo esc_attr($dah_num); ?>">
                <span class="dah-modules__num"><?php echo esc_html(str_pad((string) $dah_num, 2, '0', STR_PAD_LEFT)); ?></span>
                <span class="dah-modules__item-body">
                  <span class="dah-modules__item-title"><?php echo esc_html($dah_title); ?></span>
                </span>
                <span class="dah-modules__arrow">→</span>
              </button>
            </li>
          <?php endforeach; ?>
        </ul>

        <div class="dah-modules__detail-wrap">
          <?php foreach ($dah_modules as $dah_i => [$dah_title, $dah_intro, $dah_learn, $dah_result]): $dah_num = $dah_i + 1; ?>
            <div class="dah-modules__detail<?php echo $dah_i === 0 ? ' is-active' : ''; ?>" data-dah-module-detail="<?php echo esc_attr($dah_num); ?>"<?php echo $dah_i === 0 ? '' : ' hidden'; ?>>
              <span class="dah-modules__detail-eyebrow">Модуль <?php echo esc_html($dah_num); ?></span>
              <h3 class="dah-modules__detail-title"><?php echo esc_html($dah_title); ?></h3>
              <?php if ($dah_intro): ?>
                <p class="dah-modules__detail-text"><?php echo esc_html($dah_intro); ?></p>
              <?php endif; ?>
              <?php if ($dah_learn): ?>
                <div class="dah-modules__callout dah-modules__callout--learn">
                  <span class="dah-modules__callout-label">Что узнаете</span>
                  <p><?php echo esc_html($dah_learn); ?></p>
                </div>
              <?php endif; ?>
              <?php if ($dah_result): ?>
                <div class="dah-modules__callout dah-modules__callout--result">
                  <span class="dah-modules__callout-label">Результат</span>
                  <p><?php echo esc_html($dah_result); ?></p>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

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

<script>
(function () {
  var root = document.querySelector('[data-dah-modules]');
  if (!root) { return; }
  var buttons = root.querySelectorAll('[data-dah-module-btn]');
  var details = root.querySelectorAll('[data-dah-module-detail]');
  buttons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var id = btn.getAttribute('data-dah-module-btn');
      buttons.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
      details.forEach(function (d) {
        var active = d.getAttribute('data-dah-module-detail') === id;
        d.classList.toggle('is-active', active);
        if (active) { d.removeAttribute('hidden'); } else { d.setAttribute('hidden', ''); }
      });
    });
  });
})();
</script>

<?php get_footer(); ?>
