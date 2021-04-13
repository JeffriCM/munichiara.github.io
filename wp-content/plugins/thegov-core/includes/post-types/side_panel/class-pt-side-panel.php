<?php
if(!class_exists('Thegov_Theme_Helper')){
    return;
}
/**
 * Class Team
 * @package PostType
 */
class SidePanel {
    /**
     * @var string
     *
     * Set post type params
     */
    private $type = 'side_panel';
    private $slug;
    private $name;
    private $plural_name;

    /**
     * Team constructor.
     *
     * When class is instantiated
     */
    public function __construct() {
        // Register the post type
        $this->name = __( 'Side Panel', 'thegov-core' );
        $this->slug = 'side-panel';
        $this->plural_name = __( 'Side Panel', 'thegov-core' );

        add_action('init', array($this, 'register'));

        add_filter('single_template', array($this, 'get_custom_pt_single_template'));
    }

    /**
     * Register post type
     */
    public function register() {
        $labels = array(
            'name'                  => $this->name,
            'singular_name'         => $this->name,
            'add_new'               => sprintf( __('Add New %s', 'thegov-core' ), $this->name ),
            'add_new_item'          => sprintf( __('Add New %s', 'thegov-core' ), $this->name ),
            'edit_item'             => sprintf( __('Edit %s', 'thegov-core'), $this->name ),
            'new_item'              => sprintf( __('New %s', 'thegov-core'), $this->name ),
            'all_items'             => sprintf( __('All %s', 'thegov-core'), $this->plural_name ),
            'view_item'             => sprintf( __('View %s', 'thegov-core'), $this->name ),
            'search_items'          => sprintf( __('Search %s', 'thegov-core'), $this->name ),
            'not_found'             => sprintf( __('No %s found' , 'thegov-core'), strtolower($this->name) ),
            'not_found_in_trash'    => sprintf( __('No %s found in Trash', 'thegov-core'), strtolower($this->name) ),
            'parent_item_colon'     => '',
            'menu_name'             => $this->name
        );
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'exclude_from_search'   => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'rewrite'               => array( 'slug' => $this->slug ),
            'menu_position'         =>  5,
            'show_in_rest'          => true,
            'supports'              => array('title', 'editor', 'thumbnail', 'page-attributes'),
            'menu_icon'  =>  'dashicons-admin-page',
        );
        register_post_type( $this->type, $args );
    }

    public function wrapper_single_side_panel_open(){
        $pos = Thegov_Theme_Helper::options_compare('side_panel_position', 'mb_customize_side_panel', 'custom');

        $content_type = Thegov_Theme_Helper::options_compare('side_panel_content_type','mb_customize_side_panel','custom');
        $class = !empty($pos) ? ' side-panel_position_'.$pos : ' side-panel_position_right';

        wp_enqueue_script('perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar.min.js', array(), false, false);
        echo '<div class="side-panel_overlay"></div>';

            //Get options
        $side_panel_spacing = Thegov_Theme_Helper::options_compare('side_panel_spacing','mb_customize_side_panel','custom');

        $style  = '';
        $style .= !empty($side_panel_spacing['padding-top']) ? ' padding-top:'.(int)$side_panel_spacing['padding-top'].'px;' : '' ;
        $style .= !empty($side_panel_spacing['padding-bottom']) ? ' padding-bottom:'.(int)$side_panel_spacing['padding-bottom'].'px;' : '' ;
        $style .= !empty($side_panel_spacing['padding-left']) ? ' padding-left:'.(int)$side_panel_spacing['padding-left'].'px;' : '' ;
        $style .= !empty($side_panel_spacing['padding-right']) ? ' padding-right:'.(int)$side_panel_spacing['padding-right'].'px;' : '' ;
        $style  = !empty($style) ? ' style="'.$style.'"' : '';

        echo '<section id="side-panel" class="side-panel_widgets'.esc_attr($class).'"'.$this->side_panel_style().'>';
        echo '<a href="#" class="side-panel_close"><span class="side-panel_close_icon"></span></a>';
        echo '<div class="side-panel_sidebar"'.$style.'>';
    }

    public function wrapper_single_side_panel_close(){
        echo '</div>';
        echo '</section>';
    }

    public function side_panel_style(){

        $bg = Thegov_Theme_Helper::get_option('side_panel_bg');
        $bg = !empty($bg['rgba']) ? $bg['rgba'] : '';

        $color = Thegov_Theme_Helper::get_option('side_panel_text_color');
        $color = !empty($color['rgba']) ? $color['rgba'] : '';

        $width = Thegov_Theme_Helper::get_option('side_panel_width');
        $width = !empty($width['width']) ? $width['width'] : '';

        $align = Thegov_Theme_Helper::options_compare('side_panel_text_alignment', 'mb_customize_side_panel', 'custom');
        $style = '';

        if (class_exists( 'RWMB_Loader' ) && get_queried_object_id() !== 0) {
            $side_panel_switch = rwmb_meta('mb_customize_side_panel');
            if($side_panel_switch === 'custom'){
                $bg = rwmb_meta("mb_side_panel_bg");
                $color = rwmb_meta("mb_side_panel_text_color");
                $width = rwmb_meta("mb_side_panel_width");
            }
        }

        if (!empty($bg)) {
            $style .= !empty($bg) ? 'background-color: '.esc_attr($bg).';' : '';
        }

        if (!empty($color)) {
            $style .= !empty($color) ? 'color: '.esc_attr($color).';' : '';
        }

        if (!empty($width)) {
            $style .= 'width: '.esc_attr((int) $width ).'px;';
        }

        $style .= !empty($align) ? 'text-align: '.esc_attr($align).';' : 'text-align:center;';

        $style = !empty($style) ? ' style="'.$style.'"' : '';
        return $style;
    }


    // https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template
    function get_custom_pt_single_template($single_template) {
        global $post;

        if ($post->post_type == $this->type) {

            if (defined('ELEMENTOR_PATH')) {
                $elementor_template = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

                if ( file_exists( $elementor_template ) ) {
                    add_action( 'elementor/page_templates/canvas/before_content' , array($this, 'wrapper_single_side_panel_open'));
                    add_action( 'elementor/page_templates/canvas/after_content' , array($this, 'wrapper_single_side_panel_close'));
                    return $elementor_template;
                }
            }

            if(file_exists(get_template_directory().'/single-side-panel.php')) return $single_template;

            $single_template = plugin_dir_path( dirname( __FILE__ ) ) . 'side_panel/templates/single-side-panel.php';
        }
        return $single_template;
    }
}