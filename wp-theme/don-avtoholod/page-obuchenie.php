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

while (have_posts()): the_post();
    $dah_content = apply_filters('the_content', get_the_content());
endwhile;

// Разбиваем контент на: вступление / список модулей / текст после модулей.
$dah_intro = $dah_content;
$dah_after = '';
if (($dah_pos = mb_strpos($dah_content, '<h3>Программа курса</h3>')) !== false) {
    $dah_intro = mb_substr($dah_content, 0, $dah_pos);
    $dah_after = mb_substr($dah_content, $dah_pos + mb_strlen('<h3>Программа курса</h3>'));
}

$dah_modules = [];
if (preg_match_all('/<p><strong>Модуль\s*(\d+)\.<\/strong>\s*(.*?)<\/p>/is', $dah_after, $dah_matches, PREG_SET_ORDER)) {
    foreach ($dah_matches as $dah_match) {
        $dah_text = trim(wp_strip_all_tags($dah_match[2]));
        $dah_parts = preg_split('/(?<=[.!?])\s+/u', $dah_text, 2);
        $dah_modules[] = [
            'number' => $dah_match[1],
            'title' => $dah_parts[0] ?? $dah_text,
            'text' => $dah_parts[1] ?? '',
        ];
    }
}

$dah_outro = trim(preg_replace('/<p><strong>Модуль\s*\d+\.<\/strong>.*?<\/p>/is', '', $dah_after));
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
  <div class="container">
    <?php echo $dah_intro; ?>

    <?php if (!empty($dah_modules)): ?>
      <h2 class="dah-shop-section-title">Программа курса</h2>
      <div class="dah-modules" data-dah-modules>
        <ul class="dah-modules__list">
          <?php foreach ($dah_modules as $dah_i => $dah_module): ?>
            <li>
              <button type="button" class="dah-modules__item<?php echo $dah_i === 0 ? ' is-active' : ''; ?>" data-dah-module-btn="<?php echo esc_attr($dah_module['number']); ?>">
                <span class="dah-modules__num"><?php echo esc_html(str_pad($dah_module['number'], 2, '0', STR_PAD_LEFT)); ?></span>
                <span class="dah-modules__item-body">
                  <span class="dah-modules__item-title"><?php echo esc_html($dah_module['title']); ?></span>
                </span>
                <span class="dah-modules__arrow">→</span>
              </button>
            </li>
          <?php endforeach; ?>
        </ul>

        <div class="dah-modules__detail-wrap">
          <?php foreach ($dah_modules as $dah_i => $dah_module): ?>
            <div class="dah-modules__detail<?php echo $dah_i === 0 ? ' is-active' : ''; ?>" data-dah-module-detail="<?php echo esc_attr($dah_module['number']); ?>"<?php echo $dah_i === 0 ? '' : ' hidden'; ?>>
              <span class="dah-modules__detail-eyebrow">Модуль <?php echo esc_html($dah_module['number']); ?></span>
              <h3 class="dah-modules__detail-title"><?php echo esc_html($dah_module['title']); ?></h3>
              <?php if ($dah_module['text']): ?>
                <p class="dah-modules__detail-text"><?php echo esc_html($dah_module['text']); ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($dah_outro): ?>
      <div class="dah-training-content dah-training-content--outro"><?php echo $dah_outro; ?></div>
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
