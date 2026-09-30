<?php
/**
 * "Услуги" — хаб-страница (/uslugi/) с 3 карточками категорий, каждая
 * из которых ведёт на свою полноценную посадочную страницу
 * (taxonomy-service_category.php).
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
?>

<div class="container dah-shop-page">
    <?php dah_service_breadcrumb(); ?>
</div>

<main>
<section class="section">
<div class="container">
    <h1 class="page-title">Услуги</h1>

    <?php if (!empty($dah_terms)): ?>
        <ul class="dah-service-grid">
            <?php foreach ($dah_terms as $dah_term): ?>
                <li class="dah-service-card">
                    <a class="dah-service-card__link" href="<?php echo esc_url(get_term_link($dah_term)); ?>">
                        <h2 class="dah-service-card__title"><?php echo esc_html($dah_term->name); ?></h2>
                        <?php if ($dah_term->description): ?>
                            <p class="dah-service-card__excerpt"><?php echo esc_html($dah_term->description); ?></p>
                        <?php endif; ?>
                        <span class="dah-service-card__more">Подробнее →</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Разделы услуг пока не добавлены.</p>
    <?php endif; ?>
</div>
</section>
</main>

<?php get_footer(); ?>
