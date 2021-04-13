<?php
if( !class_exists('Thegov_Theme_Helper') ) { return; }
/**
 * Class Team
 * @package PostType
 */

class Team {
    /**
     * @var string
     *
     * Set post type params
     */
    private $type = 'team';
    private $slug;
    private $name;
    private $singular_name;
    private $plural_name;

    /**
     * Team constructor.
     *
     * When class is instantiated
     */
    public function __construct() {
        // Register the post type
        $this->name = __( 'Team', 'thegov-core' );
        $this->singular_name = __( 'Member', 'thegov-core' );
        $this->plural_name = __( 'Members', 'thegov-core' );

        $this->slug = Thegov_Theme_Helper::get_option('team_slug') != '' ? Thegov_Theme_Helper::get_option('team_slug') : 'team';
        add_action('init', array($this, 'register'));
        add_action('init', array($this, 'register_taxonomy'));
        // Register template
        add_filter('single_template', array($this, 'get_custom_pt_single_template'));
        add_filter('archive_template', array($this, 'get_custom_pt_archive_template'));
    }

    /**
     * Register post type
     */
    public function register() {
        $labels = array(
            'name'               => $this->name,
            'singular_name'      => $this->singular_name,
            'add_new'            => sprintf( __('Add New %s', 'thegov-core' ), $this->singular_name ),
            'add_new_item'       => sprintf( __('Add New %s', 'thegov-core' ), $this->singular_name ),
            'edit_item'          => sprintf( __('Edit %s', 'thegov-core'), $this->singular_name ),
            'new_item'           => sprintf( __('New %s', 'thegov-core'), $this->singular_name ),
            'all_items'          => sprintf( __('All %s', 'thegov-core'), $this->plural_name ),
            'view_item'          => sprintf( __('View %s', 'thegov-core'), $this->name ),
            'search_items'       => sprintf( __('Search %s', 'thegov-core'), $this->name ),
            'not_found'          => sprintf( __('No %s found' , 'thegov-core'), strtolower($this->name) ),
            'not_found_in_trash' => sprintf( __('No %s found in Trash', 'thegov-core'), strtolower($this->name) ),
            'parent_item_colon'  => '',
            'menu_name'          => $this->name
        );
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $this->slug ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'menu_position'      => 8,
            'show_in_rest'       => true,
            'supports'           => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
            'menu_icon'          => 'dashicons-groups',
        );
        register_post_type( $this->type, $args );
    }

    public function register_taxonomy() {
        $category = 'category'; // Second part of taxonomy name

        $labels = array(
            'name' => sprintf( __( '%s Categories', 'thegov-core' ), $this->name ),
            'menu_name' => sprintf( __( '%s Categories', 'thegov-core' ), $this->name ),
            'singular_name' => sprintf( __( '%s Category', 'thegov-core' ), $this->name ),
            'search_items' =>  sprintf( __( 'Search %s Categories', 'thegov-core' ), $this->name ),
            'all_items' => sprintf( __( 'All %s Categories', 'thegov-core' ), $this->name ),
            'parent_item' => sprintf( __( 'Parent %s Category', 'thegov-core' ), $this->name ),
            'parent_item_colon' => sprintf( __( 'Parent %s Category:', 'thegov-core' ), $this->name ),
            'new_item_name' => sprintf( __( 'New %s Category Name', 'thegov-core' ), $this->name ),
            'add_new_item' => sprintf( __( 'Add New %s Category', 'thegov-core' ), $this->name ),
            'edit_item' => sprintf( __( 'Edit %s Category', 'thegov-core' ), $this->name ),
            'update_item' => sprintf( __( 'Update %s Category', 'thegov-core' ), $this->name ),
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => $this->slug.'-'.$category ),
        );
        register_taxonomy( $this->type.'_'.$category, array($this->type), $args );
    }

    // https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template
    function get_custom_pt_single_template($single_template) {
        global $post;

        if ($post->post_type == $this->type) {
            if(file_exists(get_template_directory().'/single-team.php')) return $single_template;

            $single_template = plugin_dir_path( dirname( __FILE__ ) ) . 'team/templates/single-team.php';
        }
        return $single_template;
    }

    // https://codex.wordpress.org/Plugin_API/Filter_Reference/archive_template
    function get_custom_pt_archive_template( $archive_template ) {
        global $post;

        if ( is_post_type_archive ( $this->type ) ) {
            if(file_exists(get_template_directory().'/archive-team.php')) return $archive_template;

            $archive_template = plugin_dir_path( dirname( __FILE__ ) ) .'team/templates/archive-team.php';
        }
        return $archive_template;
    }
}

function render_wgl_team_item ( $single_member = false, $item_atts, $team_image_dims ) {

    extract($item_atts);

    $compile = $team_cats = $team_info = $team_icons = $featured_image = $team_title = $team_wrapper = $featured_image_single = $item_classes = "";
    $wgl_pid = get_the_ID();
    $post = get_post( $wgl_pid );
    $link_to = get_permalink($wgl_pid);
    $department_name = get_post_meta($wgl_pid, "department_name");
    $department = get_post_meta($wgl_pid, "department", true);
    $info_array = get_post_meta($wgl_pid, "info_items", true);
    $social_array = get_post_meta($wgl_pid, "soc_icon", true);
    $info_bg_id = get_post_meta($wgl_pid, "mb_info_bg", true);
    $info_bg_url = wp_get_attachment_url($info_bg_id, 'full');
    $wp_get_attachment_url = wp_get_attachment_url(get_post_thumbnail_id($wgl_pid), 'single-post-thumbnail');
    $style_gap = ($grid_gap != '') ? 'padding-right:'.($grid_gap/2).'px; padding-left:'.($grid_gap/2).'px; padding-bottom:'.($grid_gap).'px;' : '';

    $item_style = (!empty($style_gap)) ? 'style="'.$style_gap.'"' : '';

    // Team info
    if (isset($info_array) && !empty($info_array)) {
        for ( $i=0; $i<count( $info_array ); $i++ ){
            $info = $info_array[$i];
            $info_name = !empty($info['name']) ? $info['name'] : '';
            $info_description = !empty($info['description']) ? $info['description'] : '';
            $info_link = !empty($info['link']) ? $info['link'] : '';

            if ((bool)$single_member && (!empty($info_name) || !empty($info_description))) {
                $team_info .= '<div class="team-info_item">';
                    $team_info .= !empty($info_name) ? '<h5>'.esc_html($info_name).'</h5>' : '';
                    $team_info .= !empty($info_link) ? '<a href="'.esc_url($info_link).'">' : '';
                        $team_info .= '<span>'.esc_html($info_description).'</span>';
                    $team_info .= !empty($info_link) ? '</a>' : '';
                $team_info .= '</div>';
            }
        }
    }

    // Team social icons
    if ( isset($social_array) ) {
        for ( $i=0; $i<count( $social_array ); $i++ ) {
            $icon = $social_array[$i];
            $icon_name = !empty($icon['select']) ? $icon['select'] : '';
            $icon_link = !empty($icon['link']) ? $icon['link'] : '#';
            if ( !empty($icon['select']) ) {
                $team_icons .= '<a href="'.$icon_link.'" class="team-icon '.$icon_name.'">';
                $team_icons .= '</a>';
            }
        }
    }
    $team_icons_wrap = !empty($team_icons) ? '<div class="team-info_icons">'.$team_icons.'</div>' : '';

    // Team image
    if (!empty($wp_get_attachment_url)) {
        $wgl_featured_image_url = ($posts_per_line == '1') ? $wp_get_attachment_url : aq_resize($wp_get_attachment_url, $team_image_dims['width'], $team_image_dims['height'], true, true, true);

        $img_alt = get_post_meta(get_post_thumbnail_id($wgl_pid), '_wp_attachment_image_alt', true);
        $featured_image .= ((bool)$single_link_wrapper && !(bool)$single_member) ? '<a href="'.esc_url($link_to).'">' : '';
            $featured_image .= '<img src="'.esc_url($wgl_featured_image_url).'" alt="'.(!empty($img_alt) ? $img_alt : '').'" />';
        $featured_image .= ((bool)$single_link_wrapper && !(bool)$single_member) ? '</a>' : '';
    }

    // Team single link heading
    if (!(bool)$hide_title) {
        $team_title .= '<h2 class="team-title">';
            $team_title .= ((bool)$single_link_heading && !(bool)$single_member) ? '<a href="'.esc_url($link_to).'">' : '';
                $team_title .= get_the_title();
            $team_title .= ((bool)$single_link_heading && !(bool)$single_member) ? '</a>' : '';
        $team_title .= '</h2>';
    }

    // Render team excerpt
    if (!(bool)$single_member) {
        $excerpt = !empty( $post->post_excerpt ) ? $post->post_excerpt : $post->post_content;
        $excerpt = preg_replace( '~\[[^\]]+\]~', '', $excerpt);
        $excerpt_stripe_tags = strip_tags($excerpt);
        $excerpt = Thegov_Theme_Helper::modifier_character($excerpt_stripe_tags, $letter_count, "");
    }

    // Item classes
    $item_classes .= !empty($animation_class) ? $animation_class : '';

    // Render team list & team single
    if (!(bool)$single_member) {

        $compile .= '<div class="team-item'.(!empty($item_classes) ? $item_classes : '').'" '.$item_style.'>';
            $compile .= '<div class="team-item_wrap">';
                $compile .= '<div class="team-item_content">';
                    $compile .= '<div class="team-image">';
                        $compile .= $featured_image;
                        $compile .= !(bool)$hide_soc_icons ? $team_icons_wrap : '';
                    $compile .= '</div>';
                $compile .= '</div>';
                if (!(bool)$hide_title || !(bool)$hide_department || !(bool)$hide_soc_icons || !(bool)$hide_content) {
                    $compile .= '<div class="team-item_info">';
                        $compile .= '<div class="team-item_titles">';
                            $compile .= $team_title;
                            $compile .= (!empty($department) && !(bool)$hide_department) ? '<div class="team-department">'.esc_html($department).'</div>' : '';
                        $compile .= '</div>';
                        $compile .= !(bool)$hide_content ? '<div class="team-item_excerpt">'.$excerpt.'</div>' : '';
                    $compile .= '</div>';
                }
            $compile .= '</div>';
        $compile .= '</div>';

    } else {

        $compile .= '<div class="team-single_wrapper" '.(!empty($info_bg_url) ? 'style="background-image:url('.$info_bg_url.')"' : '').'>';
            $compile .= '<div class="team-image_wrap">';
                $compile .= '<div class="team-image">';
                    $compile .= $featured_image;
                $compile .= '</div>';
            $compile .= '</div>';
            $compile .= '<div class="team-info_wrapper">';
                $compile .= $team_title;
                $compile .= !empty($department) ? '<div class="team-info_item team-department">'.(!empty($department_name[0]) ? '<h5>'.esc_html($department_name[0]).'</h5>' : '').'<span>'.esc_html($department).'</span></div>' : '';
                $compile .= !empty($team_info) ? $team_info : '';
                $compile .= $team_icons_wrap;
            $compile .= '</div>';
        $compile .= '</div>';

    }

    return $compile;
}

function render_wgl_team ($atts) {
    $wgl_def_atts = array(
        'letter_count' => '100',
        'single_link_heading' => true,
        'hide_title' => false,
        'hide_department' => false,
        'hide_soc_icons' => false,
        'animation_class' => '',
    );

    $item_atts = vc_shortcode_attribute_parse($wgl_def_atts, $atts);
    extract($item_atts);

    $compile = $item_classes = '';

    // Dimensions for team images
    switch ($posts_per_line) {
        default:
        case '1':
        case '2': $team_image_dims = array('width' => '860', 'height' => '1000'); break; // ratio = 0.7733
        case '3': $team_image_dims = array('width' => '700', 'height' => '860');  break;
        case '4':
        case '5': $team_image_dims = array('width' => '364', 'height' => '470');  break;
    }

    list($query_args) = Thegov_Loop_Settings::buildQuery($item_atts);
    $query_args['post_type'] = 'team';
    $wgl_posts = new WP_Query($query_args);
    if ($wgl_posts->have_posts()):
        while ($wgl_posts->have_posts()):
            $wgl_posts -> the_post();
            $compile .= render_wgl_team_item( false, $item_atts, $team_image_dims );
        endwhile;
        wp_reset_postdata();
    endif;

    echo $compile;
}