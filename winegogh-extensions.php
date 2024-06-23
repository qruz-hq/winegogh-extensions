<?php
/*
Plugin Name: Winegogh Extensions
Description: A plugin to modify WooCommerce product prices for Elementor.
Version: 1.0.0
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

add_action( 'plugins_loaded', 'winegogh_extensions_init' );

function winegogh_extensions_init() {
    $winegogh_extensions = new Winegogh_Extensions();
}

add_action( 'elementor/widgets/widgets_registered', 'winegogh_extensions_elementor_integration' );

function winegogh_extensions_elementor_integration() {
    if ( \Elementor\Plugin::$instance->widgets_manager->is_widget_registered( 'woocommerce-product-price' ) ) {
        // Modify the existing widget or create a new one
        require_once plugin_dir_path(__FILE__) . 'includes/class-winegogh-elementor-price-widget.php';
    }
}
?>