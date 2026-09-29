<?php
/**
 * Архив категории услуг (/uslugi-razdel/{slug}/) — та же вёрстка, что и
 * общий архив услуг (archive-service.php), WordPress не использует его
 * для страниц таксономии, поэтому нужен отдельный файл.
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

        <div class="shop-content">
            <h1 class="page-title"><?php echo esc_html(single_term_title('', false)); ?></h1>

            <?php
            $dah_service_term = get_queried_object();
            if ($dah_service_term instanceof WP_Term && $dah_service_term->description): ?>
                <p class="dah-service-archive-lead"><?php echo esc_html($dah_service_term->description); ?></p>
            <?php endif; ?>

            <?php if (have_posts()): ?>
                <ul class="dah-service-grid">
                    <?php while (have_posts()): the_post(); ?>
                        <li class="dah-service-card">
                            <a class="dah-service-card__link" href="<?php the_permalink(); ?>">
                                <?php if (has_post_thumbnail()): ?>
                                    <span class="dah-service-card__image"><?php the_post_thumbnail('medium'); ?></span>
                                <?php endif; ?>
                                <h2 class="dah-service-card__title"><?php the_title(); ?></h2>
                                <?php if (has_excerpt()): ?>
                                    <p class="dah-service-card__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                                <?php endif; ?>
                                <span class="dah-service-card__more">Подробнее →</span>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>

                <?php
                the_posts_pagination([
                    'prev_text' => '←',
                    'next_text' => '→',
                ]);
                ?>
            <?php else: ?>
                <p>Пока нет услуг в этом разделе.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</section>
</main>

<?php get_footer(); ?>
