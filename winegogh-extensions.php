<?php
/*
Plugin Name: Winegogh Extensions
Description: A plugin to help develop Winegogh
Version: 1.0.29
Author: Gui Rodrigues
GitHub Plugin URI: https://github.com/qruz-hq/winegogh-extensions
GitHub Branch: main
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include utility functions
require_once plugin_dir_path(__FILE__) . 'lib/winegogh-utilities.php';


// Include the main plugin class
require_once plugin_dir_path(__FILE__) . 'includes/class-winegogh-extensions.php';

function register_request_variables_dynamic_tag_group($dynamic_tags_manager)
{
    error_log('Registering WineGogh dynamic tag group.');
    $dynamic_tags_manager->register_group(
        'winegogh-group',
        [
            'title' => esc_html__('WineGogh', 'winegogh')
        ]
    );
}
add_action('elementor/dynamic_tags/register', 'register_request_variables_dynamic_tag_group');

function register_winegogh_dynamic_tags($dynamic_tags_manager)
{
    error_log('Registering WineGogh dynamic tags.');
    require_once plugin_dir_path(__FILE__) . 'dynamic_tags/class-winegogh-dynamic-product-stock.php';
    if (class_exists('Winegogh_Dynamic_Product_Stock')) {
        $dynamic_tags_manager->register(new \Winegogh_Dynamic_Product_Stock());
        error_log('WineGogh Product Stock tag registered successfully.');
    } else {
        error_log('Failed to register WineGogh Product Stock tag. Class does not exist.');
    }
}
add_action('elementor/dynamic_tags/register', 'register_winegogh_dynamic_tags');

// Register new Elementor widgets
function register_new_widgets($widgets_manager)
{
    require_once(__DIR__ . '/widgets/class-winegogh-elementor-price-widget.php');
    $widgets_manager->register(new \Winegogh_Elementor_Price_Widget());

    require_once plugin_dir_path(__FILE__) . 'widgets/class-winegogh-elementor-fooevents-widget.php';
    $widgets_manager->register(new \Winegogh_Elementor_FooEvents_Widget());

    require_once plugin_dir_path(__FILE__) . 'widgets/class-winegogh-elementor-custom-meta-or-attribute-widget.php';
    $widgets_manager->register(new \Winegogh_Elementor_Custom_Meta_Or_Attribute_Widget());

    require_once plugin_dir_path(__FILE__) . 'widgets/class-winegogh-elementor-stock-status-widget.php';
    $widgets_manager->register(new \Winegogh_Elementor_Stock_Status_Widget());

    require_once plugin_dir_path(__FILE__) . 'widgets/class-winegogh-filter-bar-widget.php';
    $widgets_manager->register(new \Winegogh_Filter_Bar_Widget());
}

// Register custom category
function add_custom_elementor_widget_categories($elements_manager)
{
    $elements_manager->add_category(
        'winegogh-category',
        [
            'title' => __('Winegogh Widgets', 'winegogh-extensions'),
            'icon' => 'fa fa-plug',
        ]
    );
}



add_action('elementor/elements/categories_registered', 'add_custom_elementor_widget_categories');

add_action('elementor/widgets/register', 'register_new_widgets');

add_action('plugins_loaded', 'winegogh_extensions_init');

// Filtering



function winegogh_enqueue_scripts()
{
    // Enqueue the jQuery UI Datepicker
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('winegogh-filter', plugin_dir_url(__FILE__) . 'assets/js/filter.js', ['jquery', 'jquery-ui-datepicker']);
    wp_localize_script('winegogh-filter', 'winegogh', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);

    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
}
add_action('wp_enqueue_scripts', 'winegogh_enqueue_scripts');

function remove_inline_datepicker_defaults($tag, $handle)
{
    if ($handle === 'jquery-ui-datepicker') {
        // Log the original tag for debugging
        error_log('Original tag: ' . $tag);

        // Remove the inline script associated with the handle
        $pattern = '/<script\s+id="jquery-ui-datepicker-js-after".*?>.*?<\/script>/s';
        $modified_tag = preg_replace($pattern, '', $tag);

        // Log the modified tag for debugging
        error_log('Modified tag: ' . $modified_tag);

        return $modified_tag;
    }
    return $tag;
}
add_filter('script_loader_tag', 'remove_inline_datepicker_defaults', 10, 2);


// Add defer attribute to the script
function add_defer_attribute($tag, $handle)
{
    // List of scripts to defer
    $scripts_to_defer = ['winegogh-filter'];

    foreach ($scripts_to_defer as $defer_script) {
        if ($defer_script === $handle) {
            return str_replace(' src', ' defer="defer" src', $tag);
        }
    }

    return $tag;
}
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);


// Modify post args for loop grid

function winegogh_filter_loop_grid_query($query)
{
    if (!is_admin() && (isset($_GET['category']) || isset($_GET['event_date']))) {
        $category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
        $event_date = isset($_GET['event_date']) ? sanitize_text_field($_GET['event_date']) : '';

        $tax_query = [];
        $meta_query = [];

        if ($category) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $category,
            ];
        }

        if ($event_date) {
            $formatted_date = $event_date;
            $meta_query[] = [
                'key' => 'WooCommerceEventsDate',
                'value' => $formatted_date,
                'compare' => '=',
                'type' => 'CHAR' // Ensure comparison is done as a string
            ];
        }

        if (!empty($tax_query)) {
            $existing_tax_query = (array) $query->get('tax_query');
            $query->set('tax_query', array_merge($existing_tax_query, $tax_query));
        }

        if (!empty($meta_query)) {
            $existing_meta_query = (array) $query->get('meta_query');
            $query->set('meta_query', array_merge($existing_meta_query, $meta_query));
        }

    }

    return $query;
}

add_filter('elementor/query/winegogh_category_posts', 'winegogh_filter_loop_grid_query');

function winegogh_get_event_dates()
{
    $dates = [];

    // Define a unique key for the transient
    $transient_key = 'winegogh_event_dates_upd_6';
    $cached_dates = get_transient($transient_key);
    if ($cached_dates !== false) {
        wp_send_json($cached_dates);
        return;
    }

    $current_date = current_time('d-m-Y');

    $query = new WP_Query([
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => [

            [
                'key' => 'WooCommerceEventsDate',
                'value' => '',
                'compare' => '!=',
            ]
        ]
    ]);

    while ($query->have_posts()) {
        $query->the_post();
        $event_date = get_post_meta(get_the_ID(), 'WooCommerceEventsDate', true);
        $formatted_date = winegogh_parse_date(strtolower($event_date), 'd-m-Y');
        error_log('Date :'. $formatted_date . ' - Current : '. $current_date);
        if (new DateTime($formatted_date) >= new DateTime($current_date)) {
            if (!in_array($formatted_date, $dates)) {
                $dates[] = $formatted_date;
            }
        }
    }

    wp_reset_postdata();

    // Cache the dates for 1 hour (3600 seconds)
    set_transient($transient_key, $dates, 3600);

    wp_send_json($dates);
}


add_action('wp_ajax_get_event_dates', 'winegogh_get_event_dates');
add_action('wp_ajax_nopriv_get_event_dates', 'winegogh_get_event_dates');

function winegogh_extensions_init()
{
    $winegogh_extensions = new Winegogh_Extensions();
}
