<?php
/**
 * Список новостей (страница записей). Первые 5 постов — в виде
 * магазинной "витрины" (1 крупная + 2 мелкие + 2 средние карточки),
 * остальные — обычной сеткой.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main>
<section class="section">
<div class="container page-content dah-shop-page">
    <nav class="dah-breadcrumb woocommerce-breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a> / Новости
    </nav>

    <h1 class="page-title">Новости</h1>

    <?php if (have_posts()):
        $dah_post_index = 0;
        $dah_hero_posts = [];
        while (have_posts() && $dah_post_index < 5) {
            the_post();
            $dah_hero_posts[] = get_the_ID();
            $dah_post_index++;
        }
        ?>

        <?php if (!empty($dah_hero_posts)): ?>
            <div class="dah-news-hero-grid">
                <?php
                $dah_featured = $dah_hero_posts[0];
                $dah_side = array_slice($dah_hero_posts, 1, 2);
                $dah_wide = array_slice($dah_hero_posts, 3, 2);
                ?>

                <?php setup_postdata(get_post($dah_featured)); ?>
                <a class="dah-news-card dah-news-card--featured" href="<?php the_permalink(); ?>">
                    <span class="dah-news-card__bg">
                        <?php echo has_post_thumbnail() ? get_the_post_thumbnail(null, 'large') : ''; ?>
                    </span>
                    <span class="dah-news-card__overlay">
                        <?php $dah_cats = get_the_category(); if (!empty($dah_cats)): ?>
                            <span class="dah-news-tag"><?php echo esc_html($dah_cats[0]->name); ?></span>
                        <?php endif; ?>
                        <span class="dah-news-card__title dah-news-card__title--lg"><?php the_title(); ?></span>
                        <?php dah_news_card_meta(); ?>
                    </span>
                </a>

                <?php if (!empty($dah_side)): ?>
                    <div class="dah-news-hero-side">
                        <?php foreach ($dah_side as $pid): setup_postdata(get_post($pid)); ?>
                            <a class="dah-news-card dah-news-card--small" href="<?php the_permalink(); ?>">
                                <span class="dah-news-card__thumb">
                                    <?php echo has_post_thumbnail() ? get_the_post_thumbnail(null, 'thumbnail') : ''; ?>
                                </span>
                                <span class="dah-news-card__body">
                                    <?php $dah_cats = get_the_category(); if (!empty($dah_cats)): ?>
                                        <span class="dah-news-tag dah-news-tag--muted"><?php echo esc_html($dah_cats[0]->name); ?></span>
                                    <?php endif; ?>
                                    <span class="dah-news-card__title"><?php the_title(); ?></span>
                                    <?php dah_news_card_meta(); ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($dah_wide)): ?>
                <div class="dah-news-row-grid">
                    <?php foreach ($dah_wide as $pid): setup_postdata(get_post($pid)); ?>
                        <a class="dah-news-card dah-news-card--medium" href="<?php the_permalink(); ?>">
                            <?php $dah_cats = get_the_category(); if (!empty($dah_cats)): ?>
                                <span class="dah-news-tag dah-news-tag--muted"><?php echo esc_html($dah_cats[0]->name); ?></span>
                            <?php endif; ?>
                            <span class="dah-news-card__title"><?php the_title(); ?></span>
                            <?php if (has_post_thumbnail()): ?>
                                <span class="dah-news-card__thumb dah-news-card__thumb--wide"><?php the_post_thumbnail('medium_large'); ?></span>
                            <?php endif; ?>
                            <p class="dah-news-card__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                            <?php dah_news_card_meta(); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>

        <?php if (have_posts()): ?>
            <ul class="dah-service-grid dah-news-rest-grid">
                <?php while (have_posts()): the_post(); ?>
                    <li class="dah-service-card">
                        <a class="dah-service-card__link" href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()): ?>
                                <span class="dah-service-card__image"><?php the_post_thumbnail('medium'); ?></span>
                            <?php endif; ?>
                            <span class="dah-news-card__date"><?php echo esc_html(get_the_date()); ?></span>
                            <h2 class="dah-service-card__title"><?php the_title(); ?></h2>
                            <p class="dah-service-card__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                            <span class="dah-service-card__more">Читать →</span>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>

        <?php
        the_posts_pagination([
            'prev_text' => '←',
            'next_text' => '→',
        ]);
        ?>
    <?php else: ?>
        <p>Пока нет новостей.</p>
    <?php endif; ?>
</div>
</section>
</main>

<?php get_footer(); ?>
