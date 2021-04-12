<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
/**
* Thegov Theme Helper
*
*
* @class        Thegov_Theme_Helper
* @version      1.0
* @category     Class
* @author       WebGeniusLab
*/

if (!class_exists('Thegov_Theme_Helper')) {
    class Thegov_Theme_Helper{

        private static $instance = null;
        public static function get_instance( ) {
            if ( null == self::$instance ) {
                self::$instance = new self( );
            }

            return self::$instance;
        }

        public static function get_option($name, $preset = null, $def_preset = null) {
            if (  class_exists( 'Redux' ) && class_exists( 'Thegov_Core_Public' ) ) {
                $preset = $preset == 'default' ? null : $preset;

                if (!$preset) {

                    // Customizer
                    if (!empty($GLOBALS['thegov_set']) && $GLOBALS['thegov_set'] != NULL) {
                        $theme_options = $GLOBALS['thegov_set'];
                    } else {
                        $theme_options = get_option( 'thegov_set' );
                    }

                } else {
                    $theme_options = get_option( 'thegov_set_preset' );
                }

                if (empty($theme_options)) {
                    $theme_options = get_option( 'thegov_default_options' );
                }

                if(!$preset){
                    return isset($theme_options[$name]) ? $theme_options[$name] : null;
                }

                if(!empty($def_preset)){
                    return isset($theme_options['default'][$preset][$name]) ? $theme_options['default'][$preset][$name] : null;
                }else{
                    return isset($theme_options[$preset][$name]) ? $theme_options[$preset][$name] : null;
                }


            }else{
                $default_option = get_option( 'thegov_default_options' );
                return isset($default_option[$name]) ? $default_option[$name] : null;
            }
        }

        public static function options_compare($name,$check_key = false,$check_value = false) {
            $option = self::get_option($name);
            $id = !is_archive() ? get_queried_object_id() : 0;
            if (class_exists( 'RWMB_Loader' ) && 0 !== $id) {
                if ( $check_key ) {
                    $var = rwmb_meta($check_key);
                    if ( !empty($var) ) {
                        if ( $var == $check_value ) {
                            $option = rwmb_meta('mb_'.$name);
                        }
                    }
                } else {
                    $var = rwmb_meta('mb_'.$name);
                    $option = !empty($var) ? rwmb_meta('mb_'.$name) : self::get_option($name);
                }
            }
            return $option;
        }

        public static function bg_render($name,$check_key = false,$check_value = false) {
            $image = Thegov_Theme_Helper::get_option($name."_bg_image");
            $id = !is_archive() ? get_queried_object_id() : 0;

            // Get image src
            $src = !empty($image['background-image']) ? $image['background-image'] : '';

            // Get image repeat
            $repeat = !empty($image['background-repeat']) ? $image['background-repeat'] : '';

            // Get image size
            $size = !empty($image['background-size']) ? $image['background-size'] : '';

            // Get image attachment
            $attachment = !empty($image['background-attachment']) ? $image['background-attachment'] : '';

            // Get image position
            $position = !empty($image['background-position']) ? $image['background-position'] : '';

            if (class_exists( 'RWMB_Loader' ) && 0 !== $id) {

                $conditional_logic = rwmb_meta($check_key);

                if ($conditional_logic == 'on') {

                    $repeat = $size = $attachment = $position  = '';
                    // Get metaboxes image src
                    $src = rwmb_meta('mb_'.$name.'_bg')['image'];

                    // Check if metaboxes image exist
                    if (!empty($src)) {
                        // Get metaboxes image repeat
                        $repeat = rwmb_meta('mb_'.$name.'_bg')['repeat'];
                        $repeat = !empty($repeat) ? $repeat : '';

                        // Get metaboxes image size
                        $size = rwmb_meta('mb_'.$name.'_bg')['size'];
                        $size = !empty($size) ? $size : '';

                        // Get metaboxes image attachment
                        $attachment = rwmb_meta('mb_'.$name.'_bg')['attachment'];
                        $attachment = !empty($attachment) ? $attachment : '';

                        // Get metaboxes image position
                        $position = rwmb_meta('mb_'.$name.'_bg')['position'];
                        $position = !empty($position) ? $position : '';
                    }
                }
            }

            // Background render
            $style = '';
            $style .= !empty($src) ? 'background-image:url('.esc_url($src).');' : '';

            if (!empty($src)) {
                $style .= !empty($size) ? ' background-size:'.esc_attr($size).';' : '';
                $style .= !empty($repeat) ? ' background-repeat:'.esc_attr($repeat).';' : '';
                $style .= !empty($attachment) ? ' background-attachment:'.esc_attr($attachment).';' : '';
                $style .= !empty($position) ? ' background-position:'.esc_attr($position).';' : '';
            }
            return $style;
        }

        public static function preloader(){
            if (self::get_option('preloader') == '1' || self::get_option('preloader') == true) {
                $preloader_background = self::get_option('preloader_background');
                $preloader_color_1 = self::get_option('preloader_color_1');

                $bg_styles = !empty($preloader_background) ? ' style=background-color:'.$preloader_background.';' : "";
                $circle_color_1 = !empty($preloader_color_1) ? ' style=background-color:'.$preloader_color_1.';' : "";

                echo '<div id="preloader-wrapper" '.esc_attr($bg_styles).'>
                        <div class="preloader-container">
                          <div '.$circle_color_1.'></div>
                          <div '.$circle_color_1.'></div>
                          <div '.$circle_color_1.'></div>
                          <div '.$circle_color_1.'></div>
                          <div '.$circle_color_1.'></div>
                          <div '.$circle_color_1.'></div>
                          <div '.$circle_color_1.'></div>
                          <div '.$circle_color_1.'></div>
                          <div '.$circle_color_1.'></div>
                          </div>
                        </div>
                    </div>';
            }
        }

        public static function pagination($range = 5, $query = false, $alignment = 'left'){
            if ( $query != false ) {
                $wp_query = $query;
            } else {
                global $paged, $wp_query;
            }
            if (empty($paged)) {
                $query_vars = $wp_query->query_vars;
                $paged = isset($query_vars['paged']) ? $query_vars['paged'] : 1;
            }

            $output = '';
            $max_page = $wp_query->max_num_pages;


            // Exit if pagination not need
            if ( !($max_page > 1) ) return;

            switch ($alignment) {
                case 'left':
                    $class_alignment = '';
                    break;
                case 'right':
                    $class_alignment = 'aright';
                    break;
                case 'center':
                    $class_alignment = 'acenter';
                    break;
                default:
                    $class_alignment = '';
                    break;
            }

            //return $output;
            $big = 999999999;

            $test_pag = paginate_links(array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'type' => 'array',
                'current'    => max( 1, $paged ),
                'total'      => $max_page,
                'prev_text' => '<i class="fa flaticon-arrow-pointing-to-left"></i>',
                'next_text' => '<i class="fa flaticon-right"></i>',
            ));
            $test_comp = '';
            foreach ($test_pag as $key => $value) {
                $test_comp .= '<li class="page">'.$value.'</li>';
            }
            return '<ul class="wgl-pagination '.esc_attr($class_alignment).'">'.$test_comp.'</ul>';
        }

        public static function hexToRGB($hex = "#ffffff"){
            $color = array();
            if (strlen($hex) < 1) {
                $hex = "#ffffff";
            }
            $color['r'] = hexdec(substr($hex, 1, 2));
            $color['g'] = hexdec(substr($hex, 3, 2));
            $color['b'] = hexdec(substr($hex, 5, 2));
            return $color['r'] . "," . $color['g'] . "," . $color['b'];
        }

        //https://github.com/opensolutions/smarty/blob/master/plugins/modifier.truncate.php
        public static function modifier_character($string, $length = 80, $etc = '... ', $break_words = false) {
            if ($length == 0)
                return '';

            if (mb_strlen($string, 'utf8') > $length) {
                $length -= mb_strlen($etc, 'utf8');
                if (!$break_words) {
                    $string = preg_replace('/\s+\S+\s*$/su', '', mb_substr($string, 0, $length + 1, 'utf8'));
                }
                return mb_substr($string, 0, $length, 'utf8') . $etc;
            } else {
                return $string;
            }
        }

		public static function load_more($query = false, $name_load_more = '', $class = ''){
			$name_load_more = !empty($name_load_more) ? $name_load_more : esc_html__( 'Load More', 'thegov' );

			$uniq = uniqid();
			$ajax_data_str = htmlspecialchars( json_encode($query), ENT_QUOTES, 'UTF-8' );

			$out = '<div class="clear"></div>';
			$out .= '<div class="load_more_wrapper'.(!empty($class) ? ' '.esc_attr($class) : '' ).'">';
			$out .= '<div class="button_wrapper">';
				$out .= '<a href="#" class="load_more_item"><span>'.esc_html($name_load_more).'</span></a>';
			$out .= '</div>';
			$out .= '<form class="posts_grid_ajax">';
				$out .= "<input type='hidden' class='ajax_data' name='".esc_attr($uniq)."_ajax_data' value='$ajax_data_str' />";
			$out .= '</form>';
			$out .= '</div>';

			return $out;
		}

        public static function header_preset_name(){
            $id = !is_archive() ? get_queried_object_id() : 0;
            $name_preset = '';

            // Redux options header
            $name_preset = self::get_option('header_def_js_preset');
            $get_def_name = get_option( 'thegov_set_preset' );
            if( !self::in_array_r($name_preset, get_option( 'thegov_set_preset' ))){
                $name_preset = 'default';
            }

            // Metaboxes options header
            if (class_exists( 'RWMB_Loader' ) && $id !== 0) {
                $customize_header = rwmb_meta('mb_customize_header');
                if (!empty($customize_header) && rwmb_meta('mb_customize_header') != 'default') {
                    $name_preset = rwmb_meta('mb_customize_header');
                    if( !self::in_array_r($name_preset, get_option( 'thegov_set_preset' ))){
                        $name_preset = 'default';
                    }
                }
            }
            return $name_preset;
        }

        public static function render_html ($args) {
            return isset($args) ? $args : '';
        }

        public static function in_array_r($needle, $haystack, $strict = false) {
            if(is_array($haystack)){
                foreach ($haystack as $item) {
                    if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array_r($needle, $item, $strict))) {
                        return true;
                    }
                }
            }

            return false;
        }

        public static function render_sidebars($args = 'page'){
            $output = array();
            $sidebar_style = '';

            $layout = self::get_option( $args.'_sidebar_layout');
            $sidebar = self::get_option( $args.'_sidebar_def');
            $sidebar_width = self::get_option($args.'_sidebar_def_width');
            $sticky_sidebar = self::get_option($args.'_sidebar_sticky');
            $sidebar_gap = self::get_option($args.'_sidebar_gap');
            $sidebar_class = $sidebar_style = '';
            $id = !is_archive() ? get_queried_object_id() : 0;

            $thegov_core = class_exists('Thegov_Core');

            if( is_archive() || is_search() || is_home() || is_page()){
                if(!$thegov_core){
                    if(is_active_sidebar( 'sidebar_main-sidebar' )){
                        $layout = 'right';
                        $sidebar = 'sidebar_main-sidebar';
                        $sidebar_width = 9;
                    }

                }
            }

            if(function_exists('is_shop') &&  is_shop()){
                if(!$thegov_core){
                    if(is_active_sidebar( 'shop_products' )){
                        $layout = 'right';
                        $sidebar = 'shop_products';
                        $sidebar_width = 9;
                    }else{
                        $column = 12;
                        $sidebar = '';
                        $layout = 'none';
                    }
                }
            }

            if(is_single()){
                if(!$thegov_core){
                    if(function_exists('is_product') && is_product()){
                        if(is_active_sidebar( 'shop_single' )){
                            $layout = 'right';
                            $sidebar = 'shop_single';
                            $sidebar_width = 9;
                        }
                    }elseif(is_active_sidebar( 'sidebar_main-sidebar' )){
                        $layout = 'right';
                        $sidebar = 'sidebar_main-sidebar';
                        $sidebar_width = 9;
                    }
                }
            }

            if ( class_exists( 'RWMB_Loader' ) && 0 !== $id ) {
                $mb_layout = rwmb_meta('mb_page_sidebar_layout');
                if (!empty($mb_layout) && $mb_layout != 'default') {
                    $layout = $mb_layout;
                    $sidebar = rwmb_meta('mb_page_sidebar_def');
                    $sidebar_width = rwmb_meta('mb_page_sidebar_def_width');
                    $sticky_sidebar = rwmb_meta('mb_sticky_sidebar');
                    $sidebar_gap = rwmb_meta('mb_sidebar_gap');
                }
            }

            if((bool)$sticky_sidebar){
                wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.min.js', array(), false, false);
                $sidebar_class .= 'sticky-sidebar';
            }

            if (isset($sidebar_gap) && $sidebar_gap != 'def' && $layout != 'default') {
                $layout_pos = $layout == 'left' ? 'right' : 'left';
                $sidebar_style = 'style="padding-'.$layout_pos.': '.$sidebar_gap.'px;"';
            }

            $column = 12;
            if ( $layout == 'left' || $layout == 'right' ) {
                $column = (int) $sidebar_width;
            }else{
                $sidebar = '';
            }

            //GET Params sidebar
            if(isset($_GET['shop_sidebar']) && !empty($_GET['shop_sidebar'])){
                $layout = $_GET['shop_sidebar'];
                $sidebar = 'shop_products';
                $column = 9;
            }

            if(!is_active_sidebar( $sidebar )){
                $column = 12;
                $sidebar = '';
                $layout = 'none';
            }

            $output['column'] = $column;
            $output['row_class'] = $layout != 'none' ? ' sidebar_'.esc_attr($layout) : '';
            $output['container_class'] = $layout != 'none' ? ' wgl-content-sidebar' : '';
            $output['layout'] = $layout;
            $output['content'] = '';

            if ($layout == 'left' || $layout == 'right') {
                    $output['content'] .= '<div class="sidebar-container '.$sidebar_class.' wgl_col-'.(12 - (int)$column).'" '.$sidebar_style.'>';
                        if (is_active_sidebar( $sidebar )) {
                            $output['content'] .= "<aside class='sidebar'>";
                                ob_start();
                                    dynamic_sidebar( $sidebar );
                                $output['content'] .= ob_get_clean();
                            $output['content'] .= "</aside>";
                        }
                    $output['content'] .= "</div>";
            }
            return $output;
        }

        public static function posted_meta_on(){
            global $post;
            $text_string = '<span><time class="entry-date published" datetime="%1$s">%2$s</time></span><span>' . esc_html__('Published in', 'thegov') . ' <a href="%3$s" rel="gallery">%4$s</a></span>';

            echo sprintf($text_string,
                esc_attr(get_the_date('c')),
                esc_html(get_the_date()),
                esc_url(get_permalink($post->post_parent)),
                esc_html(get_the_title($post->post_parent))
            );

            printf(
                '<span class="author vcard">%1$s</span>',
                sprintf(
                    '<a class="url fn n" href="%1$s">%2$s</a>',
                    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                    esc_html( get_the_author() )
                )
            );

            $metadata = wp_get_attachment_metadata();

            if ( $metadata ) {
                printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s" title="%2$s">%1$s %3$s &times; %4$s</a></span>',
                    esc_html_x( 'Full size', 'Used before full size attachment link.', 'thegov' ),
                    esc_url( wp_get_attachment_url() ),
                    esc_attr( absint( $metadata['width'] ) ),
                    esc_attr( absint( $metadata['height'] ) )
                );
            }

            $allowed_html = array(
                'span' => array(
                    'class' => true,
                ),
                'br' => array(),
                'em' => array(),
                'strong' => array()
            );
            edit_post_link(
                sprintf(
                    /* translators: %s: Name of current post */
                    wp_kses( __( 'Edit<span class="screen-reader-text"> "%s"</span>', 'thegov' ), $allowed_html ) ,
                        get_the_title()
                    ),'<span class="edit-link">','</span>');
        }

        public static function hexagon_html($fill = '#fff' , $shadow = false){

            $rgb = self::hexToRGB($fill);
            $svg_shadow = (bool)$shadow ? 'filter: drop-shadow(4px 5px 4px rgba('.$rgb.',0.3));' : '';

            $output = '<div class="thegov_hexagon"><svg style="'.esc_attr($svg_shadow).' fill: '.esc_attr($fill).';" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 177.4 197.4"><path d="M0,58.4v79.9c0,6.5,3.5,12.6,9.2,15.8l70.5,40.2c5.6,3.2,12.4,3.2,18,0l70.5-40.2c5.7-3.2,9.2-9.3,9.2-15.8V58.4 c0-6.5-3.5-12.6-9.2-15.8L97.7,2.4c-5.6-3.2-12.4-3.2-18,0L9.2,42.5C3.5,45.8,0,51.8,0,58.4z"/></svg></div>';

            return $output;
        }

        public static function enqueue_css( $style ) {
            if(!empty($style)){
                ob_start();
                    echo self::render_html($style);
                $css = ob_get_clean();
                $css = apply_filters( 'thegov_enqueue_shortcode_css', $css, $style );
            }
        }

        public static function render_html_attributes( array $attributes ) {
            $rendered_attributes = [];

            foreach ( $attributes as $attribute_key => $attribute_values ) {
                if ( is_array( $attribute_values ) ) {
                    $attribute_values = implode( ' ', $attribute_values );
                }

                $rendered_attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( $attribute_values ) );
            }

            return implode( ' ', $rendered_attributes );
        }

        /**
         * Check licence activation
         */
        public static function wgl_theme_activated(){
            $licence_key = get_option( 'wgl_licence_validated' );
            $licence_key = empty( $licence_key ) ? get_option( Wgl_Theme_Verify::get_instance()->item_id ) : $licence_key;

            if( !empty($licence_key) ){
                return $licence_key;
            }

            return true;
        }

    }
    new Thegov_Theme_Helper();
}
?>