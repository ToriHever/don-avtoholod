<?php
/**
 * Template Name: Главная страница (Дон Авто Холод)
 */

get_header();

$theme_uri = get_template_directory_uri();

$hero_stats = array_map(fn ($l) => dah_split_line($l, 2), dah_split_lines(get_field('hero_stats')));
$features = array_map(fn ($l) => dah_split_line($l, 3), dah_split_lines(get_field('features')));
$service_groups = dah_split_groups(get_field('services_groups'));
$cooling_issues = dah_split_lines(get_field('cooling_issues'));
$refill_steps = array_map(fn ($l) => dah_split_line($l, 2), dah_split_lines(get_field('refill_steps')));
$heaters_brands = dah_split_lines(get_field('heaters_brands'));
$heaters_details = array_map(fn ($l) => dah_split_line($l, 2), dah_split_lines(get_field('heaters_details')));
$about_stats = array_map(fn ($l) => dah_split_line($l, 2), dah_split_lines(get_field('about_stats')));
$facts_items = array_map(fn ($l) => dah_split_line($l, 2), dah_split_lines(get_field('facts_items')));
$faq_items = array_map(fn ($l) => dah_split_line($l, 2), dah_split_lines(get_field('faq_items')));

$map_url = get_field('contacts_map_url') ?: 'https://yandex.ru/maps/-/CTxSIHkb';
$question_sent = isset($_GET['question_sent']);
?>

<main>

<section class="hero">
  <div class="container hero__inner">
    <div class="hero__content">
      <span class="hero__badge"><?php echo esc_html(get_field('hero_badge')); ?></span>
      <h1><?php echo esc_html(get_field('hero_title')); ?></h1>
      <p class="hero__lead"><?php echo esc_html(get_field('hero_lead')); ?></p>
      <div class="hero__actions">
        <a class="btn btn--primary" href="#question-form"><?php echo esc_html(get_field('hero_primary_button')); ?></a>
        <a class="btn btn--secondary" href="#services"><?php echo esc_html(get_field('hero_secondary_button')); ?></a>
      </div>

      <div class="hero__stats">
        <?php foreach ($hero_stats as [$value, $label]): ?>
          <div class="hero__stat">
            <div class="hero__stat-value"><?php echo esc_html($value); ?></div>
            <div class="hero__stat-label"><?php echo esc_html($label); ?></div>
          </div>
        <?php endforeach; ?>
        <div class="hero__own-badge">
          <span class="hero__own-badge-icon">✓</span>
          <?php echo esc_html(get_field('hero_own_badge')); ?>
        </div>
      </div>
    </div>

    <div class="hero__media">
      <div class="hero__media-photo">
        <img src="<?php echo esc_url($theme_uri . '/assets/hero-car.png'); ?>" alt="Автомобиль на сервисе Дон Авто Холод">
      </div>
      <div class="hero__media-badge">
        <span class="hero__media-badge-icon">✓</span>
        <div>
          <strong><?php echo esc_html(get_field('hero_media_badge_title')); ?></strong>
          <span><?php echo esc_html(get_field('hero_media_badge_text')); ?></span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="features">
  <div class="container features__grid">
    <?php foreach ($features as [$icon, $title, $text]): ?>
      <div class="feature-card">
        <div class="feature-card__icon" style="-webkit-mask-image:url(<?php echo esc_url($theme_uri . '/' . $icon); ?>); mask-image:url(<?php echo esc_url($theme_uri . '/' . $icon); ?>)"></div>
        <h3><?php echo esc_html($title); ?></h3>
        <p><?php echo esc_html($text); ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" id="services">
  <div class="container">
    <span class="section__eyebrow"><?php echo esc_html(get_field('services_eyebrow')); ?></span>
    <h2 class="section__title"><?php echo esc_html(get_field('services_title')); ?></h2>
    <p class="section__lead"><?php echo esc_html(get_field('services_lead')); ?></p>

    <div class="services__groups">
      <?php foreach ($service_groups as $group): ?>
        <div class="services__group">
          <h3><?php echo esc_html($group['title']); ?></h3>
          <ul class="services__list">
            <?php foreach ($group['items'] as $item): ?>
              <li><?php echo esc_html($item); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--alt" id="cooling-issues">
  <div class="container cooling-issues">
    <div class="cooling-issues__content">
      <span class="section__eyebrow"><?php echo esc_html(get_field('cooling_eyebrow')); ?></span>
      <h2 class="section__title"><?php echo esc_html(get_field('cooling_title')); ?></h2>
      <p class="section__lead"><?php echo esc_html(get_field('cooling_lead')); ?></p>
      <a class="btn btn--primary" href="#question-form"><?php echo esc_html(get_field('cooling_button')); ?></a>
    </div>

    <ul class="cooling-issues__list">
      <?php foreach ($cooling_issues as $issue): ?>
        <li><?php echo esc_html($issue); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="section" id="refill-steps">
  <div class="container">
    <span class="section__eyebrow"><?php echo esc_html(get_field('refill_eyebrow')); ?></span>
    <h2 class="section__title"><?php echo esc_html(get_field('refill_title')); ?></h2>
    <p class="section__lead"><?php echo esc_html(get_field('refill_lead')); ?></p>

    <div class="refill-steps">
      <?php foreach ($refill_steps as $i => [$title, $text]): ?>
        <div class="refill-step">
          <div class="refill-step__number"><?php echo esc_html($i + 1); ?></div>
          <h3><?php echo esc_html($title); ?></h3>
          <p><?php echo esc_html($text); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--alt" id="heaters">
  <div class="container heaters">
    <div>
      <span class="section__eyebrow"><?php echo esc_html(get_field('heaters_eyebrow')); ?></span>
      <h2 class="section__title"><?php echo esc_html(get_field('heaters_title')); ?></h2>
      <p class="section__lead"><?php echo esc_html(get_field('heaters_lead')); ?></p>

      <ul class="heaters__brands">
        <?php foreach ($heaters_brands as $brand): ?>
          <li><?php echo esc_html($brand); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="heaters__details">
      <?php foreach ($heaters_details as [$title, $text]): ?>
        <h3><?php echo esc_html($title); ?></h3>
        <p><?php echo esc_html($text); ?></p>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="about">
  <div class="container">
    <div class="about-intro">
      <div class="about-intro__media">
        <div class="about-intro__photo">
          <img src="<?php echo esc_url($theme_uri . '/assets/about-car.png'); ?>" alt="Система климат-контроля автомобиля">
        </div>
        <div class="about-intro__badge">
          <span class="about-intro__badge-icon">❄</span>
          <div>
            <strong><?php echo esc_html(get_field('about_badge_title')); ?></strong>
            <span><?php echo esc_html(get_field('about_badge_text')); ?></span>
          </div>
        </div>
      </div>

      <div class="about-intro__content">
        <span class="section__eyebrow"><?php echo esc_html(get_field('about_eyebrow')); ?></span>
        <h2 class="section__title"><?php echo esc_html(get_field('about_title')); ?></h2>
        <p class="section__lead"><?php echo esc_html(get_field('about_lead')); ?></p>

        <div class="about-intro__stats">
          <?php foreach ($about_stats as [$value, $label]): ?>
            <div class="about-intro__stat">
              <div class="about-intro__stat-value"><?php echo esc_html($value); ?></div>
              <div class="about-intro__stat-label"><?php echo esc_html($label); ?></div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="hero__own-badge about-intro__own-badge">
          <span class="hero__own-badge-icon">✓</span>
          <?php echo esc_html(get_field('about_own_badge')); ?>
        </div>
      </div>
    </div>

    <div class="about__text">
      <p><?php echo esc_html(get_field('about_paragraph1')); ?></p>
      <p>
        <?php echo esc_html(get_field('about_paragraph2_before')); ?>
        <a href="<?php echo esc_url($map_url); ?>" target="_blank" rel="noopener">улицы Вавилова</a>.
      </p>
    </div>
  </div>
</section>

<section class="section section--alt" id="facts">
  <div class="container">
    <span class="section__eyebrow"><?php echo esc_html(get_field('facts_eyebrow')); ?></span>
    <h2 class="section__title"><?php echo esc_html(get_field('facts_title')); ?></h2>

    <div class="facts__grid">
      <?php foreach ($facts_items as $i => [$title, $text]): ?>
        <div class="fact-card">
          <div class="fact-card__number"><?php echo esc_html($i + 1); ?></div>
          <h3><?php echo esc_html($title); ?></h3>
          <p><?php echo esc_html($text); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="price-note">
  <div class="container">
    <strong>Важно!</strong>
    <?php echo esc_html(get_field('price_note')); ?>
  </div>
</section>

<section class="section cta" id="contacts">
  <div class="container cta-layout">
    <div class="cta-layout__content">
      <span class="section__eyebrow"><?php echo esc_html(get_field('contacts_eyebrow')); ?></span>
      <h2 class="section__title"><?php echo esc_html(get_field('contacts_title')); ?></h2>
      <p class="section__lead"><?php echo esc_html(get_field('contacts_lead')); ?></p>

      <ul class="cta-layout__list">
        <li>
          <a href="<?php echo esc_url($map_url); ?>" target="_blank" rel="noopener">
            <?php echo esc_html(get_field('contacts_address')); ?>
          </a>
        </li>
        <li><?php echo esc_html(get_field('contacts_hours')); ?></li>
        <li><a href="mailto:<?php echo esc_attr(get_field('contacts_email')); ?>"><?php echo esc_html(get_field('contacts_email')); ?></a></li>
      </ul>

      <div class="cta-layout__actions">
        <a class="btn btn--primary" href="tel:<?php echo esc_attr(get_field('contacts_phone1')); ?>"><?php echo esc_html(get_field('contacts_phone1')); ?></a>
        <a class="btn btn--secondary" href="tel:<?php echo esc_attr(get_field('contacts_phone2')); ?>"><?php echo esc_html(get_field('contacts_phone2')); ?></a>
        <a class="btn btn--secondary" href="#question-form"><?php echo esc_html(get_field('contacts_question_button')); ?></a>
      </div>
    </div>

    <div class="cta-layout__media">
      <div class="cta-layout__map">
        <a
          href="https://yandex.ru/maps/org/atp_3/186384265217/?utm_medium=mapframe&utm_source=maps"
          class="cta-layout__map-attribution"
          style="top: 0"
          target="_blank"
          rel="noopener"
        >АТП № 3</a>
        <a
          href="https://yandex.ru/maps/39/rostov-na-donu/category/garage_cooperative/184107575/?utm_medium=mapframe&utm_source=maps"
          class="cta-layout__map-attribution"
          style="top: 14px"
          target="_blank"
          rel="noopener"
        >Гаражный кооператив в Ростове‑на‑Дону</a>
        <iframe
          src="https://yandex.ru/map-widget/v1/org/atp_3/186384265217/?ll=39.684581%2C47.269509&z=17.4"
          width="560"
          height="400"
          frameborder="1"
          allowfullscreen="true"
          loading="lazy"
          title="Дон Авто Холод на карте"
        ></iframe>
      </div>
    </div>
  </div>
</section>

<section class="section" id="faq">
  <div class="container faq-layout">
    <div class="faq-layout__questions">
      <span class="section__eyebrow"><?php echo esc_html(get_field('faq_eyebrow')); ?></span>
      <h2 class="section__title"><?php echo esc_html(get_field('faq_title')); ?></h2>

      <div class="faq__list">
        <?php foreach ($faq_items as [$question, $answer]): ?>
          <details class="faq-item">
            <summary><?php echo esc_html($question); ?></summary>
            <p><?php echo esc_html($answer); ?></p>
          </details>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="faq-layout__form" id="question-form">
      <h3><?php echo esc_html(get_field('faq_form_title')); ?></h3>

      <?php if ($question_sent): ?>
        <p class="question-form__lead">
          Спасибо! Ваш вопрос отправлен, мы ответим как можно скорее. Если срочно — позвоните нам:
          <a href="tel:<?php echo esc_attr(get_field('contacts_phone1')); ?>"><?php echo esc_html(get_field('contacts_phone1')); ?></a>.
        </p>
      <?php else: ?>
        <p class="question-form__lead">Ответим в ближайшее рабочее время по телефону или почте.</p>
        <form method="post" action="<?php echo esc_url(home_url('/#question-form')); ?>">
          <?php wp_nonce_field('dah_question_form', 'dah_question_nonce'); ?>
          <label class="question-form__field">
            <span>Имя</span>
            <input type="text" name="name" placeholder="Как к вам обращаться">
          </label>
          <label class="question-form__field">
            <span>Телефон *</span>
            <input type="tel" name="phone" required placeholder="+7 (___) ___-__-__">
          </label>
          <label class="question-form__field">
            <span>Email</span>
            <input type="email" name="email" placeholder="you@mail.ru">
          </label>
          <label class="question-form__field">
            <span>Вопрос *</span>
            <textarea name="question" required rows="4" placeholder="Опишите, что случилось с кондиционером"></textarea>
          </label>
          <button type="submit" class="btn btn--primary question-form__submit">Отправить вопрос</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>

</main>

<?php get_footer(); ?>
