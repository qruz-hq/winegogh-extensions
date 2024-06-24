<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Winegogh_Filter_Bar_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wg-filter-bar';
    }

    public function get_title() {
        return __( 'WG - Filter Bar', 'winegogh-extensions' );
    }

    public function get_icon() {
        return 'eicon-filter';
    }

    public function get_categories() {
        return [ 'winegogh-category' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_filter_bar',
            [
                'label' => __( 'Filter Bar', 'winegogh-extensions' ),
            ]
        );



        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'winegogh-extensions' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-calendar',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'caret',
            [
                'label' => __( 'Caret', 'winegogh-extensions' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-caret-down',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => __( 'Categories', 'winegogh-extensions' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_product_categories(),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    private function get_product_categories() {
        $categories = get_terms( 'product_cat' );
        $options = [];
        foreach ( $categories as $category ) {
            $options[ $category->slug ] = $category->name;
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $current_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
        $current_date = isset($_GET['event_date']) ? sanitize_text_field($_GET['event_date']) : '';
        ?>

        <form class="wg-filter-bar" method="get">
            <div class="wg-filter-category">
                <select name="category" id="wg-filter-category">
                    <option value=""><?php _e( 'All', 'winegogh-extensions' ); ?></option>
                    <?php foreach ( $settings['categories'] as $category ) : ?>
                        <option value="<?php echo esc_attr( $category ); ?>" <?php selected( $category, $current_category ); ?>><?php echo esc_html( ucwords( $category ) ); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php  \Elementor\Icons_Manager::render_icon( $settings['caret'], [ 'aria-hidden' => 'true' ] ); ?>
            </div>
            <div class="wg-filter-date">
                <input type="text" name="event_date" id="wg-filter-date" placeholder=" " readonly value="<?php echo esc_attr( $current_date ); ?>">
                <span class="wg-filter-date-label">Añadir Fecha</span>
                <?php  \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </input>
                
            </div>
        </form>

        <?php
    }
}
?>