<?php
class Winegogh_Elementor_Price_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'winegogh-elementor-price';
    }

    public function get_title() {
        return __( 'Custom Price', 'winegogh-extensions' );
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

        $price = $product->get_price_html();

        echo '<div class="elementor-custom-price">';
        echo apply_filters( 'woocommerce_get_price_html', $price, $product );
        echo '</div>';
    }
}
?>