<?php
/**
 * Plugin Name: Gift Wrap for WooCommerce
 * Description: Adds an optional "Gift wrap this order (+Rs. 99)" checkbox to the cart. The fee is a separate cart line item, persists in the session, and never duplicates on cart updates.
 * Version:     1.0.0
 * Author:      VIPUL
 * Requires Plugins: woocommerce
 * License:     GPL2
 */

if (!defined('ABSPATH')) {
    exit; // No direct access.
}

// Fee amount and the single source-of-truth session key.
define('GW_FEE_AMOUNT', 99);
define('GW_SESSION_KEY', 'gift_wrap');

/**
 * Is gift wrap currently enabled for this session?
 * The session stores a boolean ("yes"/"no") only — never a running total —
 * so the fee can never accumulate to 198 / 297 on repeated cart updates.
 */
function gw_is_enabled() {
    if (function_exists('WC') && WC()->session) {
        return WC()->session->get(GW_SESSION_KEY) === 'yes';
    }
    return false;
}

/**
 * Render the checkbox on the cart page.
 * Hooked into `woocommerce_after_cart_table`, which is INSIDE the cart <form>,
 * so the hidden marker + checkbox are also submitted on a native "Update cart".
 */
function gw_render_checkbox() {
    $checked = gw_is_enabled() ? ' checked="checked"' : '';
    ?>
    <div class="gw-gift-wrap" style="margin:15px 0;padding:12px 15px;border:1px solid #e0e0e0;border-radius:6px;">
        <label style="display:flex;align-items:center;gap:8px;margin:0;cursor:pointer;">
            <input type="checkbox" id="gw_gift_wrap" name="gw_gift_wrap" value="yes"<?php echo $checked; ?> />
            <span><?php esc_html_e('Gift wrap this order (+Rs. 99)', 'gift-wrap'); ?></span>
        </label>
        <?php // Marker so the no-JS fallback can tell our form was submitted. ?>
        <input type="hidden" name="gw_present" value="1" />
    </div>
    <?php
}
add_action('woocommerce_after_cart_table', 'gw_render_checkbox');

/**
 * Add the fee. This is the ONLY place add_fee() is ever called, and it derives
 * purely from the boolean session flag, so each recalculation yields exactly one
 * Rs. 99 line item.
 */
function gw_add_fee($cart) {
    // Don't run in the admin (except during AJAX, where the frontend cart lives).
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }
    if (gw_is_enabled()) {
        // taxable = false -> a flat fee, not added to product price.
        $cart->add_fee(__('Gift Wrap', 'gift-wrap'), GW_FEE_AMOUNT, false);
    }
}
add_action('woocommerce_cart_calculate_fees', 'gw_add_fee');

/**
 * Enqueue the toggle script, cart page only.
 */
function gw_enqueue_assets() {
    if (!function_exists('is_cart') || !is_cart()) {
        return;
    }
    wp_enqueue_script(
        'gw-gift-wrap',
        plugins_url('assets/js/gift-wrap.js', __FILE__),
        array('jquery'),
        '1.0.0',
        true
    );
    wp_localize_script('gw-gift-wrap', 'gwData', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('gw_toggle_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'gw_enqueue_assets');

/**
 * AJAX: instant toggle. Writes the boolean to the session, recalculates totals,
 * and returns the fresh cart-totals HTML so the JS can swap it in without reload.
 */
function gw_ajax_toggle() {
    check_ajax_referer('gw_toggle_nonce', 'nonce');

    $enabled = (isset($_POST['gift_wrap']) && $_POST['gift_wrap'] === 'yes') ? 'yes' : 'no';

    if (WC()->session) {
        WC()->session->set(GW_SESSION_KEY, $enabled);
    }
    if (WC()->cart) {
        WC()->cart->calculate_totals();
    }

    ob_start();
    woocommerce_cart_totals();
    $html = ob_get_clean();

    wp_send_json_success(array(
        'html'    => $html,
        'enabled' => $enabled,
    ));
}
add_action('wp_ajax_gw_toggle', 'gw_ajax_toggle');
add_action('wp_ajax_nopriv_gw_toggle', 'gw_ajax_toggle');

/**
 * No-JS fallback: when the cart form is posted (Update cart / Apply coupon /
 * quantity change), sync the session from the submitted checkbox state.
 * Runs before WooCommerce's own cart handler (priority 20) so the recalculated
 * totals on the reloaded page already reflect the correct state.
 * The `gw_present` marker guarantees we only touch the flag when OUR form was
 * actually submitted — never on a plain GET page load.
 */
function gw_sync_from_post() {
    if (!function_exists('WC') || !WC()->session) {
        return;
    }
    if (!isset($_POST['gw_present'])) {
        return;
    }
    $enabled = (isset($_POST['gw_gift_wrap']) && $_POST['gw_gift_wrap'] === 'yes') ? 'yes' : 'no';
    WC()->session->set(GW_SESSION_KEY, $enabled);
}
add_action('wp_loaded', 'gw_sync_from_post', 5);

/**
 * Reset the flag once the cart is emptied (which WooCommerce does automatically
 * after a successful checkout) so gift wrap never leaks into the next order.
 */
function gw_reset_flag() {
    if (function_exists('WC') && WC()->session) {
        WC()->session->set(GW_SESSION_KEY, 'no');
    }
}
add_action('woocommerce_cart_emptied', 'gw_reset_flag');
