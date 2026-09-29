<?php
/**
 * Страница одной услуги.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main>
<section class="section">
<div class="container page-content dah-shop-page">
    <?php dah_service_breadcrumb(); ?>

    <div class="shop-layout">
        <?php dah_service_category_sidebar(); ?>

        <div class="shop-content dah-service-single">
            <?php while (have_posts()): the_post(); ?>
                <h1 class="page-title"><?php the_title(); ?></h1>

                <?php $dah_terms = get_the_terms(get_the_ID(), 'service_category'); ?>
                <?php if (!empty($dah_terms) && !is_wp_error($dah_terms)): ?>
                    <div class="dah-service-single__category">
                        Категория:
                        <a href="<?php echo esc_url(get_term_link($dah_terms[0])); ?>"><?php echo esc_html($dah_terms[0]->name); ?></a>
                    </div>
                <?php endif; ?>

                <?php if (has_post_thumbnail()): ?>
                    <div class="dah-service-single__image"><?php the_post_thumbnail('large'); ?></div>
                <?php endif; ?>

                <div class="dah-service-single__content">
                    <?php the_content(); ?>
                </div>

                <div class="dah-service-single__actions">
                    <button type="button" class="btn btn--primary" data-modal-open="dah-question-modal">Задать вопрос</button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>
</section>
</main>

<?php get_footer(); ?>
