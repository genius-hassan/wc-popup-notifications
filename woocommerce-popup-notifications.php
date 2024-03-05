<?php
/**
 * Plugin Name: WooCommerce Popup Notifications
 * Plugin URI:  https://brandbees.net
 * Description: Displays an add to cart notification in a popup.
 * Version:     1.0.0
 * Author:      Hassan Ejaz
 * Author URI:  https://brandbees.net
 * Text Domain: woocommerce-popup-notifications
 * Domain Path: /languages
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-popup-notifications.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-popup-notifications-admin.php';

    function run_wc_popup_notifications() {
		new WC_Popup_Notifications();
		new WC_Popup_Notifications_Admin();
	}
	add_action('plugins_loaded', 'run_wc_popup_notifications');


}

/*
add_filter('wc_popup_notifications_close_after_seconds', function($seconds) {
    return 10; // Change the notification duration to 10 seconds
});
*/