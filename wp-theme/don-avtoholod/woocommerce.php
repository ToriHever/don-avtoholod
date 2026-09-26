<?php
/**
 * Обёртка WooCommerce (каталог, карточка товара, корзина, оформление заявки).
 */
get_header();
?>

<main>
  <section class="section">
    <div class="container page-content woocommerce-page">
      <?php do_action('woocommerce_before_main_content'); ?>
      <?php woocommerce_content(); ?>
      <?php do_action('woocommerce_after_main_content'); ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
