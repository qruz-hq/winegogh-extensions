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
                    'WooCommerceEventsDate' => __( 'Event Date', 'winegogh-extensions' ),
                    'WooCommerceEventsEndDate' => __( 'Event End Date', 'winegogh-extensions' ),
                    'WooCommerceEventsStartTime' => __( 'Event Start Time', 'winegogh-extensions' ),
                    'WooCommerceEventsEndTime' => __( 'Event End Time', 'winegogh-extensions' ),
                    'WooCommerceEventsLocation' => __( 'Event Location', 'winegogh-extensions' ),
                    // Add more options as needed
                ],
                'default' => 'WooCommerceEventsDate',
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

        $field_value = $product->get_meta( $field_key );

        $class = $field_value ? '' : ' empty';

        echo '<div class="wg-fooevents-data' . esc_attr( $class ) . '">';
        if ( $field_value ) {
            if ( strpos($field_key, 'Date') !== false ) {
                // Debug output
                // echo '<pre>Debug: ' . print_r($field_value, true) . '</pre>';

                // Set locale to Spanish
                setlocale(LC_TIME, 'es_ES.UTF-8');

                // Try to parse the Spanish date format
                if (preg_match('/(\d{1,2}) de (\w+) de (\d{4})/', $field_value, $matches)) {
                    $day = $matches[1];
                    $month = $matches[2];
                    $year = $matches[3];

                    // Map Spanish month names to numbers
                    $months = [
                        'enero' => 1,
                        'febrero' => 2,
                        'marzo' => 3,
                        'abril' => 4,
                        'mayo' => 5,
                        'junio' => 6,
                        'julio' => 7,
                        'agosto' => 8,
                        'septiembre' => 9,
                        'octubre' => 10,
                        'noviembre' => 11,
                        'diciembre' => 12
                    ];

                    if (isset($months[$month])) {
                        $month_number = $months[$month];
                        $timestamp = mktime(0, 0, 0, $month_number, $day, $year);
                        $formatted_date = strftime('%a %d %B', $timestamp);
                        echo strtoupper($formatted_date);
                    } else {
                        echo  __( 'Invalid month name', 'winegogh-extensions' );
                    }
                } else {
                    // Try to parse the standard date format (YYYY-MM-DD)
                    $timestamp = strtotime($field_value);
                    if ($timestamp !== false) {
                        $formatted_date = strftime('%a %d %B', $timestamp);
                        echo  strtoupper($formatted_date);
                    } else {
                        echo  __( 'Invalid date format', 'winegogh-extensions' );
                    }
                }
            } else {
                echo esc_html( $field_value );
            }
        } else {
            echo '';
        }
        echo '</div>';
    }
}
?>