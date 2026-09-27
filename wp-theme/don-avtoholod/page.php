<?php
/**
 * Шаблон обычной страницы (например "Доставка и оплата", "Вакансии").
 *
 * Корзина, оформление заявки и личный кабинет — это тоже обычные
 * WP-страницы (как и "Магазин" ранее), и WordPress по умолчанию находит
 * именно этот файл раньше, чем woocommerce.php. Из-за этого они попадали
 * в узкий контейнер ".page-content" (780px), рассчитанный на текстовые
 * страницы вроде "Доставка и оплата". Отдаём их woocommerce.php напрямую.
 */
if (function_exists('is_cart') && (is_cart() || is_checkout() || is_account_page())) {
    require get_theme_file_path('/woocommerce.php');
    return;
}

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
