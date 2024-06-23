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

        if ( !$product ) {
            return;
        }

        $price = $product->get_price_html();

        echo '<div class="wg-product-price">';
        echo apply_filters( 'woocommerce_get_price_html', $price, $product );
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