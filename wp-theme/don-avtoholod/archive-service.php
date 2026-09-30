<?php
/**
 * "Услуги" — хаб-страница (/uslugi/): крупный заголовок + сетка карточек
 * категорий с цветной иллюстрацией сверху (как в референсе), каждая
 * ведёт на свою посадочную страницу (taxonomy-service_category.php).
 */

defined('ABSPATH') || exit;

get_header();

$dah_terms = get_terms([
    'taxonomy' => 'service_category',
    'hide_empty' => false,
    'orderby' => 'term_order',
]);
if (is_wp_error($dah_terms)) {
    $dah_terms = [];
}

$dah_front_id = (int) get_option('page_on_front');
$dah_lead = get_field('services_lead', $dah_front_id);

// Иконка для каждой карточки подбирается по ключевому слову в названии
// категории — так новые категории тоже получат разумную иконку по умолчанию.
function dah_services_hub_icon(string $name): string {
    $name = mb_strtolower($name);
    if (str_contains($name, 'отопит')) {
        return '<path d="M12 3c1.5 3 3 4.8 3 7a3 3 0 1 1-6 0c0-.6.15-1.1.4-1.6-.1 1 .2 1.8.9 2.3a2 2 0 1 0 2.9-2.4C11.7 6.8 11.6 5 12 3Z"/>';
    }
    if (str_contains($name, 'установ') || str_contains($name, 'оборудован')) {
        return '<path d="m14.7 6.3 3 3-8.4 8.4-3.6.6.6-3.6 8.4-8.4Z"/><path d="M17 3.5 20.5 7 19 8.5l-3.5-3.5Z"/>';
    }
    // по умолчанию — диагностика и ремонт систем климата
    return '<path d="M12 2v20M4.9 4.9l14.2 14.2M2 12h20M4.9 19.1 19.1 4.9"/><circle cx="12" cy="12" r="3"/>';
}
?>

<div class="container dah-shop-page">
    <?php dah_service_breadcrumb(); ?>
</div>

<main>
<section class="section dah-services-hub-hero">
    <div class="container">
        <h1 class="page-title">Услуги</h1>
        <?php if ($dah_lead): ?>
            <p class="section__lead"><?php echo esc_html($dah_lead); ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="section">
<div class="container">
    <h2 class="dah-shop-section-title">Категории услуг</h2>

    <?php if (!empty($dah_terms)): ?>
        <div class="dah-services-hub-grid">
            <?php foreach ($dah_terms as $dah_term):
                $dah_note = get_field('service_hero_note', $dah_term) ?: $dah_term->description;
            ?>
                <a class="dah-services-hub-card" href="<?php echo esc_url(get_term_link($dah_term)); ?>">
                    <span class="dah-services-hub-card__banner">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><?php echo dah_services_hub_icon($dah_term->name); ?></svg>
                    </span>
                    <span class="dah-services-hub-card__body">
                        <span class="dah-services-hub-card__title"><?php echo esc_html($dah_term->name); ?></span>
                        <?php if ($dah_note): ?>
                            <span class="dah-services-hub-card__text"><?php echo esc_html($dah_note); ?></span>
                        <?php endif; ?>
                        <span class="dah-services-hub-card__more">Подробнее →</span>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Разделы услуг пока не добавлены.</p>
    <?php endif; ?>
</div>
</section>
</main>

<?php get_footer(); ?>
