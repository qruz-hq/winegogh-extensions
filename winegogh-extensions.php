<?php
/*
Plugin Name: Winegogh Extensions
Description: A plugin to help develop Winegogh
Version: 1.0.9
Author: Gui Rodrigues
GitHub Plugin URI: https://github.com/qruz-hq/winegogh-extensions
GitHub Branch: main
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// Include the main plugin class
require_once plugin_dir_path(__FILE__) . 'includes/class-winegogh-extensions.php';

function register_request_variables_dynamic_tag_group( $dynamic_tags_manager ) {
    error_log( 'Registering WineGogh dynamic tag group.' );
    $dynamic_tags_manager->register_group(
        'winegogh-group',
        [
            'title' => esc_html__( 'WineGogh', 'winegogh' )
        ]
    );
}
add_action( 'elementor/dynamic_tags/register', 'register_request_variables_dynamic_tag_group' );

function register_winegogh_dynamic_tags( $dynamic_tags_manager ) {
    error_log( 'Registering WineGogh dynamic tags.' );
    require_once plugin_dir_path( __FILE__ ) . 'dynamic_tags/class-winegogh-dynamic-product-stock.php';
    if ( class_exists( 'Winegogh_Dynamic_Product_Stock' ) ) {
        $dynamic_tags_manager->register( new \Winegogh_Dynamic_Product_Stock() );
        error_log( 'WineGogh Product Stock tag registered successfully.' );
    } else {
        error_log( 'Failed to register WineGogh Product Stock tag. Class does not exist.' );
    }
}
add_action( 'elementor/dynamic_tags/register', 'register_winegogh_dynamic_tags' );

// Register new Elementor widgets
function register_new_widgets( $widgets_manager ) {
    require_once( __DIR__ . '/widgets/class-winegogh-elementor-price-widget.php' );
    $widgets_manager->register( new \Winegogh_Elementor_Price_Widget() );

    require_once plugin_dir_path( __FILE__ ) . 'widgets/class-winegogh-elementor-fooevents-widget.php';
    $widgets_manager->register( new \Winegogh_Elementor_FooEvents_Widget() );

    require_once plugin_dir_path( __FILE__ ) . 'widgets/class-winegogh-elementor-custom-meta-or-attribute-widget.php';
    $widgets_manager->register( new \Winegogh_Elementor_Custom_Meta_Or_Attribute_Widget() );

    require_once plugin_dir_path( __FILE__ ) . 'widgets/class-winegogh-elementor-stock-status-widget.php';
    $widgets_manager->register( new \Winegogh_Elementor_Stock_Status_Widget() );
}

// Register custom category
function add_custom_elementor_widget_categories( $elements_manager ) {
    $elements_manager->add_category(
        'winegogh-category',
        [
            'title' => __( 'Winegogh Widgets', 'winegogh-extensions' ),
            'icon' => 'fa fa-plug',
        ]
    );
}



add_action( 'elementor/elements/categories_registered', 'add_custom_elementor_widget_categories' );

add_action( 'elementor/widgets/register', 'register_new_widgets' );



add_action( 'plugins_loaded', 'winegogh_extensions_init' );

function winegogh_extensions_init() {
    $winegogh_extensions = new Winegogh_Extensions();
}
?>