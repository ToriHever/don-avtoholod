<?php
/**
 * Список новостей (страница записей). Карточки оформлены "таблицей" —
 * тонкие линии-разделители между ячейками, крупная дата, компактная
 * картинка (или совсем без неё — карточка от этого не разваливается).
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
        <div class="dah-news-table-grid">
            <?php while (have_posts()): the_post(); ?>
                <a class="dah-news-tablecard" href="<?php the_permalink(); ?>">
                    <span class="dah-news-tablecard__title"><?php the_title(); ?></span>

                    <?php $dah_cats = get_the_category(); if (!empty($dah_cats)): ?>
                        <span class="dah-news-tablecard__tag">
                            <span class="dah-news-tablecard__dot"></span><?php echo esc_html($dah_cats[0]->name); ?>
                        </span>
                    <?php endif; ?>

                    <span class="dah-news-tablecard__date"><?php echo esc_html(get_the_date('d.m')); ?></span>

                    <?php if (has_post_thumbnail()): ?>
                        <span class="dah-news-tablecard__thumb"><?php the_post_thumbnail('medium'); ?></span>
                    <?php endif; ?>
                </a>
            <?php endwhile; ?>
        </div>

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
