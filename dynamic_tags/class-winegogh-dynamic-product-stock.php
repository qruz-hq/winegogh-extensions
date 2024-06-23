<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Dynamic Tag - Product Stock
 *
 * Elementor dynamic tag that returns the product stock.
 *
 * @since 1.0.0
 */
class Winegogh_Dynamic_Product_Stock extends \Elementor\Core\DynamicTags\Tag {

    /**
     * Get dynamic tag name.
     *
     * Retrieve the name of the product stock tag.
     *
     * @since 1.0.0
     * @access public
     * @return string Dynamic tag name.
     */
    public function get_name() {
        return 'winegogh-product-stock';
    }

    /**
     * Get dynamic tag title.
     *
     * Returns the title of the product stock tag.
     *
     * @since 1.0.0
     * @access public
     * @return string Dynamic tag title.
     */
    public function get_title() {
        return esc_html__( 'Product Stock', 'winegogh-extensions' );
    }

    /**
     * Get dynamic tag groups.
     *
     * Retrieve the list of groups the product stock tag belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Dynamic tag groups.
     */
    public function get_group() {
        return [ 'winegogh-group' ];
    }

    /**
     * Get dynamic tag categories.
     *
     * Retrieve the list of categories the product stock tag belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Dynamic tag categories.
     */
    public function get_categories() {
        return  [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function register_controls() {
		$variables = [];

		foreach ( array_keys( $_SERVER ) as $variable ) {
			$variables[ $variable ] = ucwords( str_replace( '_', ' ', $variable ) );
		}

		$this->add_control(
			'user_selected_variable',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__( 'Variable', 'textdomain' ),
				'options' => $variables,
			]
		);
	}

    /**
     * Render tag output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function render() {
        global $product;

        if ( ! $product ) {
            echo 0;
            return;
        }

        $stock_quantity = $product->get_stock_quantity();

        echo esc_html( $stock_quantity > 0 ? $stock_quantity : 0 );
    }
}