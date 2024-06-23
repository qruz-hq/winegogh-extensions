<?php
class Winegogh_Elementor_Price_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wg-product-price';
    }

    public function get_title() {
        return __( 'WG - Product Price', 'winegogh-extensions' );
    }

    public function get_icon() {
        return 'eicon-price-list';
    }

    public function get_categories() {
        return [ 'woocommerce-elements' ];
    }

    protected function render() {
        global $product;

        if ( ! $product ) {
            return;
        }

        // Get the regular price
        $regular_price = $product->get_regular_price();

        // Use the custom price formatting function
        $extensions = new Winegogh_Extensions();
        $formatted_price = $extensions->format_product_price( $regular_price );

        echo '<div class="wg-product-price">';
        echo $formatted_price;
        echo '</div>';
    }

    protected function _register_controls() {
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
                    '{{WRAPPER}} .wg-product-price' => '{{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }
}
?>