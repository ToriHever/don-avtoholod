<?php
/**
 * Страница «Не найдено» — хлебные крошки по сегментам адреса, чтобы вернуться на уровень выше.
 */
get_header();

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$segments = array_filter(explode('/', $path));
$crumbs = [['label' => 'Главная', 'url' => home_url('/')]];
$accumulated = '';
foreach ($segments as $segment) {
    $accumulated .= '/' . $segment;
    $crumbs[] = [
        'label' => urldecode($segment),
        'url' => home_url($accumulated . '/'),
    ];
}
?>

<main>
  <section class="section">
    <div class="container page-content">
      <nav class="dah-breadcrumb woocommerce-breadcrumb">
        <?php foreach ($crumbs as $i => $crumb): ?>
          <?php if ($i > 0): ?> / <?php endif; ?>
          <?php if ($i === count($crumbs) - 1): ?>
            <?php echo esc_html($crumb['label']); ?>
          <?php else: ?>
            <a href="<?php echo esc_url($crumb['url']); ?>"><?php echo esc_html($crumb['label']); ?></a>
          <?php endif; ?>
        <?php endforeach; ?>
      </nav>

      <h1>Страница не найдена</h1>
      <p>Такой страницы больше нет, либо адрес указан неверно. Поднимитесь на уровень выше по ссылкам сверху или начните с <a href="<?php echo esc_url(home_url('/')); ?>">главной страницы</a>.</p>
    </div>
  </section>
</main>

<?php get_footer(); ?>
