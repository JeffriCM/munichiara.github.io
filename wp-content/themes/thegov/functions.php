<?php 
@ini_set( 'upload_max_size' , '512M' );
@ini_set( 'post_max_size', '512M');
@ini_set( 'max_execution_time', '600' );
@ini_set( 'max_input_time', '600' );

update_option( 'wgl_licence_validated', '*********' );

//Class Theme Helper
require_once ( get_theme_file_path( '/core/class/theme-helper.php' ) );

//Class Theme Cache
require_once ( get_theme_file_path( '/core/class/theme-cache.php' ) );

//Class Walker comments
require_once ( get_theme_file_path( '/core/class/walker-comment.php' ) );

//Class Walker Mega Menu
require_once ( get_theme_file_path( '/core/class/walker-mega-menu.php' ) );

//Class Theme Likes
require_once ( get_theme_file_path( '/core/class/theme-likes.php' ) );

//Class Theme Cats Meta
require_once ( get_theme_file_path( '/core/class/theme-cat-meta.php' ) );

//Class Single Post
require_once ( get_theme_file_path( '/core/class/single-post.php' ) );

//Class Tinymce
require_once ( get_theme_file_path( '/core/class/tinymce-icon.php' ) );

//Class Theme Autoload
require_once ( get_theme_file_path( '/core/class/theme-autoload.php' ) );

//Class Theme Dashboard
require_once ( get_theme_file_path( '/core/class/theme-panel.php' ) );

//Class Theme Verify
require_once ( get_theme_file_path( '/core/class/theme-verify.php' ) );

function thegov_content_width() {
    if ( ! isset( $content_width ) ) {
        $content_width = 940;
    }
}
add_action( 'after_setup_theme', 'thegov_content_width', 0 );

function thegov_theme_slug_setup() {
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'thegov_theme_slug_setup');

add_action('init', 'thegov_page_init');
if (!function_exists('thegov_page_init')) {
    function thegov_page_init()
    {
        add_post_type_support('page', 'excerpt');
    }
}

if (!function_exists('thegov_main_menu')) {
    function thegov_main_menu ($location = ''){
        wp_nav_menu( array(
            'theme_location'  => 'main_menu',
            'menu'  => $location,
            'container' => '',
            'container_class' => '',  
            'after' => '',
            'link_before'     => '<span>',
            'link_after'      => '</span>',            
            'walker' => new Thegov_Mega_Menu_Waker()
        ) );
    }
}

// return all sidebars
if (!function_exists('thegov_get_all_sidebar')) {
    function thegov_get_all_sidebar() {
        global $wp_registered_sidebars;
        $out = array();
        if ( empty( $wp_registered_sidebars ) )
            return;
         foreach ( $wp_registered_sidebars as $sidebar_id => $sidebar) :
            $out[$sidebar_id] = $sidebar['name'];
         endforeach; 
         return $out;
    }
}

if (!function_exists('thegov_get_custom_preset')) {
    function thegov_get_custom_preset() {
        $custom_preset = get_option('thegov_set_preset');
        $presets =  thegov_default_preset();
        
        $out = array();
        $out['default'] = esc_html__( 'Default', 'thegov' );
        $i = 1;
        if(is_array($presets)){
            foreach ($presets as $key => $value) {
                $out[$key] = $key;
                $i++;
            }            
        }
        if(is_array($custom_preset)){
            foreach ( $custom_preset as $preset_id => $preset) :
                $out[$preset_id] = $preset_id;
            endforeach;             
        }
        return $out;
    }
}

if (!function_exists('thegov_get_custom_menu')) {
    function thegov_get_custom_menu() {
        $taxonomies = array();

        $menus = get_terms('nav_menu');
        foreach ($menus as $key => $value) {
            $taxonomies[$value->name] = $value->name;
        }
        return $taxonomies;   
    }
}

function thegov_get_attachment( $attachment_id ) {
    $attachment = get_post( $attachment_id );
    return array(
        'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
        'caption' => $attachment->post_excerpt,
        'description' => $attachment->post_content,
        'href' => get_permalink( $attachment->ID ),
        'src' => $attachment->guid,
        'title' => $attachment->post_title
    );
}

if (!function_exists('thegov_reorder_comment_fields')) {
    function thegov_reorder_comment_fields($fields ) {
        $new_fields = array();

        $myorder = array('author', 'email', 'url', 'comment');

        foreach( $myorder as $key ){
            $new_fields[ $key ] = isset($fields[ $key ]) ? $fields[ $key ] : '';
            unset( $fields[ $key ] );
        }

        if( $fields ) {
            foreach( $fields as $key => $val ) {
                $new_fields[ $key ] = $val;
            }
        }

        return $new_fields;
    }
}
add_filter('comment_form_fields', 'thegov_reorder_comment_fields');

function thegov_mce_buttons_2( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
add_filter( 'mce_buttons_2', 'thegov_mce_buttons_2' );


function thegov_tiny_mce_before_init( $settings ) {

    $settings['theme_advanced_blockformats'] = 'p,h1,h2,h3,h4';
    $header_font_color = Thegov_Theme_Helper::get_option('header-font')['color'];
    $theme_color = Thegov_Theme_Helper::get_option('theme-custom-color');
    
    $style_formats = array(
        array(
            'title' => esc_html__( 'Dropcap', 'thegov' ),
            'items' => array(
                array(
                    'title' => esc_html__( 'Dropcap Colored', 'thegov' ),
                    'inline' => 'span', 
                    'classes' => 'dropcap', 
                    'styles' => array( 'background-color' => esc_attr( $theme_color ), 'border-color' => esc_attr( $theme_color ) ),
                ),
                array(
                    'title' => esc_html__( 'Dropcap Dark', 'thegov' ),
                    'inline' => 'span',
                    'classes' => 'dropcap-bg',
                    'styles' => array( 'background-color' => esc_attr( $header_font_color ), 'color' => '#ffffff'),
                ),                
            ),
        ),
        array( 
            'title' => esc_html__( 'Highlighter', 'thegov' ), 
            'inline' => 'span', 
            'classes' => 'highlighter', 
            'styles' => array( 'color' => '#ffffff', 'background-color' => esc_attr( Thegov_Theme_Helper::get_option('theme-custom-color') )),
        ),
        array( 
            'title' => esc_html__( 'Double Heading Font', 'thegov' ), 
            'inline' => 'span', 
            'classes' => 'dbl_font',
        ),
        array( 
            'title' => esc_html__( 'Font Weight', 'thegov' ), 
            'items' => array(
                array( 'title' => esc_html__( 'Default', 'thegov' ), 'inline' => 'span', 'classes' => '', 'styles' => array( 'font-weight' => 'inherit' ) ),
                array( 'title' => esc_html__( 'Lightest (100)', 'thegov' ), 'inline' => 'span', 'classes' => '', 'styles' => array( 'font-weight' => '100' ) ),
                array( 'title' => esc_html__( 'Lighter (200)', 'thegov' ), 'inline' => 'span', 'classes' => '', 'styles' => array( 'font-weight' => '200' ) ),
                array( 'title' => esc_html__( 'Light (300)', 'thegov' ), 'inline' => 'span', 'classes' => '', 'styles' => array( 'font-weight' => '300' ) ),
                array( 'title' => esc_html__( 'Normal (400)', 'thegov' ), 'inline' => 'span', 'classes' => '', 'styles' => array( 'font-weight' => '400' ) ),
                array( 'title' => esc_html__( 'Medium (500)', 'thegov' ), 'inline' => 'span', 'classes' => '', 'styles' => array( 'font-weight' => '500' ) ),
                array( 'title' => esc_html__( 'Semi-Bold (600)', 'thegov' ), 'inline' => 'span', 'classes' => '', 'styles' => array( 'font-weight' => '600' ) ),
                array( 'title' => esc_html__( 'Bold (700)', 'thegov' ), 'inline' => 'span', 'classes' => '', 'styles' => array( 'font-weight' => '700' ) ),
                array( 'title' => esc_html__( 'Bolder (800)', 'thegov' ), 'inline' => 'span', 'classes' => '', 'styles' => array( 'font-weight' => '800' ) ),
                array( 'title' => esc_html__( 'Extra Bold (900)', 'thegov' ), 'inline' => 'span', 'classes' => '', 'styles' => array( 'font-weight' => '900' ) ),
            )
        ),
        array(
            'title' => esc_html__( 'List Style', 'thegov' ),
            'items' => array(
                array( 'title' => esc_html__( 'Check', 'thegov' ), 'selector' => 'ul', 'classes' => 'thegov_check' ),
                array( 'title' => esc_html__( 'Plus', 'thegov' ), 'selector' => 'ul', 'classes' => 'thegov_plus' ),
                array( 'title' => esc_html__( 'Dash', 'thegov' ), 'selector' => 'ul', 'classes' => 'thegov_dash' ),
                array( 'title' => esc_html__( 'Slash', 'thegov' ), 'selector' => 'ul', 'classes' => 'thegov_slash' ),
                array( 'title' => esc_html__( 'No List Style', 'thegov' ), 'selector' => 'ul', 'classes' => 'no-list-style' ),
            )
        ),
    );

    $settings['style_formats'] = str_replace( '"', "'", json_encode( $style_formats ) );
    $settings['extended_valid_elements'] = 'span[*],a[*],i[*]';
    return $settings;
}
add_filter( 'tiny_mce_before_init', 'thegov_tiny_mce_before_init' );

function thegov_theme_add_editor_styles() {
    add_editor_style( 'css/font-awesome.min.css' );
}
add_action( 'current_screen', 'thegov_theme_add_editor_styles' );

function thegov_categories_postcount_filter ($variable) {
    if(strpos($variable,'</a> (')){
        $variable = str_replace('</a> (', '</a> <span class="post_count">(', $variable); 
        $variable = str_replace('</a>&nbsp;(', '</a>&nbsp;<span class="post_count">', $variable); 
        $variable = str_replace(')', ')</span>', $variable);      
    }
    else{
        $variable = str_replace('</a> <span class="count">(', '</a><span class="post_count">(', $variable);    
        $variable = str_replace(')', ')</span>', $variable);  
    }

    $pattern1 = '/cat-item-\d+/';
    preg_match_all( $pattern1, $variable,$matches );
    if(isset($matches[0])){
        foreach ($matches[0] as $key => $value) {
            $int = (int) str_replace('cat-item-','', $value);
            $icon_image_id = get_term_meta ( $int, 'category-icon-image-id', true );
            if(!empty($icon_image_id)){
                $icon_image = wp_get_attachment_image_src ( $icon_image_id, 'full' );
                $icon_image_alt = get_post_meta($icon_image_id, '_wp_attachment_image_alt', true);
                $replacement = '$1<img class="cats_item-image" src="'. esc_url($icon_image[0]) .'" alt="'.(!empty($icon_image_alt) ? esc_attr($icon_image_alt) : '').'"/>';
                $pattern = '/(cat-item-'.$int.'+.*?><a.*?>)/';
                $variable = preg_replace( $pattern, $replacement, $variable );
            }
        }        
    }   

    return $variable;
}
add_filter('wp_list_categories', 'thegov_categories_postcount_filter');

add_filter( 'get_archives_link', 'thegov_render_archive_widgets', 10, 6 );
function thegov_render_archive_widgets ( $link_html, $url, $text, $format, $before, $after ) {

    $text = wptexturize( $text );
    $url  = esc_url( $url );

    if ( 'link' == $format ) {
        $link_html = "\t<link rel='archives' title='" . esc_attr( $text ) . "' href='$url' />\n";
    } elseif ( 'option' == $format ) {
        $link_html = "\t<option value='$url'>$before $text $after</option>\n";
    } elseif ( 'html' == $format ) {
        $after = str_replace('(', '', $after);
        $after = str_replace(' ', '', $after);
        $after = str_replace('&nbsp;', '', $after);
        $after = str_replace(')', '', $after);

        $after = !empty($after) ? " <span class='post_count'>(".esc_html($after).")</span> " : "";

        $link_html = "<li>".esc_html($before)."<a href='".esc_url($url)."'>".esc_html($text)."</a>".$after."</li>";
    } else { // custom
        $link_html = "\t$before<a href='$url'>$text</a>$after\n";
    }
    
    return $link_html;
}

// Add image size
if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'thegov-840-620',  840, 620, true  );
    add_image_size( 'thegov-440-440',  440, 440, true  );
    add_image_size( 'thegov-180-180',  180, 180, true  );
    add_image_size( 'thegov-120-120',  120, 120, true  );
}

// Include Woocommerce init if plugin is active
if ( class_exists( 'WooCommerce' ) ) {
    require_once( get_theme_file_path ( '/woocommerce/woocommerce-init.php' ) ); 
}

// Include WP Events init if plugin is active
if ( class_exists( 'EM_Events' ) ) {
    require_once( get_theme_file_path ( '/plugins/events-manager/wp-event-init.php' ) ); 
}

add_filter('thegov_enqueue_shortcode_css', 'thegov_render_css');
function thegov_render_css($styles){
    global $thegov_dynamic_css;
    if(! isset($thegov_dynamic_css['style'])){
        $thegov_dynamic_css = array();
        $thegov_dynamic_css['style'] = $styles;
    }else{
        $thegov_dynamic_css['style'] .= $styles;
    }
}