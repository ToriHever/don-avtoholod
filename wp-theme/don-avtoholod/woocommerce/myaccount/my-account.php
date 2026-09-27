<?php
/**
 * Обёртка "Мой аккаунт" — та же сетка сайдбар+контент, что и в каталоге,
 * только сайдбар — это стандартное меню аккаунта WooCommerce.
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_my_account', $current_user);
?>

<?php if (is_user_logged_in()): ?>
    <div class="dah-account-layout">
        <?php do_action('woocommerce_before_account_navigation'); ?>
        <?php wc_get_template('myaccount/navigation.php'); ?>
        <?php do_action('woocommerce_after_account_navigation'); ?>

        <div class="woocommerce-MyAccount-content dah-account-content">
            <?php do_action('woocommerce_account_content'); ?>
        </div>
    </div>
<?php else: ?>
    <?php do_action('woocommerce_account_content'); ?>
<?php endif; ?>

<?php do_action('woocommerce_after_my_account'); ?>
