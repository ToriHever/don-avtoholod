<?php
/**
 * Шаблон обычной страницы (например "Доставка и оплата", "Вакансии").
 */
get_header();
?>

<main>
  <section class="section">
    <div class="container page-content">
      <a href="/" class="page-content__back">← На главную</a>
      <?php while (have_posts()): the_post(); ?>
        <h1><?php the_title(); ?></h1>
        <div class="page-content__body"><?php the_content(); ?></div>
      <?php endwhile; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
