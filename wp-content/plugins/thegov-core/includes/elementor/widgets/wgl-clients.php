<?php
namespace WglAddons\Widgets;

use WglAddons\Includes\Wgl_Icons;
use WglAddons\Includes\Wgl_Carousel_Settings;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Wgl_Clients extends Widget_Base {

    public function get_name() {
        return 'wgl-clients';
    }

    public function get_title() {
        return esc_html__('Wgl Clients', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-clients';
    }

    public function get_script_depends() {
        return [
            'slick',
        ];
    }

    public function get_categories() {
        return [ 'wgl-extensions' ];
    }

    // Adding the controls fields for the premium title
    // This will controls the animation, colors and background, dimensions etc
    protected function register_controls() {
        $theme_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-custom-color'));
        $main_font_color = esc_attr(\Thegov_Theme_Helper::get_option('main-font')['color']);
        $header_font_color = esc_attr(\Thegov_Theme_Helper::get_option('header-font')['color']);

        /*-----------------------------------------------------------------------------------*/
        /*  Content
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section('wgl_clients_section',
            array(
                'label'         => esc_html__('Clients Settings', 'thegov-core'),
            )
        );

        $this->add_control('item_grid',
            array(
                'label'             => esc_html__('Columns Amount', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    '1'          => esc_html__('One Column', 'thegov-core'),
                    '2'          => esc_html__('Two Columns', 'thegov-core'),
                    '3'          => esc_html__('Three Columns', 'thegov-core'),
                    '4'          => esc_html__('Four Columns', 'thegov-core'),
                    '5'          => esc_html__('Five Columns', 'thegov-core'),
                    '6'          => esc_html__('Six Columns', 'thegov-core'),
                ],
                'default'           => '1',
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail',
            array(
                'label'       => esc_html__( 'Thumbnail', 'thegov-core' ),
                'type'        => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            )
        );

        $repeater->add_control(
            'hover_thumbnail',
            array(
                'label'       => esc_html__( 'Hover Thumbnail', 'thegov-core' ),
                'type'        => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => [
                    'url' => '',
                ],
                'description' => esc_html__( 'Need for \'Exchange Images\' and \'Shadow\' animations only.', 'thegov-core' ),
            )
        );

        $repeater->add_control('client_link',
            array(
                'label'             => esc_html__('Add Link', 'thegov-core'),
                'type'              => Controls_Manager::URL,
                'label_block' => true,
            )
        );

        $this->add_control(
            'list',
            array(
                'label'   => esc_html__( 'Items', 'thegov-core' ),
                'type'    => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
            )
        );

        $this->add_control('item_anim',
            array(
                'label'             => esc_html__('Thumbnail Animation', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'none'          => esc_html__('None', 'thegov-core'),
                    'grayscale'          => esc_html__('Grayscale', 'thegov-core'),
                    'opacity'          => esc_html__('Opacity', 'thegov-core'),
                    'zoom'          => esc_html__('Zoom', 'thegov-core'),
                    'contrast'          => esc_html__('Contrast', 'thegov-core'),
                    'blur'          => esc_html__('Blur', 'thegov-core'),
                    'invert'          => esc_html__('Invert', 'thegov-core'),
                    'ex_images'          => esc_html__('Exchange Images', 'thegov-core'),
                    'ex_images_ver'          => esc_html__('Exchange Images Vertical', 'thegov-core'),
                    'ex_images_bg'          => esc_html__('Exchange Images with Background', 'thegov-core'),
                ],
                'default' => 'ex_images_ver',
            )
        );

        $this->add_control(
            'height',
            array(
                'label' => esc_html__( 'Custom Items Height', 'thegov-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'size' => 180,
                    'unix' => 'px'
                ],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'item_anim'   => 'ex_images_bg',
                ],
            )
        );

        $this->add_control(
            'item_align',
            array(
                'label' => esc_html__( 'Alignment', 'thegov-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'thegov-core' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'thegov-core' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'thegov-core' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'label_block' => false,
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'justify-content: {{VALUE}};',
                ],
            )
        );

        $this->add_control(
            'item_align_v',
            array(
                'label' => esc_html__( 'Vertical Alignment', 'thegov-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start'      => [
                        'title' => esc_html__('Top', 'thegov-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center'          => [
                        'title' => esc_html__('Center', 'thegov-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end'         => [
                        'title' => esc_html__('Bottom', 'thegov-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'label_block' => false,
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .wgl-clients' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .slick-track' => 'align-items: {{VALUE}}; display: flex;',
                ],
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Carousel options
        /*-----------------------------------------------------------------------------------*/

        Wgl_Carousel_Settings::options($this);

        /*-----------------------------------------------------------------------------------*/
        /*  Carousel styles
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Carousel', 'thegov-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

        $this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Image Border Radius', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .image_wrapper img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .image_wrapper img',
			]
        );

        $this->add_responsive_control(
			'slick_padding',
			[
				'label' => esc_html__( 'Padding', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .slick-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Carousel Items styles
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
			'items_style',
			[
				'label' => esc_html__( 'Items', 'thegov-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'item_anim'   => 'ex_images_bg',
                ],
			]
        );

        $this->start_controls_tabs( 'item_colors_style' );

        $this->start_controls_tab(
            'item_colors',
            [
                'label' => esc_html__( 'Normal', 'thegov-core' ),
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => esc_html__( 'Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'item_hover',
            [
                'label' => esc_html__( 'Hover', 'thegov-core' ),
            ]
        );

        $this->add_control(
            'hover_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .clients_image:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'button_border',
                'label'    => esc_html__( 'Border Type', 'thegov-core' ),
                'selector' => '{{WRAPPER}} .clients_image',
            )
        );

        $this->end_controls_section();

    }

    protected function render() {

        $content = '';
        $carousel_options = array();
        $settings = $this->get_settings_for_display();
        extract($settings);

        if ((bool)$use_carousel) {
            // carousel options array
            $carousel_options = array(
                'slide_to_show' => $item_grid,
                'autoplay' => $autoplay,
                'autoplay_speed' => $autoplay_speed,
                'fade_animation' => $fade_animation,
                'slides_to_scroll' => $slides_to_scroll,
                'infinite' => true,
                'rtl' => true,
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

        $this->add_render_attribute( 'clients', [
			'class' => [
                'wgl-clients',
                'clearfix',
                'anim-'.$item_anim,
                'items-'.$item_grid,
            ],
            'data-carousel' => $use_carousel
        ] );

        foreach ( $settings['list'] as $index => $item ) {

            if ( !empty( $item[ 'client_link' ][ 'url' ] ) ) {
                $client_link = $this->get_repeater_setting_key( 'client_link', 'list' , $index );
				$this->add_render_attribute($client_link, 'class', [
                    'image_link',
                    'image_wrapper'
                ]);
				$this->add_link_attributes($client_link, $item['client_link']);
            }

            $client_image = $this->get_repeater_setting_key( 'thumbnail', 'list' , $index );
            $this->add_render_attribute( $client_image, [
                'class' => 'main_image',
                'src' => esc_url($item['thumbnail']['url']),
                'alt' => Control_Media::get_image_alt( $item['thumbnail'] ),
            ] );

            $client_hover_image = $this->get_repeater_setting_key( 'hover_thumbnail', 'list' , $index );
            $this->add_render_attribute( $client_hover_image, [
                'class' => 'hover_image',
                'src' => esc_url($item['hover_thumbnail']['url']),
                'alt' => Control_Media::get_image_alt( $item['hover_thumbnail'] ),
            ] );

            ob_start();

            ?><div class="clients_image"><?php
                if ( !empty($item['client_link']['url']) ) : ?><a <?php echo $this->get_render_attribute_string( $client_link ); ?>><?php
                else : ?><div class="image_wrapper"><?php
                endif;
                    if (!empty($item['hover_thumbnail']['url']) && ($item_anim == 'ex_images' || $item_anim == 'ex_images_bg' || $item_anim == 'ex_images_ver')) : ?><img <?php echo $this->get_render_attribute_string( $client_hover_image ); ?> /><?php endif;
                    ?><img <?php echo $this->get_render_attribute_string( $client_image ); ?> /><?php
                if ( !empty($item['client_link']['url']) ) : ?></a><?php
                else : ?></div><?php
                endif;
            ?></div> <?php

            $content .= ob_get_clean();
        }

        ?><div <?php echo $this->get_render_attribute_string( 'clients' ); ?>><?php
            if((bool)$use_carousel) : echo Wgl_Carousel_Settings::init($carousel_options, $content, false);
            else : echo $content;
            endif;
        ?></div><?php

    }

}