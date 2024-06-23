<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Winegogh_Elementor_Stock_Status_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wg-stock-status';
    }

    public function get_title() {
        return __( 'WG - Stock Status', 'winegogh-extensions' );
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return [ 'winegogh-category' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_stock_status',
            [
                'label' => __( 'Stock Status', 'winegogh-extensions' ),
            ]
        );

        $this->add_control(
            'stock_message',
            [
                'label' => __( 'Stock Message', 'winegogh-extensions' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Only {{ stock }} left in stock!', 'winegogh-extensions' ),
                'description' => __( 'Use {{ stock }} as a placeholder for the stock quantity.', 'winegogh-extensions' ),
            ]
        );

        $this->add_control(
            'out_of_stock_message',
            [
                'label' => __( 'Out of Stock Message', 'winegogh-extensions' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Out of stock', 'winegogh-extensions' ),
            ]
        );

        $this->add_control(
            'low_stock_threshold',
            [
                'label' => __( 'Low Stock Threshold', 'winegogh-extensions' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'description' => __( 'Set the threshold for low stock', 'winegogh-extensions' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        global $product;

        if ( ! $product ) {
            return;
        }

        $settings = $this->get_settings_for_display();
        $stock_message = $settings['stock_message'];
        $out_of_stock_message = $settings['out_of_stock_message'];
        $low_stock_threshold = $settings['low_stock_threshold'];

        $stock_quantity = $product->get_stock_quantity();
        $stock_status = $product->get_stock_status();

        $status_class = '';
        $message = '';

        if ( $stock_status === 'outofstock' || !$stock_quantity ) {
            $status_class = 'out-of-stock';
            $message = $out_of_stock_message;
        } elseif ( $stock_quantity <= $low_stock_threshold ) {
            $status_class = 'low-stock';
            $message = str_replace('{{ stock }}', $stock_quantity, $stock_message);
        } else {
            $status_class = 'in-stock';
            $message = str_replace('{{ stock }}', $stock_quantity, $stock_message);
        }

        echo '<div class="wg-stock-status ' . esc_attr( $status_class ) . '">';
        echo esc_html( $message );
        echo '</div>';
    }
}
?>