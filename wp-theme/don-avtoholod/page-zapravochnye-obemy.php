<?php
/**
 * Template Name: Обучение — заправочные объёмы
 * Справочник заправочных объёмов и типа масла по маркам/моделям —
 * данные из старого сайта. Список большой (2000+ строк), поэтому
 * показывается как раскрывающиеся по марке блоки с живым поиском,
 * а не одной плоской таблицей.
 */

defined('ABSPATH') || exit;

get_header();

$dah_parent_id = wp_get_post_parent_id(get_the_ID());
$dah_data_path = get_theme_file_path('/assets/data/refill-volumes.json');
$dah_refill_json = file_exists($dah_data_path) ? file_get_contents($dah_data_path) : '{}';
$dah_refill_json = str_replace('</script>', '<\/script>', $dah_refill_json);
?>

<div class="container dah-shop-page">
  <nav class="dah-breadcrumb woocommerce-breadcrumb">
    <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a> /
    <?php if ($dah_parent_id): ?>
      <a href="<?php echo esc_url(get_permalink($dah_parent_id)); ?>"><?php echo esc_html(get_the_title($dah_parent_id)); ?></a> /
    <?php endif; ?>
    <?php the_title(); ?>
  </nav>
</div>

<main>
<section class="section">
  <div class="container page-content dah-page-content--wide">
    <?php while (have_posts()): the_post(); ?>
      <h1 class="page-title"><?php the_title(); ?></h1>
      <div class="page-content__body">
        <?php the_content(); ?>
      </div>
    <?php endwhile; ?>

    <p class="dah-refill-hint">
      Найдите марку автомобиля в списке ниже или начните вводить марку/модель в поиске —
      покажем заправочный объём хладагента (в граммах) и рекомендуемое масло.
    </p>

    <div class="dah-refill" data-dah-refill>
      <input
        type="search"
        class="dah-refill__search"
        placeholder="Поиск по марке или модели, например «Camry» или «Volvo»"
        data-dah-refill-search
        autocomplete="off"
      >
      <p class="dah-refill__empty" data-dah-refill-empty hidden>Ничего не найдено — проверьте написание марки или модели.</p>
      <div class="dah-refill__list" data-dah-refill-list></div>
    </div>

    <p class="dah-refill-note">* Данные актуальны по состоянию на момент публикации и могут отличаться от спецификаций производителя для конкретного года выпуска — точный объём уточняйте при диагностике автомобиля.</p>
  </div>
</section>
</main>

<script type="application/json" id="dah-refill-data"><?php echo $dah_refill_json; ?></script>
<script>
(function () {
  var root = document.querySelector('[data-dah-refill]');
  if (!root) { return; }

  var dataEl = document.getElementById('dah-refill-data');
  var data = {};
  try { data = JSON.parse(dataEl.textContent || '{}'); } catch (e) { data = {}; }

  var list = root.querySelector('[data-dah-refill-list]');
  var search = root.querySelector('[data-dah-refill-search]');
  var emptyMsg = root.querySelector('[data-dah-refill-empty]');

  var brands = Object.keys(data);

  function escapeHtml(s) {
    var div = document.createElement('div');
    div.textContent = s;
    return div.innerHTML;
  }

  brands.forEach(function (brand) {
    var rows = data[brand];

    var details = document.createElement('details');
    details.className = 'dah-refill-brand';
    details.dataset.brand = brand.toLowerCase();

    var summary = document.createElement('summary');
    summary.innerHTML = '<span class="dah-refill-brand__name">' + escapeHtml(brand) + '</span>' +
      '<span class="dah-refill-brand__count">' + rows.length + '</span>';
    details.appendChild(summary);

    var tableWrap = document.createElement('div');
    tableWrap.className = 'dah-refill-brand__body';

    var table = document.createElement('table');
    table.className = 'dah-refill-table';
    table.innerHTML =
      '<thead><tr><th>Модель</th><th>Модификация / год</th><th>Хладагент, г</th><th>Масло</th></tr></thead>';

    var tbody = document.createElement('tbody');
    rows.forEach(function (row) {
      var tr = document.createElement('tr');
      tr.dataset.search = (row[0] + ' ' + row[1]).toLowerCase();
      tr.innerHTML =
        '<td>' + escapeHtml(row[0]) + '</td>' +
        '<td>' + escapeHtml(row[1]) + '</td>' +
        '<td>' + escapeHtml(row[2]) + '</td>' +
        '<td>' + escapeHtml(row[3] || '—') + '</td>';
      tbody.appendChild(tr);
    });
    table.appendChild(tbody);
    tableWrap.appendChild(table);
    details.appendChild(tableWrap);

    list.appendChild(details);
  });

  var allBrandEls = Array.prototype.slice.call(list.querySelectorAll('.dah-refill-brand'));

  search.addEventListener('input', function () {
    var q = search.value.trim().toLowerCase();
    var visibleCount = 0;

    allBrandEls.forEach(function (el) {
      if (!q) {
        el.hidden = false;
        el.open = false;
        el.querySelectorAll('tbody tr').forEach(function (tr) { tr.hidden = false; });
        visibleCount++;
        return;
      }

      var brandMatches = el.dataset.brand.indexOf(q) !== -1;
      var rowMatchCount = 0;

      el.querySelectorAll('tbody tr').forEach(function (tr) {
        var match = brandMatches || tr.dataset.search.indexOf(q) !== -1;
        tr.hidden = !match;
        if (match) { rowMatchCount++; }
      });

      var show = rowMatchCount > 0;
      el.hidden = !show;
      el.open = show;
      if (show) { visibleCount++; }
    });

    emptyMsg.hidden = visibleCount > 0;
  });
})();
</script>

<?php get_footer(); ?>
