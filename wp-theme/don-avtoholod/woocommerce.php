<?php
/**
 * Обёртка WooCommerce (карточка товара, корзина, оформление заявки).
 *
 * И главная страница магазина (это статическая WP-страница, а не архив
 * таксономии), и страницы категорий идут через этот файл, минуя
 * woocommerce/archive-product.php напрямую — так уже устроен WooCommerce
 * при наличии woocommerce.php в теме. Чтобы там показывалась наша вёрстка
 * (сайдбар, сетка разделов, хаб-страницы категорий), а не встроенный
 * классический цикл woocommerce_content(), делегируем рендер обоих
 * случаев в archive-product.php напрямую — он сам умеет их различать.
 */
if (is_shop() || is_product_category()) {
    require get_theme_file_path('/woocommerce/archive-product.php');
    return;
}

get_header();
?>

<main>
  <section class="section">
    <div class="container page-content dah-shop-page">
      <?php if (is_product()): ?>
        <?php do_action('woocommerce_before_main_content'); ?>
        <div class="shop-layout">
          <?php dah_wc_category_sidebar(); ?>
          <div class="shop-content">
            <?php woocommerce_content(); ?>
          </div>
        </div>
        <?php do_action('woocommerce_after_main_content'); ?>
      <?php else: ?>
        <?php
        /**
         * woocommerce_content() написана только для магазина/архива и
         * одиночного товара — для остальных WC-страниц (корзина, заявка,
         * личный кабинет) она не знает, что рисовать, и ошибочно выводит
         * заголовок магазина с пустым содержимым. Для них нужен обычный
         * контент страницы (там лежит шорткод [woocommerce_cart] и т.п.),
         * как и в стандартном page.php.
         */
        ?>
        <?php do_action('woocommerce_before_main_content'); ?>
        <?php while (have_posts()): the_post(); ?>
          <?php the_content(); ?>
        <?php endwhile; ?>
        <?php do_action('woocommerce_after_main_content'); ?>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
