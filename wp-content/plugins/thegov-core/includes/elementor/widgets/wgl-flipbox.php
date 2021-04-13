<?php
namespace WglAddons\Widgets;

use WglAddons\Includes\Wgl_Icons;
use WglAddons\Includes\Wgl_Carousel_Settings;
use WglAddons\Includes\Wgl_Elementor_Helper;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;


if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Wgl_Flipbox extends Widget_Base {

    public function get_name() {
        return 'wgl-flipbox';
    }

    public function get_title() {
        return esc_html__('Wgl Flipbox', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-flipbox';
    }

    public function get_categories() {
        return [ 'wgl-extensions' ];
    }

    // Adding the controls fields for the premium title
    // This will controls the animation, colors and background, dimensions etc
    protected function register_controls() {
        $theme_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-custom-color'));
        $second_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-secondary-color'));
        $third_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-third-color'));
        $header_font_color = esc_attr(\Thegov_Theme_Helper::get_option('header-font')['color']);
        $main_font_color = esc_attr(\Thegov_Theme_Helper::get_option('main-font')['color']);

        /*-----------------------------------------------------------------------------------*/
        /*  Build Icon/Image Box
        /*-----------------------------------------------------------------------------------*/


        $this->start_controls_section(
            'section_flipbox_settings',
            [
                'label' => esc_html__( 'Flipbox Settings', 'thegov-core' ),
            ]
        );

        $this->add_control(
            'flip_direction',
            [
                'label' => esc_html__( 'Border Type', 'Border Control', 'thegov-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'flip_right' => esc_html__( 'Flip to Right', 'thegov-core' ),
                    'flip_left' => esc_html__( 'Flip to Left', 'thegov-core' ),
                    'flip_top' => esc_html__( 'Flip to Top', 'thegov-core' ),
                    'flip_bottom' => esc_html__( 'Flip to Bottom', 'thegov-core' ),
                ],
                'default' => 'flip_right',
            ]
        );

        $this->add_control(
            'alignment',
            array(
                'label' => esc_html__( 'Alignment', 'thegov-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'thegov-core' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'thegov-core' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'thegov-core' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_wrap' => 'text-align: {{VALUE}};',
                ],
            )
        );

        $this->add_control(
            'flipbox_height',
            array(
                'label' => esc_html__( 'Custom Flipbox Height)', 'thegov-core' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 150,
                'step' => 10,
                'default' => 320,
                'description' => esc_html__( 'Enter value in pixels', 'thegov-core' ),
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox' => 'height: {{VALUE}}px;',
                ],
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Build Icon/Image Box
        /*-----------------------------------------------------------------------------------*/


        $this->start_controls_section(
            'section_flipbox_icon',
            [
                'label' => esc_html__( 'Flipbox Icon', 'thegov-core' ),
            ]
        );

        $this->start_controls_tabs(
            'flipbox_icon'
        );

        $this->start_controls_tab(
            'flipbox_front_icon',
            [
                'label' => esc_html__( 'Front', 'thegov-core' ),
            ]
        );

        Wgl_Icons::init( $this, array( 'label' => esc_html__('Flipbox ', 'thegov-core'), 'output' => '', 'section' => false, 'prefix' => 'front_') );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'flipbox_back_icon',
            [
                'label' => esc_html__( 'Back', 'thegov-core' ),
            ]
        );

        Wgl_Icons::init( $this, array( 'label' => esc_html__('Flipbox ', 'thegov-core'), 'output' => '', 'section' => false,'prefix' => 'back_') );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Content
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section('wgl_ib_content',
            array(
                'label'         => esc_html__('Flipbox Content', 'thegov-core'),
            )
        );

        $this->start_controls_tabs(
            'flipbox_content'
        );

        $this->start_controls_tab(
            'flipbox_front_content',
            [
                'label' => esc_html__( 'Front', 'thegov-core' ),
            ]
        );

        $this->add_control('front_title',
            array(
                'label'         => esc_html__('Title', 'thegov-core'),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__('This is the heading​', 'thegov-core'),
            )
        );

        $this->add_control('front_content',
            array(
                'label'             => esc_html__('Flipbox Text', 'thegov-core'),
                'type'    => Controls_Manager::WYSIWYG,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Description Text', 'thegov-core' ),
				'label_block' => true,
                'default'       => esc_html__('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'thegov-core'),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'flipbox_back_content',
            [
                'label' => esc_html__( 'Back', 'thegov-core' ),
            ]
        );

        $this->add_control('back_title',
            array(
                'label'         => esc_html__('Title', 'thegov-core'),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'placeholder'       => esc_html__('This is the heading​', 'thegov-core'),
            )
        );

        $this->add_control('back_content',
            array(
                'label'             => esc_html__('Flipbox Text', 'thegov-core'),
                'type'    => Controls_Manager::WYSIWYG,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Description Text', 'thegov-core' ),
                'label_block' => true,
                'default'       => esc_html__('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'thegov-core'),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        /*End General Settings Section*/
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_link',
            [
                'label' => esc_html__( 'Flipbox Link', 'thegov-core' ),
            ]
        );

        $this->add_control('add_item_link',
            array(
                'label'        => esc_html__('Add Link To Whole Item','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
                'condition' => [
                    'add_read_more!' => 'yes',
                ],

            )
        );

        $this->add_control('item_link',
            array(
                'label'             => esc_html__('Link', 'thegov-core'),
                'type'              => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'condition'     => [
                    'add_item_link'   => 'yes',
                ],
            )
        );

        $this->add_control('add_read_more',
            array(
                'label'             => esc_html__('Add \'Read More\' Button', 'thegov-core'),
                'type'              => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
                'condition' => [
                    'add_item_link!' => 'yes',
                ],
            )
        );

        $this->add_control('read_more_text',
            array(
                'label'             => esc_html__('Button Text', 'thegov-core'),
                'type'              => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
                'default' =>  esc_html__('Read More', 'thegov-core'),
				'label_block' => true,
                'condition'     => [
                    'add_read_more'   => 'yes',
                ],
            )
        );

        $this->add_control('link',
            array(
                'label'             => esc_html__('Button Link', 'thegov-core'),
                'type'              => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'condition'     => [
                    'add_read_more'   => 'yes',
                ],
            )
        );

        /*End Link Settings Section*/
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Item Section)
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Flipbox Styles', 'thegov-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs(
            'flipbox_style'
        );

        $this->start_controls_tab(
            'flipbox_front_style',
            [
                'label' => esc_html__( 'Front', 'thegov-core' ),
            ]
        );

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'front_background',
				'label' => esc_html__( 'Front Background', 'thegov-core' ),
				'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wgl-flipbox_front',
			]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'flipbox_back_style',
            [
                'label' => esc_html__( 'Back', 'thegov-core' ),
            ]
        );

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'back_background',
				'label' => esc_html__( 'Back Background', 'thegov-core' ),
				'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wgl-flipbox_back',
			]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'flipbox_padding',
            array(
                'label' => esc_html__( 'Padding', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'separator' => 'before',
                'default'   => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit'  => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front, {{WRAPPER}} .wgl-flipbox_back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_responsive_control(
            'flipbox_margin',
            array(
                'label' => esc_html__( 'Margin', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front, {{WRAPPER}} .wgl-flipbox_back' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_control(
            'flipbox_border_radius',
            array(
                'label' => esc_html__( 'Border Radius', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'   => [
                    'top' => 15,
                    'right' => 15,
                    'bottom' => 15,
                    'left' => 15,
                    'unit'  => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front, {{WRAPPER}} .wgl-flipbox_back' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            )
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'flipbox_border',
				'selector' => '{{WRAPPER}} .wgl-flipbox_front, {{WRAPPER}} .wgl-flipbox_back',
				'separator' => 'before',
			]
        );

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'flipbox_shadow',
				'selector' => '{{WRAPPER}} .wgl-flipbox_front, {{WRAPPER}} .wgl-flipbox_back',
			]
		);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Media Section)
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Media', 'thegov-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs(
            'media_styles'
        );

        $this->start_controls_tab(
            'front_media_style',
            [
                'label' => esc_html__( 'Front', 'thegov-core' ),
            ]
        );

        $this->add_responsive_control(
            'front_media_margin',
            array(
                'label' => esc_html__( 'Margin', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_media-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_control(
            'front_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $theme_color,
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front .wgl-icon' => 'color: {{VALUE}};',
                ],
                'condition'     => [
                    'front_icon_type'   => 'font',
                ]
            ]
        );

        $this->add_responsive_control(
            'front_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'thegov-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 16,
                        'max' => 100,
                    ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 55,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front .wgl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'front_icon_type'   => 'font',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'back_media_style',
            [
                'label' => esc_html__( 'Back', 'thegov-core' ),
            ]
        );

        $this->add_responsive_control(
            'back_media_margin',
            array(
                'label' => esc_html__( 'Margin', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_media-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_control(
            'back_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $theme_color,
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-icon' => 'color: {{VALUE}};',
                ],
                'condition'     => [
                    'back_icon_type'   => 'font',
                ]
            ]
        );

        $this->add_responsive_control(
            'back_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'thegov-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 16,
                        'max' => 100,
                    ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 55,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'back_icon_type'   => 'font',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Headings Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'title_style_section',
            array(
                'label'     => esc_html__( 'Title', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control('title_tag',
            array(
                'label'         => esc_html__('Title Tag', 'thegov-core'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'h3',
                'description'   => esc_html__( 'Choose your tag for flipbox title', 'thegov-core' ),
                'options'       => [
                    'h1'      => 'H1',
                    'h2'      => 'H2',
                    'h3'      => 'H3',
                    'h4'      => 'H4',
                    'h5'      => 'H5',
                    'h6'      => 'H6',
                    'div'     => 'DIV',
                    'span'    => 'SPAN',
                ],
            )
        );

        $this->start_controls_tabs(
            'title_styles'
        );

        $this->start_controls_tab(
            'front_title_style',
            [
                'label' => esc_html__( 'Front', 'thegov-core' ),
            ]
        );

        $this->add_responsive_control(
            'front_title_offset',
            array(
                'label' => esc_html__( 'Title Offset', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default'   => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 8,
                    'left' => 0,
                    'unit'  => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_front_fonts_title',
                'selector' => '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_title',
            )
        );

        $this->add_control(
            'front_title_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'back_title_style',
            [
                'label' => esc_html__( 'Back', 'thegov-core' ),
            ]
        );

        $this->add_responsive_control(
            'back_title_offset',
            array(
                'label' => esc_html__( 'Title Offset', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default'   => [
                    'top' => 10,
                    'right' => 0,
                    'bottom' => 8,
                    'left' => 0,
                    'unit'  => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_back_fonts_title',
                'selector' => '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_title',
            )
        );

        $this->add_control(
            'back_title_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Content styles

        $this->start_controls_section(
            'content_style_section',
            array(
                'label'     => esc_html__( 'Content', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->start_controls_tabs(
            'content_styles'
        );

        $this->start_controls_tab(
            'front_content_style',
            [
                'label' => esc_html__( 'Front', 'thegov-core' ),
            ]
        );

        $this->add_responsive_control(
            'front_content_offset',
            array(
                'label' => esc_html__( 'Content Offset', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default'   => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit'  => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_front_fonts_content',
                'selector' => '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_content',
            )
        );

        $this->add_control(
            'front_content_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $main_font_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_content' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'back_content_style',
            [
                'label' => esc_html__( 'Back', 'thegov-core' ),
            ]
        );

        $this->add_responsive_control(
            'back_content_offset',
            array(
                'label' => esc_html__( 'Content Offset', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default'   => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit'  => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_back_fonts_content',
                'selector' => '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_content',
            )
        );

        $this->add_control(
            'back_content_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $main_font_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_content' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Button Styles

        $this->start_controls_section(
            'button_style_section',
            array(
                'label'     => esc_html__( 'Button', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'add_read_more!'   => '',
                ],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_fonts_button',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_family']],
                    'font_weight' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_weight']],
                ],
                'selector' => '{{WRAPPER}} .wgl-flipbox_readmore',
            )
        );

        $this->add_responsive_control(
            'custom_button_padding',
            array(
                'label' => esc_html__( 'Padding', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_readmore' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_responsive_control(
            'custom_button_margin',
            array(
                'label' => esc_html__( 'Margin', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_readmore' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_control(
            'custom_button_border',
            array(
                'label' => esc_html__( 'Border Radius', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_readmore' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            )
        );

        $this->start_controls_tabs( 'button_color_tab' );

        $this->start_controls_tab(
            'custom_button_color_normal',
            array(
                'label' => esc_html__( 'Normal' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'button_background',
            array(
                'label' => esc_html__( 'Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($theme_color),
                'selectors' => array(
                    '{{WRAPPER}} .wgl-flipbox_readmore' => 'background: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'button_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .wgl-flipbox_readmore' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'button_border',
                'label'    => esc_html__( 'Border Type', 'thegov-core' ),
                'selector' => '{{WRAPPER}} .wgl-flipbox_readmore',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'button_shadow',
                'selectors' =>  '{{WRAPPER}} .wgl-flipbox_readmore',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_button_color_hover',
            array(
                'label' => esc_html__( 'Hover' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'button_background_hover',
            array(
                'label' => esc_html__( 'Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $second_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-flipbox_readmore:hover' => 'background: {{VALUE}};'
                ),
            )
        );

        $this->add_control(
            'button_color_hover',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .wgl-flipbox_readmore:hover' => 'color: {{VALUE}};'
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'button_border_hover',
                'label'    => esc_html__( 'Border Type', 'thegov-core' ),
                'selector' => '{{WRAPPER}} .wgl-flipbox_readmore:hover',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'button_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-flipbox_readmore:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    public function render(){

        $settings = $this->get_settings_for_display();

        // HTML tags allowed for rendering
        $allowed_html = array(
            'a' => array(
                'href' => true,
                'title' => true,
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
            'span' => array(
                'class' => true,
                'style' => true,
            ),
            'p' => array(
                'class' => true,
                'style' => true,
            )
        );

        $this->add_render_attribute( 'flipbox', [
			'class' => [
                'wgl-flipbox',
                'type_'.$settings['flip_direction'],
            ],
        ] );


        $this->add_render_attribute('flipbox_link', 'class', [ 'wgl-flipbox_readmore', 'wgl-button', 'elementor-button', 'elementor-size-xl']);
        if (isset($settings['link']['url'])) $this->add_link_attributes('flipbox_link', $settings['link']);

        $this->add_render_attribute('item_link', 'class', 'wgl-flipbox_item-link');
        if (isset($settings['item_link']['url'])) $this->add_link_attributes('item_link', $settings['item_link']);

        // Icon/Image output
        ob_start();
        if (!empty($settings['front_icon_type'])) {
            $icons = new Wgl_Icons;
            echo $icons->build($this, $settings, 'front_' );
        }
        $front_media = ob_get_clean();
        // Icon/Image output
        ob_start();
        if (!empty($settings['back_icon_type'])) {
            $icons = new Wgl_Icons;
            echo $icons->build($this, $settings, 'back_' );
        }
        $back_media = ob_get_clean();

        ?>
        <div <?php echo $this->get_render_attribute_string( 'flipbox' ); ?>>
            <div class="wgl-flipbox_wrap">
                <div class="wgl-flipbox_front"><?php
                    if ($settings['front_icon_type'] != '') {?>
                    <div class="wgl-flipbox_media-wrap"><?php
                        if (!empty($front_media)){
                            echo $front_media;
                        }?>
                    </div><?php
                    }
                    if (!empty($settings['front_title'])) {?>
                        <<?php echo $settings['title_tag']; ?> class="wgl-flipbox_title"><?php echo wp_kses( $settings['front_title'], $allowed_html );?></<?php echo $settings['title_tag']; ?>><?php
                    }
                    if (!empty($settings['front_content'])) {?>
                        <div class="wgl-flipbox_content"><?php echo wp_kses( $settings['front_content'], $allowed_html );?></div><?php
                    }?>
                </div>
                <div class="wgl-flipbox_back"><?php
                    if ($settings['back_icon_type'] != '') {?>
                    <div class="wgl-flipbox_media-wrap"><?php
                        if (!empty($back_media)){
                            echo $back_media;
                        }?>
                    </div><?php
                    }
                    if (!empty($settings['back_title'])) {?>
                        <<?php echo $settings['title_tag']; ?> class="wgl-flipbox_title"><?php echo wp_kses( $settings['back_title'], $allowed_html );?></<?php echo $settings['title_tag']; ?>><?php
                    }
                    if (!empty($settings['back_content'])) {?>
                        <div class="wgl-flipbox_content"><?php echo wp_kses( $settings['back_content'], $allowed_html );?></div><?php
                    }
                    if ((bool)$settings['add_read_more']) {?>
                        <div class="wgl-flipbox_button-wrap"><a <?php echo $this->get_render_attribute_string( 'flipbox_link' ); ?>><?php echo esc_html($settings['read_more_text']);?></a></div><?php
                    }?>
                </div>
            </div><?php
            if ((bool)$settings['add_item_link']) {?>
                <a <?php echo $this->get_render_attribute_string( 'item_link' ); ?>></a><?php
            }?>
        </div>

        <?php
    }

}