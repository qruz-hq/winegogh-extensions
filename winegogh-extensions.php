<?php
/*
Plugin Name: Winegogh Extensions
Description: A plugin to modify WooCommerce product prices for Elementor.
Version: 1.0.2
Author: Your Name
GitHub Plugin URI: https://github.com/yourusername/winegogh-extensions
GitHub Branch: main
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// Include the main plugin class
require_once plugin_dir_path(__FILE__) . 'includes/class-winegogh-extensions.php';

// Register new Elementor widgets
function register_new_widgets( $widgets_manager ) {
    require_once( __DIR__ . '/widgets/class-winegogh-elementor-price-widget.php' );
    $widgets_manager->register( new \Winegogh_Elementor_Price_Widget() );
}

add_action( 'elementor/widgets/register', 'register_new_widgets' );

add_action( 'plugins_loaded', 'winegogh_extensions_init' );

function winegogh_extensions_init() {
    $winegogh_extensions = new Winegogh_Extensions();
}
?>