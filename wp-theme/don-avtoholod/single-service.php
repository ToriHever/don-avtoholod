<?php
/**
 * Страница одной услуги — структура по плану "1..8 блоков + контакты":
 * первый экран, симптомы, перечень работ и цены, как проходит работа,
 * преимущества и гарантия, доверие, FAQ, контакты. Тексты заполняются
 * полями ACF (см. inc/acf-fields.php, группа "Услуга — содержимое
 * страницы") — каждый блок скрывается сам, пока поле не заполнено.
 */

defined('ABSPATH') || exit;

get_header();

while (have_posts()): the_post();
    $dah_post_id = get_the_ID();

    $dah_hero_note = get_field('service_hero_note', $dah_post_id);
    $dah_price_from = get_field('service_price_from', $dah_post_id);
    $dah_symptoms = dah_split_lines(get_field('service_symptoms', $dah_post_id));
    $dah_price_items = array_map(fn ($l) => dah_split_line($l, 2), dah_split_lines(get_field('service_price_items', $dah_post_id)));
    $dah_steps = array_map(fn ($l) => dah_split_line($l, 2), dah_split_lines(get_field('service_steps', $dah_post_id)));
    $dah_advantages = dah_split_lines(get_field('service_advantages', $dah_post_id));
    $dah_examples = array_map(fn ($l) => dah_split_line($l, 2), dah_split_lines(get_field('service_examples', $dah_post_id)));
    $dah_reviews_url = get_field('service_reviews_url', $dah_post_id);
    $dah_faq = array_map(fn ($l) => dah_split_line($l, 2), dah_split_lines(get_field('service_faq', $dah_post_id)));

    $dah_front_id = (int) get_option('page_on_front');
    $dah_contacts_address = get_field('contacts_address', $dah_front_id);
    $dah_contacts_hours = get_field('contacts_hours', $dah_front_id);
    $dah_contacts_phone1 = get_field('contacts_phone1', $dah_front_id);
    $dah_contacts_phone2 = get_field('contacts_phone2', $dah_front_id);
    $dah_contacts_map_url = get_field('contacts_map_url', $dah_front_id) ?: 'https://yandex.ru/maps/-/CTxSIHkb';
    ?>

    <main>

    <?php dah_service_schema(); ?>

    <div class="container dah-shop-page">
        <?php dah_service_breadcrumb(); ?>
    </div>

    <!-- 1. Первый экран -->
    <section class="section dah-service-hero">
        <div class="container dah-service-hero__inner">
            <h1 class="dah-service-hero__title"><?php the_title(); ?></h1>
            <?php if ($dah_hero_note): ?>
                <p class="dah-service-hero__note"><?php echo esc_html($dah_hero_note); ?></p>
            <?php endif; ?>
            <div class="dah-service-hero__actions">
                <?php if ($dah_price_from): ?>
                    <span class="dah-service-hero__price"><?php echo esc_html($dah_price_from); ?></span>
                <?php endif; ?>
                <button type="button" class="btn btn--primary" data-modal-open="dah-question-modal">Задать вопрос</button>
                <?php if ($dah_contacts_phone1): ?>
                    <a class="btn btn--secondary" href="tel:<?php echo esc_attr($dah_contacts_phone1); ?>"><?php echo esc_html($dah_contacts_phone1); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container dah-shop-page">

            <?php $dah_terms = get_the_terms($dah_post_id, 'service_category'); ?>
            <?php if (!empty($dah_terms) && !is_wp_error($dah_terms)): ?>
                <div class="dah-service-single__category">
                    Категория:
                    <a href="<?php echo esc_url(get_term_link($dah_terms[0])); ?>"><?php echo esc_html($dah_terms[0]->name); ?></a>
                </div>
            <?php endif; ?>

            <?php if (has_post_thumbnail()): ?>
                <div class="dah-service-single__image"><?php the_post_thumbnail('large'); ?></div>
            <?php endif; ?>

            <!-- 2. Симптомы и проблемы -->
            <?php if (!empty($dah_symptoms)): ?>
                <div class="dah-service-block">
                    <h2 class="dah-shop-section-title">С какими проблемами обращаются</h2>
                    <ul class="dah-service-symptoms">
                        <?php foreach ($dah_symptoms as $dah_symptom): ?>
                            <li><?php echo esc_html($dah_symptom); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- 3. Перечень работ и цены -->
            <?php if (!empty($dah_price_items)): ?>
                <div class="dah-service-block">
                    <h2 class="dah-shop-section-title">Перечень работ и цены</h2>
                    <table class="dah-service-price-table">
                        <?php foreach ($dah_price_items as [$dah_work, $dah_price]): ?>
                            <tr>
                                <td class="dah-service-price-table__work"><?php echo esc_html($dah_work); ?></td>
                                <td class="dah-service-price-table__price"><?php echo esc_html($dah_price); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>

            <!-- 4. Как проходит работа -->
            <?php if (!empty($dah_steps)): ?>
                <div class="dah-service-block">
                    <h2 class="dah-shop-section-title">Как проходит работа</h2>
                    <ol class="dah-service-steps">
                        <?php foreach ($dah_steps as $dah_i => [$dah_step_title, $dah_step_text]): ?>
                            <li class="dah-service-steps__item">
                                <span class="dah-service-steps__num"><?php echo esc_html($dah_i + 1); ?></span>
                                <span class="dah-service-steps__body">
                                    <span class="dah-service-steps__title"><?php echo esc_html($dah_step_title); ?></span>
                                    <?php if ($dah_step_text): ?>
                                        <span class="dah-service-steps__text"><?php echo esc_html($dah_step_text); ?></span>
                                    <?php endif; ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            <?php endif; ?>

            <!-- 5. Преимущества и гарантия -->
            <?php if (!empty($dah_advantages)): ?>
                <div class="dah-service-block">
                    <h2 class="dah-shop-section-title">Преимущества и гарантия</h2>
                    <ul class="dah-service-advantages">
                        <?php foreach ($dah_advantages as $dah_advantage): ?>
                            <li><?php echo esc_html($dah_advantage); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (get_the_content()): ?>
                <div class="dah-service-single__content">
                    <?php the_content(); ?>
                </div>
            <?php endif; ?>

            <!-- 6. Доверие -->
            <?php if (!empty($dah_examples) || $dah_reviews_url): ?>
                <div class="dah-service-block">
                    <h2 class="dah-shop-section-title">Примеры выполненных работ</h2>
                    <?php if (!empty($dah_examples)): ?>
                        <ul class="dah-service-examples">
                            <?php foreach ($dah_examples as [$dah_brand, $dah_note]): ?>
                                <li>
                                    <span class="dah-service-examples__brand"><?php echo esc_html($dah_brand); ?></span>
                                    <?php if ($dah_note): ?>
                                        <span class="dah-service-examples__note"><?php echo esc_html($dah_note); ?></span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <?php if ($dah_reviews_url): ?>
                        <a class="btn btn--secondary" href="<?php echo esc_url($dah_reviews_url); ?>" target="_blank" rel="noopener">Читать отзывы</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="dah-service-single__actions">
                <button type="button" class="btn btn--primary" data-modal-open="dah-question-modal">Задать вопрос</button>
            </div>

        </div>
    </section>

    <!-- 7. FAQ -->
    <?php if (!empty($dah_faq)): ?>
        <section class="section section--alt">
            <div class="container dah-shop-page">
                <h2 class="dah-shop-section-title">Частые вопросы</h2>
                <div class="faq__list">
                    <?php foreach ($dah_faq as [$dah_question, $dah_answer]): ?>
                        <details class="faq-item">
                            <summary><?php echo esc_html($dah_question); ?></summary>
                            <p><?php echo esc_html($dah_answer); ?></p>
                        </details>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- 8. Контакты -->
    <section class="section dah-service-contacts">
        <div class="container dah-service-contacts__inner">
            <div>
                <h2 class="dah-shop-section-title">Контакты</h2>
                <ul class="dah-service-contacts__list">
                    <?php if ($dah_contacts_address): ?>
                        <li><a href="<?php echo esc_url($dah_contacts_map_url); ?>" target="_blank" rel="noopener"><?php echo esc_html($dah_contacts_address); ?></a></li>
                    <?php endif; ?>
                    <?php if ($dah_contacts_hours): ?>
                        <li><?php echo esc_html($dah_contacts_hours); ?></li>
                    <?php endif; ?>
                </ul>
                <div class="dah-service-contacts__actions">
                    <?php if ($dah_contacts_phone1): ?>
                        <a class="btn btn--primary" href="tel:<?php echo esc_attr($dah_contacts_phone1); ?>"><?php echo esc_html($dah_contacts_phone1); ?></a>
                    <?php endif; ?>
                    <?php if ($dah_contacts_phone2): ?>
                        <a class="btn btn--secondary" href="tel:<?php echo esc_attr($dah_contacts_phone2); ?>"><?php echo esc_html($dah_contacts_phone2); ?></a>
                    <?php endif; ?>
                    <button type="button" class="btn btn--secondary" data-modal-open="dah-question-modal">Записаться</button>
                </div>
            </div>
        </div>
    </section>

    </main>

    <?php
endwhile;

get_footer();
