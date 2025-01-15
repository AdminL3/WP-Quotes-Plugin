<?php

/*
 *
 * @link              https://github.com/AdminL3/
 * @since             1.0.0
 * @package           Wp_Quotes
 *
 * @wordpress-plugin
 * Plugin Name:       WP Quotes
 * Plugin URI:        https://github.com/AdminL3/wp-quotes
 * Description:       A simple WordPress plugin to upload a database of quotes and display them on your site.
 * Version:           1.0.0
 * Author:            Levi Blumenwitz
 * Author URI:        https://github.com/AdminL3/
 * License:           GPL-2.0+
 * Text Domain:       wp-quotes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/menu.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/settings.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/api.php';

// Enqueue admin-specific styles and scripts
function wp_quotes_admin_assets() {
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_style('wp-quotes-admin-style', $plugin_url . 'assets/style.css');
}
add_action('admin_enqueue_scripts', 'wp_quotes_admin_assets');

define('ALLOW_UNFILTERED_UPLOADS', true);