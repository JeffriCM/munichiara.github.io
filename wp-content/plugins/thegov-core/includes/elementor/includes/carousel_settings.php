<?php

namespace WglAddons\Includes;

use Elementor\Plugin;
use Elementor\Frontend;

use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use WglAddons\Includes\Wgl_Elementor_Helper;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
* Wgl Elementor Carousel Settings
*
*
* @class        Wgl_Carousel_Settings
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/

if (!class_exists('Wgl_Carousel_Settings')) {
    class Wgl_Carousel_Settings{

        private static $instance = null;
        public static function get_instance( ) {
            if ( null == self::$instance ) {
                self::$instance = new self( );
            }

            return self::$instance;
        }

        public static function options($self, $array = array()){
            
            $theme_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-custom-color'));
            $main_font_color = esc_attr(\Thegov_Theme_Helper::get_option('main-font')['color']);
            $header_font_color = esc_attr(\Thegov_Theme_Helper::get_option('header-font')['color']);
            
            $desctop_width = get_option('elementor_container_width');
            $desctop_width = !empty($desctop_width) ? $desctop_width : '1140';

            $tablet_width = get_option('elementor_viewport_lg');
            $tablet_width = !empty($tablet_width) ? $tablet_width : '1025';
            
            $mobile_width = get_option('elementor_viewport_md');
            $mobile_width = !empty($mobile_width) ? $mobile_width : '768';
            /* Start Carousel General Settings Section */
            
            $self->start_controls_section('wgl_carousel_section',
                array(
                    'label'         => esc_html__('Carousel Options', 'thegov-core'),
                )
            );

            $self->add_control('use_carousel',
                array(
                    'label'        => esc_html__('Use Carousel','thegov-core' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'On', 'thegov-core' ),
                    'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                    'return_value' => 'yes',
                )
            );        

            $self->add_control(
                'autoplay',
                array(
                    'label'        => esc_html__('Autoplay','thegov-core' ),
                    
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'On', 'thegov-core' ),
                    'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                    'return_value' => 'yes',
                    'condition'     => [
                        'use_carousel'   => 'yes',
                    ]
                )
            );  

            $self->add_control('autoplay_speed',
                array(
                    'label'       => esc_html__('Autoplay Speed', 'thegov-core'),
                    'type'        => Controls_Manager::NUMBER,
                    'default'     => '3000',
                    'min'         => 1,
                    'step'        => 1,
                    'condition'     => [
                        'autoplay'  => 'yes',
                    ]
                )
            );       

            $self->add_control('fade_animation',
                array(
                    'label'        => esc_html__('Fade Animation','thegov-core' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'On', 'thegov-core' ),
                    'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                    'return_value' => 'yes',
                    'condition'         => [
                        'use_carousel'   => 'yes',
                        'posts_per_line' => '1',
                    ]
                )
            );  

            $self->add_control(
                'slides_to_scroll',
                array(
                    'label'        => esc_html__('Slide per single item at a time','thegov-core' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'On', 'thegov-core' ),
                    'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                    'return_value' => 'yes',
                    'condition'     => [
                        'use_carousel'   => 'yes',
                    ]
                )
            );          

            $self->add_control(
                'infinite',
                array(
                    'label'        => esc_html__('Infinite Loop Sliding','thegov-core' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'On', 'thegov-core' ),
                    'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                    'return_value' => 'yes',
                    'condition'     => [
                        'use_carousel'   => 'yes',
                    ]
                )
            );           

            $self->add_control(
                'center_mode',
                array(
                    'label'        => esc_html__('Center Mode','thegov-core' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'On', 'thegov-core' ),
                    'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                    'return_value' => 'yes',
                    'condition'     => [
                        'use_carousel'   => 'yes',
                    ]
                )
            );        

            $self->add_control(
                'use_pagination',
                array(
                    'label'        => esc_html__('Add Pagination control','thegov-core' ),
                    
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'On', 'thegov-core' ),
                    'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                    'return_value' => 'yes',
                    'condition'     => [
                        'use_carousel'   => 'yes',
                    ]
                )
            ); 

            $self->add_control('pag_type',
                array(
                    'label'         => esc_html__( 'Pagination Type', 'thegov-core' ),
                    'type'          => 'wgl-radio-image',
                    'options'       => [
                        'circle'      => [
                            'title'=> esc_html__( 'Circle', 'thegov-core' ),
                            'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/pag_circle.png',
                        ],                    
                        'circle_border' => [
                            'title'=> esc_html__( 'Empty Circle', 'thegov-core' ),
                            'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/pag_circle_border.png',
                        ],
                        'square'    => [
                            'title'=> esc_html__( 'Square', 'thegov-core' ),
                            'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/pag_square.png',
                        ],   
                        'square_border'    => [
                            'title'=> esc_html__( 'Empty Square', 'thegov-core' ),
                            'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/pag_square_border.png',
                        ],                   
                        'line'    => [
                            'title'=> esc_html__( 'Line', 'thegov-core' ),
                            'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/pag_line.png',
                        ],                    
                        'line_circle'    => [
                            'title'=> esc_html__( 'Line - Circle', 'thegov-core' ),
                            'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/pag_line_circle.png',
                        ],                    
                    ],
                    'default'       => 'circle_border',     
                    'condition'     => [
                        'use_pagination'  => 'yes',
                        'use_carousel'   => 'yes',
                    ]       
                )
            );  

            $self->add_control('pag_align',
                array(
                    'label'             => esc_html__('Pagination Aligning', 'thegov-core'),
                    'type'              => Controls_Manager::SELECT,
                    'options'           => [
                        'left'          => esc_html__('Left', 'thegov-core'),
                        'right'         => esc_html__('Right', 'thegov-core'),
                        'center'        => esc_html__('Center', 'thegov-core'), 
                    ],
                    'default'           => 'center',
                    'condition'         => [
                        'use_pagination'   => 'yes',
                    ] 
                )
            );

            $self->add_control('pag_offset',
                array(
                    'label'       => esc_html__('Pagination Top Offset', 'thegov-core'),
                    'type'        => Controls_Manager::NUMBER,
                    'min'         => -500,
                    'step'        => 1,
                    'default'     => 70,
                    'condition'     => [
                        'use_pagination'  => 'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wgl-carousel .slick-dots' => 'margin-top: {{VALUE}}px;',
                    ],
                ) 
            );

            $self->add_control(
                'custom_pag_color',
                array(
                    'label'        => esc_html__('Custom Pagination Color','thegov-core' ),
                    
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'On', 'thegov-core' ),
                    'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                    'return_value' => 'yes',
                    'condition'     => [
                        'use_pagination'  => 'yes',
                    ]
                )
            ); 
 
            $self->add_control(
                'pag_color',
                array(
                    'label' => esc_html__( 'Color', 'thegov-core' ),
                    'type' => Controls_Manager::COLOR,
                    'default'      => esc_attr($header_font_color),
                    'condition'     => [
                        'custom_pag_color'  => 'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li button' => 'background: {{VALUE}};',
                        '{{WRAPPER}} .wgl-carousel.pagination_line .slick-dots li button:before' => 'background: {{VALUE}};',
                        '{{WRAPPER}} .wgl-carousel.pagination_square .slick-dots li' => 'background: {{VALUE}};',
                        '{{WRAPPER}} .wgl-carousel.pagination_circle_border .slick-dots li.slick-active button' => 'border-color: {{VALUE}};',
                        '{{WRAPPER}} .wgl-carousel.pagination_circle_border .slick-dots li button:before' => 'background: {{VALUE}};',
                    ],
                )
            );

            $self->add_control(
                'use_prev_next',
                array(
                    'label'        => esc_html__('Add Prev/Next buttons','thegov-core' ),
                    
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'On', 'thegov-core' ),
                    'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                    'return_value' => 'yes',
                    'condition'     => [
                        'use_carousel'   => 'yes',
                    ]
                )
            );    

            $self->add_control('prev_next_position',
                array(
                    'label'             => esc_html__('Arrows Positioning', 'thegov-core'),
                    'type'              => Controls_Manager::SELECT,
                    'options'           => [
                        ''              => esc_html__('Opposite each other', 'thegov-core'),
                        'left'         => esc_html__('Bottom left corner', 'thegov-core'),
                    ],
                    'condition'         => [
                        'use_prev_next'   => 'yes',
                    ] 
                )
            );     

            $self->add_control('custom_prev_next_color',
                array(
                    'label'        => esc_html__('Customize Arrows Colors','thegov-core' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'On', 'thegov-core' ),
                    'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                    'return_value' => 'yes',
                    'condition'         => [
                        'use_prev_next'   => 'yes',
                    ]
                )
            );

            $self->start_controls_tabs( 'arrows_style' );

            $self->start_controls_tab(
                'arrows_button_normal',
                [
                    'label' => esc_html__( 'Normal', 'thegov-core' ),
                    'condition'         => [
                        'custom_prev_next_color'   => 'yes',
                    ],
                ]
            );

            $self->add_control(
                'prev_next_color',
                array(
                    'label' => esc_html__( 'Arrows Color', 'thegov-core' ),
                    'type'  =>  Controls_Manager::COLOR,
                    'default'   => $header_font_color,
                     'condition'         => [
                        'custom_prev_next_color'   => 'yes',
                    ],
                    'dynamic'       => [ 'active' => true ],
                )
            );    
            $self->add_control(
                'prev_next_bg_idle',
                array(
                    'label' => esc_html__( 'Arrows Background Color', 'thegov-core' ),
                    'type'  =>  Controls_Manager::COLOR,
                     'condition'         => [
                        'custom_prev_next_color'   => 'yes',
                    ]
                )
            );   
            $self->end_controls_tab();

            $self->start_controls_tab(
                'arrows_button_hover',
                [
                    'label' => esc_html__( 'Hover', 'thegov-core' ),
                    'condition'         => [
                        'custom_prev_next_color'   => 'yes',
                    ],
                ]
            );

            $self->add_control(
                'prev_next_color_hover',
                array(
                    'label' => esc_html__( 'Arrows Color', 'thegov-core' ),
                    'type'  =>  Controls_Manager::COLOR,
                    'default'   => $header_font_color,
                     'condition'         => [
                        'custom_prev_next_color'   => 'yes',
                    ],
                    'dynamic'       => [ 'active' => true ],
                )
            );              

            $self->add_control(
                'prev_next_bg_hover',
                array(
                    'label' => esc_html__( 'Arrows Background', 'thegov-core' ),
                    'type'  =>  Controls_Manager::COLOR,
                     'condition'         => [
                        'custom_prev_next_color'   => 'yes',
                    ]
                )
            ); 
            $self->end_controls_tab();

            $self->end_controls_tabs();

            $self->add_control('custom_resp',
                array(
                    'label'        => esc_html__('Customize Responsive','thegov-core' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'On', 'thegov-core' ),
                    'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                    'return_value' => 'yes',
                    'condition'         => [
                        'use_carousel'   => 'yes',
                    ]
                )
            );

            $self->add_control(
                'heading_desktop',
                array(
                    'label' => esc_html__( 'Desktop Settings', 'thegov-core' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition'         => [
                        'custom_resp'   => 'yes',
                    ]
                )
            );

            $self->add_control('resp_medium',
                array(
                    'label'             => esc_html__('Desktop Screen Breakpoint', 'thegov-core'),
                    'type'              => Controls_Manager::NUMBER,
                    'default'           => $desctop_width,
                    'min'               => 1,
                    'step'              => 1,
                    'condition'         => [
                        'custom_resp'   => 'yes',
                    ]
                )
            );         

            $self->add_control('resp_medium_slides',
                array(
                    'label'             => esc_html__('Slides to show', 'thegov-core'),
                    'type'              => Controls_Manager::NUMBER,
                    'min'               => 1,
                    'step'              => 1,
                    'condition'         => [
                        'custom_resp'   => 'yes',
                    ]
                )
            );         

            $self->add_control(
                'heading_tablet',
                array(
                    'label' => esc_html__( 'Tablet Settings', 'thegov-core' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition'         => [
                        'custom_resp'   => 'yes',
                    ]
                )
            );

            $self->add_control('resp_tablets',
                array(
                    'label'             => esc_html__('Tablet Screen Breakpoint', 'thegov-core'),
                    'type'              => Controls_Manager::NUMBER,
                    'default'           => $tablet_width,
                    'min'               => 1,
                    'step'              => 1,
                    'condition'         => [
                        'custom_resp'   => 'yes',
                    ]
                )
            );         

            $self->add_control('resp_tablets_slides',
                array(
                    'label'             => esc_html__('Slides to show', 'thegov-core'),
                    'type'              => Controls_Manager::NUMBER,
                    'min'               => 1,
                    'step'              => 1,
                    'condition'         => [
                        'custom_resp'   => 'yes',
                    ]
                )
            );         

            $self->add_control(
                'heading_mobile',
                array(
                    'label' => esc_html__( 'Mobile Settings', 'thegov-core' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition'         => [
                        'custom_resp'   => 'yes',
                    ]
                )
            );

            $self->add_control('resp_mobile',
                array(
                    'label'             => esc_html__('Mobile Screen Breakpoint', 'thegov-core'),
                    'type'              => Controls_Manager::NUMBER,
                    'default'           => $mobile_width,
                    'min'               => 1,
                    'step'              => 1,
                    'condition'         => [
                        'custom_resp'   => 'yes',
                    ]
                )
            );         

            $self->add_control('resp_mobile_slides',
                array(
                    'label'             => esc_html__('Slides to show', 'thegov-core'),
                    'type'              => Controls_Manager::NUMBER,
                    'min'               => 1,
                    'step'              => 1,
                    'condition'         => [
                        'custom_resp'   => 'yes',
                    ]
                )
            );       

            /*End General Settings Section*/
            $self->end_controls_section();
        }

        public static function init($atts, $item = array(), $templates = false){

            $theme_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-custom-color'));
            wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.min.js', array(), false, false);

            extract( shortcode_atts( array(
                // General
                'slide_to_show' => '1',
                'speed' => '300',
                'autoplay' => true,
                'autoplay_speed' => '3000',
                'slides_to_scroll' => false,
                'infinite' => false,
                'adaptive_height' => false,
                'fade_animation' => false,
                'variable_width' => false,
                'extra_class' => '',
                // Navigation
                'use_pagination' => true,
                'use_navigation' => false,
                'pag_type' => 'circle',
                'nav_type' => 'element',
                'pag_offset' => '',
                'pag_align' => 'center',
                'custom_prev_next_offset' => false,
                'prev_next_offset' => '50%',
                'custom_pag_color' => false,
                'pag_color' => $theme_color,
                'center_mode' => false,
                'use_prev_next' => false,
                'prev_next_position' => '',
                'custom_prev_next_color' => false,
                'prev_next_color' => $theme_color,
                'prev_next_color_hover' => $theme_color,
                'prev_next_border_color' => $theme_color,
                'prev_next_bg_idle' => '#ffffff',
                'prev_next_bg_hover' => '#ffffff',
                // Responsive
                'custom_resp' => false,
                'resp_medium' => '1025',
                'resp_medium_slides' => '',
                'resp_tablets' => '800',
                'resp_tablets_slides' => '',
                'resp_mobile' => '480',
                'resp_mobile_slides' => '',
            ), $atts));

            if ((bool)$custom_pag_color || (bool)$custom_prev_next_color || !empty($pag_offset) || !empty($prev_next_offset)) {
                $carousel_id = uniqid( "thegov_carousel_" );
            }
            $carousel_id_attr = isset($carousel_id) ? ' id='.$carousel_id : '';

            // Custom styles
            ob_start();
                if ( (bool)$custom_prev_next_color ) {
                    $bg_color_idle = !empty($prev_next_bg_idle) ? esc_attr($prev_next_bg_idle) : 'transparent';
                    $bg_color_hover = !empty($prev_next_bg_hover) ? esc_attr($prev_next_bg_hover) : 'transparent';
                    $bg_color_idle_shadow = '0px 9px 30px 0px rgba('.(!empty($prev_next_bg_idle) ? \Thegov_Theme_Helper::hexToRGB($prev_next_bg_idle).',0.4' : '0,0,0,0').')';
                    $bg_color_hover_shadow = '0px 9px 30px 0px rgba('.(!empty($prev_next_bg_hover) ? \Thegov_Theme_Helper::hexToRGB($prev_next_bg_hover).',0.4' : '0,0,0,0').')';
                    echo "#$carousel_id .slick-arrow {
                              border-color: ".(!empty($prev_next_border_color) ? esc_attr($prev_next_border_color) : 'transparent').";
                              background-color: ".$bg_color_idle.";
                              box-shadow: ".$bg_color_idle_shadow.";
                          }
                          #$carousel_id .slick-arrow:after {
                              color: ".(!empty($prev_next_color) ? esc_attr($prev_next_color) : 'transparent').";
                          }";
                    echo "#$carousel_id .slick-arrow:hover {
                              background-color: $bg_color_hover;
                              box-shadow: $bg_color_hover_shadow;
                          }
                          #$carousel_id .slick-arrow:hover:after {
                              color: ".(!empty($prev_next_color_hover) ? esc_attr($prev_next_color_hover) : 'transparent').";
                          }
                          #$carousel_id .slick-arrow:hover:before {
                              opacity: 0;
                          }";
                }
            $styles = ob_get_clean(); 
            Wgl_Elementor_Helper::enqueue_css($styles);
            
            switch ($slide_to_show) {
                case '2':
                    $responsive_medium = 2;
                    $responsive_tablets = 2;
                    $responsive_mobile = 1;
                    break;
                case '3':
                    $responsive_medium = 3;
                    $responsive_tablets = 2;
                    $responsive_mobile = 1;
                    break;
                case '4':
                case '5':
                case '6':
                    $responsive_medium = 4;
                    $responsive_tablets = 2;
                    $responsive_mobile = 1;
                    break;
                default:
                    $responsive_medium = 1;
                    $responsive_tablets = 1;
                    $responsive_mobile = 1;
                    break;
            }

            // If custom responsive
            if ($custom_resp) {
                $responsive_medium = !empty($resp_medium_slides) ? (int)$resp_medium_slides : $responsive_medium;
                $responsive_tablets = !empty($resp_tablets_slides) ? (int)$resp_tablets_slides : $responsive_tablets;
                $responsive_mobile = !empty($resp_mobile_slides) ? (int)$resp_mobile_slides : $responsive_mobile;
            }

            if ( $slides_to_scroll ) {
                $responsive_sltscrl_medium = $responsive_sltscrl_tablets = $responsive_sltscrl_mobile = 1;
            } else {
                $responsive_sltscrl_medium = $responsive_medium;
                $responsive_sltscrl_tablets = $responsive_tablets;
                $responsive_sltscrl_mobile = $responsive_mobile;
            }

            $data_array = array(); 
            $data_array['slidesToShow'] = (int)$slide_to_show;
            $data_array['slidesToScroll'] = $slides_to_scroll ? 1 : (int)$slide_to_show;
            $data_array['infinite'] = $infinite ? true : false;
            $data_array['variableWidth'] = $variable_width ? true : false;

            $data_array['autoplay'] = $autoplay ? true : false;
            $data_array['autoplaySpeed'] = $autoplay_speed ? $autoplay_speed : '';
            $data_array['speed'] = $speed ? (int)$speed : '300';
            if ( (bool)$center_mode ) {
                $data_array['centerMode'] = $center_mode ? true : false;
                $data_array['centerPadding'] = '0px';   
            }


            $data_array['arrows'] = $use_prev_next ? true : false;
            $data_array['dots'] = $use_pagination ? true : false;
            $data_array['adaptiveHeight'] = $adaptive_height ? true : false;

            // Responsive settings
            $data_array['responsive'][0]['breakpoint'] = (int)$resp_medium;
            $data_array['responsive'][0]['settings']['slidesToShow'] = (int)esc_attr($responsive_medium);
            $data_array['responsive'][0]['settings']['slidesToScroll'] = (int)esc_attr($responsive_sltscrl_medium);

            $data_array['responsive'][1]['breakpoint'] = (int)$resp_tablets;
            $data_array['responsive'][1]['settings']['slidesToShow'] = (int)esc_attr($responsive_tablets);
            $data_array['responsive'][1]['settings']['slidesToScroll'] = (int)esc_attr($responsive_sltscrl_tablets);

            $data_array['responsive'][2]['breakpoint'] = (int)$resp_mobile;
            $data_array['responsive'][2]['settings']['slidesToShow'] = (int)esc_attr($responsive_mobile);
            $data_array['responsive'][2]['settings']['slidesToScroll'] = (int)esc_attr($responsive_sltscrl_mobile);

            $prev_next_position_class = ((bool)$use_prev_next && !empty($prev_next_position)) ? ' prev_next_pos_'.$prev_next_position : '';
            $data_attribute = " data-slick='".json_encode($data_array, true)."'";

            // Classes
            $carousel_wrap_classes = $use_pagination ? ' pagination_'.$pag_type : '';
            $carousel_wrap_classes .= $use_navigation ? ' navigation_'.$nav_type : '';
            $carousel_wrap_classes .= ' pag_align_'.$pag_align;
            $carousel_wrap_classes .= $prev_next_position_class;

            $carousel_classes = (bool)$fade_animation ? ' fade_slick' : '';

            // Render
            $output = '<div class="wgl-carousel_wrapper">';
                $output .= '<div'.$carousel_id_attr.' class="wgl-carousel'.esc_attr($carousel_wrap_classes).'">';
                    $output .= '<div class="wgl-carousel_slick'.$carousel_classes.'"'.$data_attribute.'>';   
                        
                        if(!empty($templates)){
                            
                            $wgl_frontend = new Frontend;

                            $output .= ob_start();
                                
                                foreach( $item as $id ) :
                                 ?>
                                    <div class="item">
                                        <?php echo $wgl_frontend->get_builder_content_for_display( $id, true ); ?>
                                    </div>
                                <?php 
                                endforeach; 
                            $output .= ob_get_clean();
                        
                        }else{

                            $output .= $item;
                        
                        }
                        
                    $output .= '</div>';
                $output .= '</div>';
            $output .= '</div>';

            return $output;
        }
              
    }
    new Wgl_Carousel_Settings();
}

?>