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
$dah_back_url = $dah_parent_id ? get_permalink($dah_parent_id) : home_url('/');
$dah_back_label = $dah_parent_id ? '← ' . get_the_title($dah_parent_id) : '← На главную';
?>

<main>
  <section class="section">
    <div class="container page-content<?php echo $dah_parent_id ? ' dah-page-content--wide' : ''; ?>">
      <a href="<?php echo esc_url($dah_back_url); ?>" class="page-content__back"><?php echo esc_html($dah_back_label); ?></a>
      <?php while (have_posts()): the_post(); ?>
        <h1><?php the_title(); ?></h1>
        <div class="page-content__body"><?php the_content(); ?></div>
      <?php endwhile; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
