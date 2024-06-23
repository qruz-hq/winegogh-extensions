<?php
class Winegogh_Extensions {
    public function __construct() {
    }

    public function format_product_price( $price ) {
        // Get the regular price
        $regular_price = floatval($price);

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
        $modified_price =  $formatted_price . $currency_symbol;

        return $modified_price;
    }
}
?>