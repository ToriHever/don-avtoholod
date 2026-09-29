<?php
/**
 * Отдельная новость.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main>
<section class="section">
<div class="container page-content dah-shop-page">
    <?php while (have_posts()): the_post(); ?>
        <nav class="dah-breadcrumb woocommerce-breadcrumb">
            <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a> /
            <?php if (get_option('page_for_posts')): ?>
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>">Новости</a> /
            <?php endif; ?>
            <?php the_title(); ?>
        </nav>

        <article class="dah-service-single">
            <h1 class="page-title"><?php the_title(); ?></h1>
            <p class="dah-news-card__date"><?php echo esc_html(get_the_date()); ?></p>

            <?php if (has_post_thumbnail()): ?>
                <div class="dah-service-single__image"><?php the_post_thumbnail('large'); ?></div>
            <?php endif; ?>

            <div class="dah-service-single__content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>
</section>
</main>

<?php get_footer(); ?>
