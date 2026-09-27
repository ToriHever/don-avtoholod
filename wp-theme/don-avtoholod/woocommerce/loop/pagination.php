<?php
/**
 * Пагинация каталога — через ?paged=N, а не отдельными адресами /page/N/.
 */

defined('ABSPATH') || exit;

if (wc_get_loop_prop('is_paginated') && wc_get_loop_prop('total_pages') > 1) {
    $current = max(1, get_query_var('paged'));
    ?>
    <nav class="woocommerce-pagination">
        <?php
        echo wp_kses_post(
            paginate_links(
                apply_filters(
                    'woocommerce_pagination_args',
                    [
                        'base' => esc_url_raw(add_query_arg('paged', '%#%')),
                        'format' => '',
                        'add_args' => false,
                        'current' => $current,
                        'total' => wc_get_loop_prop('total_pages'),
                        'prev_text' => '←',
                        'next_text' => '→',
                        'type' => 'list',
                        'end_size' => 3,
                        'mid_size' => 3,
                    ]
                )
            )
        );
        ?>
    </nav>
    <?php
}
