<?php
namespace WglAddons\Templates;

use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use WglAddons\Includes\Wgl_Loop_Settings;
use WglAddons\Includes\Wgl_Elementor_Helper;
use WglAddons\Includes\Wgl_Carousel_Settings;
use WglAddons\Includes\Wgl_Icons;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
* Wgl Elementor Testimonials Render
*
*
* @class        WglTestimonials
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/

if (!class_exists('WglTestimonials')) {
    class WglTestimonials{

        private static $instance = null;
        public static function get_instance( ) {
            if ( null == self::$instance ) {
                self::$instance = new self( );
            }

            return self::$instance;
        }

        public function render( $self, $atts ){

            $theme_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-custom-color'));
            $header_font_color = esc_attr(\Thegov_Theme_Helper::get_option('header-font')['color']);

            $carousel_options = array();
            extract($atts);

            if ((bool)$use_carousel) {
                // carousel options array
                $carousel_options = array(
                    'slide_to_show' => $posts_per_line,
                    'autoplay' => $autoplay,
                    'autoplay_speed' => $autoplay_speed,
                    'fade_animation' => $fade_animation,
                    'slides_to_scroll' => true,
                    'infinite' => true,
                    'use_pagination' => $use_pagination,
                    'pag_type' => $pag_type,
                    'pag_offset' => $pag_offset,
                    'pag_align' => $pag_align,
                    'custom_pag_color' => $custom_pag_color,
                    'pag_color' => $pag_color,
                    'use_prev_next' => $use_prev_next, 
                    'prev_next_position' => $prev_next_position,
                    'custom_prev_next_color' => $custom_prev_next_color,
                    'prev_next_color' => $prev_next_color,
                    'prev_next_color_hover' => $prev_next_color_hover,
                    'prev_next_bg_idle' => $prev_next_bg_idle,
                    'prev_next_bg_hover' => $prev_next_bg_hover,
                    'custom_resp' => $custom_resp,
                    'resp_medium' => $resp_medium,
                    'resp_medium_slides' => $resp_medium_slides,
                    'resp_tablets' => $resp_tablets,
                    'resp_tablets_slides' => $resp_tablets_slides,
                    'resp_mobile' => $resp_mobile,
                    'resp_mobile_slides' => $resp_mobile_slides,
                );

                wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.min.js', array(), false, false);
            }

            $content =  '';


            switch ($posts_per_line) {
                case '1': $col = 12;    break;  
                case '2': $col = 6;     break;
                case '3': $col = 4;     break;
                case '4': $col = 3;     break;
                case '5': $col = '1/5'; break;
            }

            // Wrapper classes
            $self->add_render_attribute( 'wrapper', 'class', [ 'wgl-testimonials', 'type-'.$item_type, ' alignment_'.$item_align  ] );
            if((bool)$hover_animation){
                $self->add_render_attribute( 'wrapper', 'class', 'hover_animation' );
            }

            // Image styles
            $designed_img_width = 140; // define manually
            $image_size = isset($image_size['size']) ? $image_size['size'] : '';
            $image_width_crop = !empty($image_size) ? $image_size*2 : $designed_img_width*2;
            $image_width = 'width: '.(!empty($image_size) ? esc_attr((int)$image_size) : $designed_img_width).'px;';
            
            $testimonials_img_style = $image_width;
            $testimonials_img_style = !empty($testimonials_img_style) ? ' style="'.$testimonials_img_style.'"' : '';

            $values = (array) $list; 
            $item_data = array();
            foreach ( $values as $data ) {
                $new_data = $data;
                $new_data['thumbnail'] = isset( $data['thumbnail'] ) ? $data['thumbnail'] : '';
                $new_data['quote'] = isset( $data['quote'] ) ? $data['quote'] : '';
                $new_data['author_name'] = isset( $data['author_name'] ) ? $data['author_name'] : '';
                $new_data['author_position'] = isset( $data['author_position'] ) ? $data['author_position'] : '';
                $new_data['link_author'] = isset( $data['link_author'] ) ? $data['link_author'] : '';

                $item_data[] = $new_data;
            }

            foreach ( $item_data as $item_d ) {
                // image styles
                $testimonials_image_src = (aq_resize($item_d['thumbnail']['url'], $image_width_crop, $image_width_crop, true, true, true));

                if (isset($item_d['link_author']['url']) && !empty($item_d['link_author']['url'])) {
                    $self->add_link_attributes('link_author', $item_d['link_author']);
                }

                $link_author = $self->get_render_attribute_string( 'link_author' );
                // outputs
                $name_output = '<'.esc_attr($name_tag).' class="wgl-testimonials_name">';
                    $name_output .= !empty($item_d['link_author']['url']) ? '<a '.implode( ' ', [ $link_author ] ).'>' : '';
                        $name_output .= esc_html($item_d['author_name']);
                    $name_output .= !empty($item_d['link_author']['url']) ? '</a>' : '';
                $name_output .= '</'.esc_attr($name_tag).'>';

                $quote_output = '<'.esc_attr($quote_tag).' class="wgl-testimonials_quote">'.$item_d['quote'].'</'.esc_attr($quote_tag).'>';
                
                $status_output = !empty($item_d['author_position']) ? '<'.esc_attr($position_tag).' class="wgl-testimonials_position">'.esc_html($item_d['author_position']).'</'.esc_attr($position_tag).'>' : '';
                
                
                $image_output = '';

                if (!empty( $testimonials_image_src )) { 
                    $image_output = '<div class="wgl-testimonials_image">';
                        $image_output .= !empty($item_d['link_author']['url']) ? '<a '.implode( ' ', [ $link_author ] ).'>' : '';
                            $image_output .= '<img src="'.esc_url($testimonials_image_src).'" alt="'.esc_attr($item_d['author_name']).' photo" '.$testimonials_img_style.'>';
                        $image_output .= !empty($item_d['link_author']['url']) ? '</a>' : '';
                    $image_output .= '</div>';
                }


                $content .= '<div class="wgl-testimonials-item_wrap'.(!(bool)$use_carousel ? " item wgl_col-".$col : '').'">';
                    switch ($item_type) {
                        case 'author_top':
                            $content .= '<div class="wgl-testimonials_item">';
                                $content .= '<div class="wgl-testimonials-content_wrap">';
                                    $content .= $image_output;
                                    $content .= $quote_output;
                                $content .= '</div>';
                                $content .= '<div class="wgl-testimonials-meta_wrap">';
                                    $content .= '<div class="wgl-testimonials-name_wrap">';
                                        $content .= $name_output;
                                        $content .= $status_output;
                                    $content .= '</div>';
                                $content .= '</div>';
                            $content .= '</div>';
                            break;
                        case 'author_bottom':
                            $content .= '<div class="wgl-testimonials_item">';
                                $content .= '<div class="wgl-testimonials-content_wrap">';
                                    $content .= $quote_output;
                                $content .= '</div>';
                                $content .= '<div class="wgl-testimonials-meta_wrap">';
                                    $content .= $image_output;
                                    $content .= '<div class="wgl-testimonials-name_wrap">';
                                        $content .= $name_output;
                                        $content .= $status_output;
                                    $content .= '</div>';
                                $content .= '</div>';
                            $content .= '</div>';
                            break;
                        case 'inline_top':
                            $content .= '<div class="wgl-testimonials_item">';
                                $content .= '<div class="wgl-testimonials-content_wrap">';
                                    $content .= '<div class="wgl-testimonials-meta_wrap">';
                                        $content .= $image_output;
                                        $content .= '<div class="wgl-testimonials-name_wrap">';
                                            $content .= $name_output;
                                            $content .= $status_output;
                                        $content .= '</div>';
                                    $content .= '</div>';
                                    $content .= $quote_output;    
                                $content .= '</div>';
                            $content .= '</div>';
                            break;
                        case 'inline_bottom':
                            $content .= '<div class="wgl-testimonials_item">';
                                $content .= '<div class="wgl-testimonials-content_wrap">';
                                    $content .= $quote_output;
                                $content .= '</div>';
                                $content .= '<div class="wgl-testimonials-meta_wrap">';
                                    $content .= $image_output;
                                    $content .= '<div class="wgl-testimonials-name_wrap">';
                                        $content .= $name_output;
                                        $content .= $status_output;
                                    $content .= '</div>';
                                $content .= '</div>';
                            $content .= '</div>';
                            break;
                    }
                $content .= '</div>';
            }

            $wrapper = $self->get_render_attribute_string( 'wrapper' );

            $output = '<div  '.implode( ' ', [ $wrapper ] ).'>';
                if((bool)$use_carousel) {
                    $output .= Wgl_Carousel_Settings::init($carousel_options, $content, false);
                }else{
                    $output .= $content;
                }
            $output .= '</div>';

            return $output;
            
        }

    }
}