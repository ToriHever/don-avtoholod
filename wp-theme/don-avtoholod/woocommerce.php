<?php
/**
 * Обёртка WooCommerce (каталог, карточка товара, корзина, оформление заявки).
 */
get_header();
?>

<main>
  <section class="section">
    <div class="container page-content woocommerce-page">
      <a href="/" class="page-content__back">← На главную</a>
      <?php woocommerce_content(); ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
