<?php
/**
 * Обёртка WooCommerce (карточка товара, корзина, оформление заявки).
 *
 * Главная страница магазина — это статическая WP-страница (а не архив
 * таксономии), поэтому WordPress подхватывает именно этот файл, минуя
 * woocommerce/archive-product.php. Чтобы там тоже показывалась наша сетка
 * разделов, а не встроенный классический цикл woocommerce_content(),
 * делегируем рендер в archive-product.php напрямую.
 */
if (is_shop()) {
    require get_theme_file_path('/woocommerce/archive-product.php');
    return;
}

get_header();
?>

<main>
  <section class="section">
    <div class="container page-content dah-shop-page">
      <?php do_action('woocommerce_before_main_content'); ?>
      <?php woocommerce_content(); ?>
      <?php do_action('woocommerce_after_main_content'); ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
