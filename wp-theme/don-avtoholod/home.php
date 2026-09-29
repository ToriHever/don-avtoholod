<?php
/**
 * Список новостей (страница записей).
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

    <?php if (have_posts()): ?>
        <ul class="dah-service-grid">
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
