<?php
/**
 * Отдельная новость: небольшая обложка сверху, дальше — статья и
 * прилипающее (floating) содержание справа от текста.
 */

defined('ABSPATH') || exit;

get_header();

/**
 * Достаёт из HTML-контента заголовки h2/h3, проставляет им id
 * (для якорных ссылок) и возвращает [контент_с_id, список_заголовков].
 */
function dah_news_toc(string $content): array {
    $headings = [];
    $used_slugs = [];

    $content = preg_replace_callback(
        '/<h([23])([^>]*)>(.*?)<\/h\1>/is',
        function ($matches) use (&$headings, &$used_slugs) {
            $level = (int) $matches[1];
            $attrs = $matches[2];
            $text = trim(wp_strip_all_tags($matches[3]));

            if ($text === '') {
                return $matches[0];
            }

            $slug = sanitize_title($text);
            if ($slug === '') {
                $slug = 'toc';
            }
            $base_slug = $slug;
            $i = 2;
            while (isset($used_slugs[$slug])) {
                $slug = $base_slug . '-' . $i;
                $i++;
            }
            $used_slugs[$slug] = true;

            $headings[] = [
                'id' => $slug,
                'text' => $text,
                'level' => $level,
            ];

            if (!preg_match('/\sid=/i', $attrs)) {
                $attrs .= ' id="' . esc_attr($slug) . '"';
            }

            return '<h' . $level . $attrs . '>' . $matches[3] . '</h' . $level . '>';
        },
        $content
    );

    return [$content, $headings];
}
?>

<main>
<section class="section">
<div class="container page-content dah-shop-page">
    <?php while (have_posts()): the_post(); ?>
        <a href="<?php echo esc_url(get_option('page_for_posts') ? get_permalink(get_option('page_for_posts')) : home_url('/')); ?>" class="page-content__back">← Все новости</a>

        <header class="dah-news-single__header">
            <?php $dah_cats = get_the_category(); if (!empty($dah_cats)): ?>
                <span class="dah-news-single__tag"><?php echo esc_html($dah_cats[0]->name); ?></span>
            <?php endif; ?>

            <h1 class="dah-news-single__title"><?php the_title(); ?></h1>

            <div class="dah-news-single__meta">
                <span class="dah-news-single__author-avatar"><?php echo get_avatar(get_the_author_meta('ID'), 72); ?></span>
                <span class="dah-news-single__author-name"><?php echo esc_html(get_the_author()); ?></span>
                <span class="dah-news-meta__dot">•</span>
                <span><?php echo esc_html(get_the_date()); ?></span>
                <span class="dah-news-meta__dot">•</span>
                <span><?php echo esc_html(dah_reading_time(get_the_content())); ?></span>
            </div>
        </header>

        <?php
        [$dah_content, $dah_headings] = dah_news_toc(apply_filters('the_content', get_the_content()));
        ?>

        <?php if (has_post_thumbnail()): ?>
            <span class="dah-news-single__cover"><?php the_post_thumbnail('large'); ?></span>
        <?php endif; ?>

        <div class="dah-news-single">
            <div class="dah-news-single__content">
                <?php echo $dah_content; ?>
            </div>

            <?php if (!empty($dah_headings)): ?>
                <aside class="dah-news-single__toc">
                    <p class="dah-news-single__toc-title">Содержание</p>
                    <ul class="dah-news-single__toc-list">
                        <?php foreach ($dah_headings as $dah_heading): ?>
                            <li<?php echo $dah_heading['level'] === 3 ? ' class="dah-news-single__toc-sublist"' : ''; ?>>
                                <a href="#<?php echo esc_attr($dah_heading['id']); ?>"><?php echo esc_html($dah_heading['text']); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </aside>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>
</section>
</main>

<?php get_footer(); ?>
