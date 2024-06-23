<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Winegogh_Elementor_Custom_Meta_Or_Attribute_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wg-custom-meta-or-attribute-data';
    }

    public function get_title() {
        return __( 'WG - Custom Meta or Attribute Data', 'winegogh-extensions' );
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return [ 'winegogh-category' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_custom_meta_or_attribute_data',
            [
                'label' => __( 'Custom Meta or Attribute Data', 'winegogh-extensions' ),
            ]
        );

        $this->add_control(
            'data_type',
            [
                'label' => __( 'Data Type', 'winegogh-extensions' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'meta' => __( 'Meta Key', 'winegogh-extensions' ),
                    'attribute' => __( 'Attribute', 'winegogh-extensions' ),
                ],
                'default' => 'meta',
            ]
        );

        $this->add_control(
            'meta_key_or_attribute',
            [
                'label' => __( 'Meta Key or Attribute Name', 'winegogh-extensions' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __( 'Enter meta key or attribute name', 'winegogh-extensions' ),
            ]
        );

        $this->add_control(
            'default_value',
            [
                'label' => __( 'Default Value', 'winegogh-extensions' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __( 'Enter default value if no data found', 'winegogh-extensions' ),
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
                    '{{WRAPPER}} .wg-custom-meta-or-attribute-data' => '{{VALUE}}',
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
        $data_type = $settings['data_type'];
        $meta_key_or_attribute = $settings['meta_key_or_attribute'];
        $default_value = $settings['default_value'];

        $data_value = '';
        if ( $data_type === 'meta' ) {
            $data_value = $product->get_meta( $meta_key_or_attribute );
        } elseif ( $data_type === 'attribute' ) {
            $attributes = $product->get_attributes();
            if ( isset( $attributes[ 'pa_' . $meta_key_or_attribute ] ) ) {
                $data_value = $product->get_attribute( 'pa_' . $meta_key_or_attribute );
            }
        }

        $data_value = $data_value ? $data_value : $default_value;
        $class = $data_value ? '' : ' empty';

        echo '<div class="wg-custom-meta-or-attribute-data' . esc_attr( $class ) . '">';
        if ( $data_value ) {
            if ( $data_type === 'meta' && strpos( $meta_key_or_attribute, 'Date' ) !== false ) {
                // Set locale to Spanish
                setlocale(LC_TIME, 'es_ES.UTF-8');

                // Try to parse the Spanish date format
                if ( preg_match( '/(\d{1,2}) de (\w+) de (\d{4})/', $data_value, $matches ) ) {
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

                    if ( isset( $months[ $month ] ) ) {
                        $month_number = $months[ $month ];
                        $timestamp = mktime( 0, 0, 0, $month_number, $day, $year );
                        $formatted_date = strftime( '%a %d %B', $timestamp );
                        echo  $this->capitalize_date( $formatted_date );
                    } else {
                        echo  __( 'Invalid month name', 'winegogh-extensions' );
                    }
                } else {
                    // Try to parse the standard date format (YYYY-MM-DD)
                    $timestamp = strtotime( $data_value );
                    if ( $timestamp !== false ) {
                        $formatted_date = strftime( '%a %d %B', $timestamp );
                        echo  $this->capitalize_date( $formatted_date );
                    } else {
                        echo  __( 'Invalid date format', 'winegogh-extensions' );
                    }
                }
            } else {
                echo  esc_html( $data_value );
            }
        } else {
            echo  __( 'No data available', 'winegogh-extensions' );
        }
        echo '</div>';
    }

    private function capitalize_date( $date ) {
        $words = explode( ' ', $date );
        foreach ( $words as &$word ) {
            $word = ucfirst( $word );
        }
        return implode( ' ', $words );
    }
}
?>