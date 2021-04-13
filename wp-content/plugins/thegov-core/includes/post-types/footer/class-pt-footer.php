<?php
if(!class_exists('Thegov_Theme_Helper')){
    return;
}
/**
 * Class Team
 * @package PostType
 */
class Footer {
    /**
     * @var string
     *
     * Set post type params
     */
    private $type = 'footer';
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
        $this->name = __( 'Footer', 'thegov-core' );
        $this->slug = 'footer';
        $this->plural_name = __( 'Footers', 'thegov-core' );

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
            'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
            'menu_icon'  =>  'dashicons-admin-page',
        );
        register_post_type( $this->type, $args );
    }

    // https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template
    function get_custom_pt_single_template($single_template) {
        global $post;

        if ($post->post_type == $this->type) {
            if(file_exists(get_template_directory().'/single-footer.php')) return $single_template;

            $single_template = plugin_dir_path( dirname( __FILE__ ) ) . 'footer/templates/single-footer.php';
        }
        return $single_template;
    }
}