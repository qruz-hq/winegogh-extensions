<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Winegogh_Elementor_FooEvents_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wg-fooevents-data';
    }

    public function get_title() {
        return __( 'WG - FooEvents Data', 'winegogh-extensions' );
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return [ 'winegogh-category' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_fooevents_data',
            [
                'label' => __( 'FooEvents Data', 'winegogh-extensions' ),
            ]
        );

        $this->add_control(
            'fooevents_field',
            [
                'label' => __( 'FooEvents Field', 'winegogh-extensions' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '_event_date' => __( 'Event Date', 'winegogh-extensions' ),
                    '_event_location' => __( 'Event Location', 'winegogh-extensions' ),
                    '_event_start_time' => __( 'Event Start Time', 'winegogh-extensions' ),
                    '_event_end_time' => __( 'Event End Time', 'winegogh-extensions' ),
                    // Add more options as needed
                ],
                'default' => '_event_date',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_custom_css',
            [
                'label' => __( 'Custom CSS', 'winegogh-extensions' ),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $this->add_control(
            'custom_css',
            [
                'label' => __( 'Custom CSS', 'winegogh-extensions' ),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'selectors' => [
                    '{{WRAPPER}} .wg-fooevents-data' => '{{VALUE}}',
                ],
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
        $field_key = $settings['fooevents_field'];

        $field_value = get_post_meta( $product->get_id(), $field_key, true );

        echo '<div class="wg-fooevents-data">';
        if ( $field_value ) {
            echo '<p>' . esc_html( $field_value ) . '</p>';
        } else {
            echo '<p>' . __( 'No data available', 'winegogh-extensions' ) . '</p>';
        }
        echo '</div>';
    }
}
?>