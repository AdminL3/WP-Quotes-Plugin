<?php

function wp_api_menu() {
    add_menu_page('WP - Quotes', 'Quotes - Settings', 'manage_options', 'wp_quotes', 'wp_quotes_settings_page');
}
add_action('admin_menu', 'wp_api_menu');