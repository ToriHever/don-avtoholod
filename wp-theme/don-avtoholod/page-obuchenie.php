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
?>

<main>
<section class="section">
<div class="container page-content dah-shop-page">
    <nav class="dah-breadcrumb woocommerce-breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a> / Обучение
    </nav>

    <?php while (have_posts()): the_post(); ?>
        <h1 class="page-title"><?php the_title(); ?></h1>

        <div class="dah-service-single__content">
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>

    <?php if (!empty($dah_children)): ?>
        <h2 class="dah-shop-section-title">Разделы обучения</h2>
        <ul class="dah-service-grid">
            <?php foreach ($dah_children as $child): ?>
                <li class="dah-service-card">
                    <a class="dah-service-card__link" href="<?php echo esc_url(get_permalink($child)); ?>">
                        <h2 class="dah-service-card__title"><?php echo esc_html(get_the_title($child)); ?></h2>
                        <?php if ($child->post_excerpt): ?>
                            <p class="dah-service-card__excerpt"><?php echo esc_html($child->post_excerpt); ?></p>
                        <?php endif; ?>
                        <span class="dah-service-card__more">Подробнее →</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <div class="dah-service-single__actions">
        <button type="button" class="btn btn--primary" data-modal-open="dah-question-modal">Записаться на обучение</button>
    </div>
</div>
</section>
</main>

<?php get_footer(); ?>
