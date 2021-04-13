<?php

namespace WglAddons\Includes;

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
* Wgl Elementor Helper Settings
*
*
* @class        Wgl_Elementor_Helper
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/

if (!class_exists('Wgl_Elementor_Helper')) {
    class Wgl_Elementor_Helper{

        private static $instance = null;
        public static function get_instance( ) {
            
            if ( null == self::$instance ) {
                self::$instance = new self( );
            }

            return self::$instance;
        }

        public static function enqueue_css( $style ) {
            if(!(bool) Plugin::$instance->editor->is_edit_mode()){
                if(!empty($style)){
                    ob_start();             
                        echo $style;
                    $css = ob_get_clean();
                    $css = apply_filters( 'thegov_enqueue_shortcode_css', $css, $style );   

                    return $css;
                }
            }else{
                echo '<style>'.esc_attr($style).'</style>';
            }
        }

        public function get_elementor_templates() {
            
            $options = array();

            $_templates = get_posts( array(
                'post_type' => 'elementor_library',
                'posts_per_page' => -1,
            ));
            
            if ( ! empty( $_templates ) && ! is_wp_error( $_templates ) ) {
                
                foreach ( $_templates as $_template ) {
                    $options[ $_template->ID ] = $_template->post_title;
                }
                
                update_option( 'temp_count', $options );
                
                return $options;
            }
        }
              
    }
    new Wgl_Elementor_Helper;
}
?>