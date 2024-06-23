<?php
class Winegogh_Extensions {
    public function __construct() {
        add_filter( 'woocommerce_get_price_html', array( $this, 'modify_product_price' ), 10, 2 );
    }

    public function modify_product_price( $price, $product ) {
        // Example modification: Append custom text to the price
        $modified_price = $price . ' <span class="custom-price-text">Custom Text</span>';

        return $modified_price;
    }
}
?>