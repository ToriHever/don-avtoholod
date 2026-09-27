<?php
/**
 * Каталог товаров — список категории или магазина целиком.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main>
<section class="section">
<div class="container page-content dah-shop-page">
<?php dah_wc_breadcrumb(); ?>

<?php if (!empty($_GET['filter_marka'])): ?>
    <pre style="background:#fee;border:2px solid red;padding:12px;white-space:pre-wrap;font-size:12px;overflow:auto;max-height:400px;">DEBUG filter_marka=<?php echo esc_html($_GET['filter_marka']); ?>
tax_query object: <?php echo esc_html(print_r($GLOBALS['wp_query']->tax_query->queries ?? 'NONE', true)); ?>
SQL request: <?php echo esc_html($GLOBALS['wp_query']->request ?? 'NONE'); ?>
found_posts: <?php echo esc_html($GLOBALS['wp_query']->found_posts ?? 'n/a'); ?></pre>
<?php endif; ?>

<div class="shop-layout">
    <?php dah_wc_category_sidebar(); ?>

    <div class="shop-content">
        <div class="dah-category-hub">
            <?php dah_wc_category_hub_header(); ?>
            <div class="dah-category-hub__body">
                <?php if (apply_filters('woocommerce_show_page_title', true)): ?>
                    <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
                <?php endif; ?>

                <?php do_action('woocommerce_archive_description'); ?>
            </div>
        </div>

        <?php if (!is_shop()): ?>
            <?php woocommerce_result_count(); ?>
            <div class="dah-catalog-filters">
                <?php dah_wc_brand_filter(); ?>
                <?php woocommerce_catalog_ordering(); ?>
            </div>
        <?php endif; ?>

        <?php dah_wc_category_subcategories(); ?>

        <?php if (is_shop()): ?>
            <?php dah_wc_shop_category_grid(); ?>
            <?php dah_wc_shop_featured_products(); ?>
        <?php else: ?>
            <?php if (woocommerce_product_loop()): ?>
                <?php do_action('woocommerce_before_shop_loop'); ?>

                <?php woocommerce_product_loop_start(); ?>

                <?php if (wc_get_loop_prop('total')): ?>
                    <?php while (have_posts()): the_post(); ?>
                        <?php do_action('woocommerce_shop_loop'); ?>
                        <?php wc_get_template_part('content', 'product'); ?>
                    <?php endwhile; ?>
                <?php endif; ?>

                <?php woocommerce_product_loop_end(); ?>

                <?php do_action('woocommerce_after_shop_loop'); ?>
            <?php else: ?>
                <?php do_action('woocommerce_no_products_found'); ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

</div>
</section>
</main>

<?php
get_footer();
