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

<?php
$dah_parent_id = wp_get_post_parent_id(get_queried_object_id());
$dah_ancestors = array_reverse(get_post_ancestors(get_queried_object_id()));
?>

<div class="container dah-shop-page">
  <nav class="dah-breadcrumb woocommerce-breadcrumb">
    <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a> /
    <?php foreach ($dah_ancestors as $dah_ancestor_id): ?>
      <a href="<?php echo esc_url(get_permalink($dah_ancestor_id)); ?>"><?php echo esc_html(get_the_title($dah_ancestor_id)); ?></a> /
    <?php endforeach; ?>
    <?php the_title(); ?>
  </nav>
</div>

<main>
  <section class="section">
    <div class="container page-content<?php echo $dah_parent_id ? ' dah-page-content--wide' : ''; ?>">
      <?php while (have_posts()): the_post(); ?>
        <h1><?php the_title(); ?></h1>
        <div class="page-content__body"><?php the_content(); ?></div>
      <?php endwhile; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
