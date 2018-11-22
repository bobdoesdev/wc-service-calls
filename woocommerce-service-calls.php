<?php
/*
Plugin Name:  WooCommerce Service Calls
Plugin URI:   http://digitaleel.com
Description:  Add service call statuses to WooCommerce order statuses
Version:      1.0
Author:       Bob O'Brien, Digital Eel. Inc.
Author URI:   http://digitaleel.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  woocommerce-service-call-statuses
Domain Path:  /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option('active_plugins') ) ) ){

	require_once plugin_dir_path(__FILE__) . 'includes/class-wc-sc-order-statuses.php';

	require_once plugin_dir_path(__FILE__) . 'includes/class-wc-sc-checkout-fields.php';

}