<?php
/**
 * Запасной шаблон (используется, если не подошёл ни один другой).
 */
get_header();
?>

<main>
  <section class="section">
    <div class="container page-content">
      <a href="/" class="page-content__back">← На главную</a>
      <?php if (have_posts()): while (have_posts()): the_post(); ?>
        <h1><?php the_title(); ?></h1>
        <div class="page-content__body"><?php the_content(); ?></div>
      <?php endwhile; else: ?>
        <h1>Страница не найдена</h1>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
