<?php
class Winegogh_Extensions {
    public function __construct() {
        add_filter( 'woocommerce_get_price_html', array( $this, 'modify_product_price' ), 10, 2 );
    }

    public function modify_product_price( $price, $product ) {
        // Get the regular price
        $regular_price = $product->get_regular_price();

        // Format the price: show decimals only if needed
        if ( strpos( $regular_price, '.' ) !== false ) {
            $formatted_price = number_format( $regular_price, 2, ',', '' );
            // Remove trailing zeroes after the decimal point
            $formatted_price = rtrim( rtrim( $formatted_price, '0' ), ',' );
        } else {
            $formatted_price = $regular_price;
        }

        // Add the currency symbol
        $currency_symbol = get_woocommerce_currency_symbol();
        $modified_price = $currency_symbol . $formatted_price;

        return $modified_price;
    }
}
?>