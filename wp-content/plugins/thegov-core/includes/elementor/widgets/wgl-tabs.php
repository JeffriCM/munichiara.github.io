<?php
namespace WglAddons\Widgets;

use WglAddons\Includes\Wgl_Icons;
use WglAddons\Includes\Wgl_Elementor_Helper;
use WglAddons\Templates\WglToggleAccordion;
use Elementor\Frontend;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Wgl_Tabs extends Widget_Base {

    public function get_name() {
        return 'wgl-tabs';
    }

    public function get_title() {
        return esc_html__('Wgl Tabs', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-tabs';
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

        /* Start General Settings Section */
        $this->start_controls_section('wgl_tabs_section',
            array(
                'label'         => esc_html__('General', 'thegov-core'),
            )
        );

        $this->add_responsive_control('tabs_tab_align',
			[
				'label' => esc_html__( 'Alignment', 'thegov-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'thegov-core' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => 'left',
			]
        );

        $this->add_responsive_control(
			'tabs_section_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        /*End General Settings Section*/
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Content Section(Title Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'content_section',
            array(
                'label'     => esc_html__( 'Content', 'thegov-core' ),
            )
        );

        $repeater = new Repeater();
        $repeater->add_control(
			'tabs_tab_title',
			[
                'label' => esc_html__('Tab Title', 'thegov-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Tab Title', 'thegov-core'),
                'dynamic' => ['active' => true],
			]
        );
        $repeater->add_control(
			'tabs_tab_icon_type',
			[
                'label'             => esc_html__('Add Icon/Image', 'thegov-core'),
                'type'              => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'           => [
                    ''              => [
                        'title' => esc_html__('None', 'thegov-core'),
                        'icon' => 'fa fa-ban',
                    ],
                    'font'          => [
                        'title' => esc_html__('Icon', 'thegov-core'),
                        'icon' => 'fa fa-smile-o',
                    ],
                    'image'         => [
                        'title' => esc_html__('Image', 'thegov-core'),
                        'icon' => 'fa fa-picture-o',
                    ]
                ],
                'default'           => '',
			]
        );
        $repeater->add_control(
			'tabs_tab_icon_pack',
			[
                'label'             => esc_html__('Icon Pack', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'fontawesome'               => esc_html__('Fontawesome', 'thegov-core'),
                    'flaticon'          => esc_html__('Flaticon', 'thegov-core'),
                ],
                'default'           => 'fontawesome',
                'condition'     => [
                    'tabs_tab_icon_type'  => 'font',
                ]
			]
        );
        $repeater->add_control(
			'tabs_tab_icon_flaticon',
			[
                'label'       => esc_html__( 'Icon', 'thegov-core' ),
                'type'        => 'wgl-icon',
                'label_block' => true,
                'condition'     => [
                    'tabs_tab_icon_pack'  => 'flaticon',
                    'tabs_tab_icon_type'  => 'font',
                ],
                'description' => esc_html__( 'Select icon from Flaticon library.', 'thegov-core' ),
			]
        );
        $repeater->add_control(
			'tabs_tab_icon_fontawesome',
			[
                'label'       => esc_html__( 'Icon', 'thegov-core' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => true,
                'condition'     => [
                    'tabs_tab_icon_pack'  => 'fontawesome',
                    'tabs_tab_icon_type'  => 'font',
                ],
                'description' => esc_html__( 'Select icon from Fontawesome library.', 'thegov-core' ),
			]
        );
        $repeater->add_control(
			'tabs_tab_icon_thumbnail',
			[
                'label'       => esc_html__( 'Image', 'thegov-core' ),
                'type'        => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition'     => [
                    'tabs_tab_icon_type'   => 'image',
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
			]
        );
        $repeater->add_control(
			'tabs_content_type',
			[
                'label' => esc_html__('Content Type', 'thegov-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'content' => esc_html__('Content', 'thegov-core'),
                    'template' => esc_html__('Saved Templates', 'thegov-core'),
                ],
                'default' => 'content',
			]
        );
        $repeater->add_control(
			'tabs_content_templates',
			[
                'label' => esc_html__('Choose Template', 'thegov-core'),
                'type' => Controls_Manager::SELECT,
                'options' => Wgl_Elementor_Helper::get_instance()->get_elementor_templates(),
                'condition' => [
                    'tabs_content_type' => 'template',
                ],
			]
        );
        $repeater->add_control(
			'tabs_content',
			[
                'label' => esc_html__('Tab Content', 'thegov-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'thegov-core'),
                'dynamic' => ['active' => true],
                'condition' => [
                    'tabs_content_type' => 'content',
                ],
			]
        );

        $this->add_control(
            'tabs_tab',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' => [
                    ['tabs_tab_title' => esc_html__('Tab Title 1', 'thegov-core')],
                    ['tabs_tab_title' => esc_html__('Tab Title 2', 'thegov-core')],
                    ['tabs_tab_title' => esc_html__('Tab Title 3', 'thegov-core')],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{tabs_tab_title}}',
            ]
        );

        /*End General Settings Section*/
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Title Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'tabs_title_style',
            array(
                'label'     => esc_html__( 'Title', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'tabs_title_typo',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_family']],
                ],
                'selector' => '{{WRAPPER}} .wgl-tabs_title',
            )
        );

        $this->add_control(
			'tabs_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'thegov-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
				],
				'default' => 'h4',
			]
		);

        $this->add_responsive_control(
			'tabs_title_padding',
			[
				'label' => esc_html__( 'Padding', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'top' => 20,
                    'right' => 26,
                    'bottom' => 16,
                    'left' => 26,
                ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs_header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'tabs_title_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 2,
                    'left' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs_header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->add_responsive_control(
			'tabs_title_line',
			[
                'label' => esc_html__('Add Title Bottom Line', 'thegov-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
			]
        );

        /* Tabs */
        $this->start_controls_tabs('tabs_header_tabs');
        /* Normal */
        $this->start_controls_tab('tabs_header_normal', ['label' => esc_html__('Normal', 'thegov-core')]);

        $this->add_control(
            'tabs_title_color',
            array(
                'label' => esc_html__( 'Title Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_header' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'tabs_title_bg_color',
            array(
                'label' => esc_html__( 'Title Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_header' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'tabs_title_line_color',
            array(
                'label' => esc_html__( 'Title Bottom Line Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_header:before' => 'background-color: {{VALUE}};',
                ),
                'condition' => [
                    'tabs_title_line' => 'yes',
                ],
            )
        );

		$this->add_control(
			'tabs_title_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs_header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'tabs_title_border',
				'selector' => '{{WRAPPER}} .wgl-tabs_header',
			]
		);

        $this->end_controls_tab();
        /* End Normal Tab */
        /* Hover */
        $this->start_controls_tab('tabs_header_hover', ['label' => esc_html__('Hover', 'thegov-core')]);

        $this->add_control(
            'tabs_title_color_hover',
            array(
                'label' => esc_html__( 'Title Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_header:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'tabs_title_bg_color_hover',
            array(
                'label' => esc_html__( 'Title Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#efefef',
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_header:hover' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'tabs_title_line_color_hover',
            array(
                'label' => esc_html__( 'Title Bottom Line Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#efefef',
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_header:hover:before' => 'background-color: {{VALUE}};',
                ),
                'condition' => [
                    'tabs_title_line' => 'yes',
                ],
            )
        );

		$this->add_control(
			'tabs_title_border_radius_hover',
			[
				'label' => esc_html__( 'Border Radius', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs_header:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'tabs_title_border_hover',
				'selector' => '{{WRAPPER}} .wgl-tabs_header:hover',
			]
		);

        $this->end_controls_tab();
        /* End Hover Tab */
        /* Active */
        $this->start_controls_tab('tabs_header_active', ['label' => esc_html__('Active', 'thegov-core')]);

        $this->add_control(
            'tabs_title_color_active',
            array(
                'label' => esc_html__( 'Title Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_header.active' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'tabs_title_bg_color_active',
            array(
                'label' => esc_html__( 'Title Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $theme_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_header.active' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'tabs_title_line_color_active',
            array(
                'label' => esc_html__( 'Title Bottom Line Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $theme_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_header.active:before' => 'background-color: {{VALUE}};',
                ),
                'condition' => [
                    'tabs_title_line' => 'yes',
                ],
            )
        );

		$this->add_control(
			'tabs_title_border_radius_active',
			[
				'label' => esc_html__( 'Border Radius', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs_header.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'tabs_title_border_active',
				'selector' => '{{WRAPPER}} .wgl-tabs_header.active',
			]
		);

        $this->end_controls_tab();
        /* End Active Tab */
        $this->end_controls_tabs();
        /* End Tabs */
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Icon Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'icon_style_section',
            array(
                'label'     => esc_html__( 'Icon', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'tabs_icon_size',
            [
                'label' => esc_html__('Icon Size', 'thegov-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 26,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_icon:not(.wgl-tabs_icon-image)' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control('tabs_icon_position',
            array(
                'label'             => esc_html__('Icon/Image Position', 'thegov-core'),
                'type'              => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'           => [
                    'top'              => [
                        'title' => esc_html__('Top', 'thegov-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right'          => [
                        'title' => esc_html__('Right', 'thegov-core'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'bottom'         => [
                        'title' => esc_html__('Bottom', 'thegov-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'left'         => [
                        'title' => esc_html__('Left', 'thegov-core'),
                        'icon' => 'eicon-h-align-left',
                    ]
                ],
                'default'           => 'top',
            )
        );

        $this->add_responsive_control(
			'tabs_icon_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        /* Tabs */
        $this->start_controls_tabs('tabs_icon_tabs');
        /* Normal */
        $this->start_controls_tab('tabs_icon_normal', ['label' => esc_html__('Normal', 'thegov-core')]);

        $this->add_control(
            'tabs_icon_color',
            array(
                'label' => esc_html__( 'Icon Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_icon' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();
        /* End Normal Tab */
        /* Hover */
        $this->start_controls_tab('tabs_icon_hover', ['label' => esc_html__('Hover', 'thegov-core')]);

        $this->add_control(
            'tabs_icon_color_hover',
            array(
                'label' => esc_html__( 'Icon Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_header:hover .wgl-tabs_icon' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();
        /* End Hover Tab */
        /* Active */
        $this->start_controls_tab('tabs_icon_active', ['label' => esc_html__('Active', 'thegov-core')]);

        $this->add_control(
            'tabs_icon_color_active',
            array(
                'label' => esc_html__( 'Icon Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_header.active .wgl-tabs_icon' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();
        /* End Active Tab */
        $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Content Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'tabs_content_style',
            array(
                'label'     => esc_html__( 'Content', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'tabs_content_typo',
                'selector' => '{{WRAPPER}} .wgl-tabs_content',
            )
        );

        $this->add_responsive_control(
			'tabs_content_padding',
			[
				'label' => esc_html__( 'Padding', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'top' => 21,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'tabs_content_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->add_control(
            'tabs_content_color',
            array(
                'label' => esc_html__( 'Content Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $main_font_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_content' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'tabs_content_bg_color',
            array(
                'label' => esc_html__( 'Content Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .wgl-tabs_content' => 'background-color: {{VALUE}};',
                ),
            )
        );

		$this->add_control(
			'tabs_content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'tabs_content_border',
				'selector' => '{{WRAPPER}} .wgl-tabs_content',
			]
		);

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        $id_int = substr($this->get_id_int(), 0, 3);

        $this->add_render_attribute( 'tabs', [
			'class' => [
                'wgl-tabs',
                'icon_position-'.$settings['tabs_icon_position'],
                'tabs_align-'.$settings['tabs_tab_align'],
            ],
        ] );

        ?>
        <div <?php echo $this->get_render_attribute_string( 'tabs' ); ?>>

            <div class="wgl-tabs_headings"><?php
                foreach ( $settings['tabs_tab'] as $index => $item ) :

                    $tab_count = $index + 1;
                    $tab_title_key = $this->get_repeater_setting_key( 'tabs_tab_title', 'tabs_tab', $index );
                    $this->add_render_attribute( $tab_title_key, [
                        'data-tab-id' => 'wgl-tab_' . $id_int . $tab_count,
                        'class' => [ 'wgl-tabs_header' ],
                    ] );

                    ?>
                    <<?php echo $settings['tabs_title_tag']; ?> <?php echo $this->get_render_attribute_string( $tab_title_key ); ?>>
                        <span class="wgl-tabs_title"><?php echo $item['tabs_tab_title'] ?></span>

                        <?php
                        // Tab Icon/image
                        if($item['tabs_tab_icon_type'] != ''){
                            if ($item['tabs_tab_icon_type'] == 'font' && (!empty($item['tabs_tab_icon_flaticon']) || !empty($item['tabs_tab_icon_fontawesome']))) {
                                switch ($item['tabs_tab_icon_pack']) {
                                    case 'fontawesome':
                                        wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
                                        $icon_font = $item['tabs_tab_icon_fontawesome'];
                                        break;
                                    case 'flaticon':
                                        wp_enqueue_style('flaticon', get_template_directory_uri() . '/fonts/flaticon/flaticon.css');
                                        $icon_font = $item['tabs_tab_icon_flaticon'];
                                        break;
                                }
                                ?>
                                <span class="wgl-tabs_icon">
                                    <i class="icon <?php echo esc_attr($icon_font) ?>"></i>
                                </span>
                                <?php
                            }
                            if ($item['tabs_tab_icon_type'] == 'image' && !empty($item['tabs_tab_icon_thumbnail'])) {
                                if ( ! empty( $item['tabs_tab_icon_thumbnail']['url'] ) ) {
                                    $this->add_render_attribute( 'thumbnail', 'src', $item['tabs_tab_icon_thumbnail']['url'] );
                                    $this->add_render_attribute( 'thumbnail', 'alt', Control_Media::get_image_alt( $item['tabs_tab_icon_thumbnail'] ) );
                                    $this->add_render_attribute( 'thumbnail', 'title', Control_Media::get_image_title( $item['tabs_tab_icon_thumbnail'] ) );
                                    ?>
                                    <span class="wgl-tabs_icon wgl-tabs_icon-image">
                                    <?php
                                        echo Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'tabs_tab_icon_thumbnail' );
                                    ?>
                                    </span>
                                    <?php
                                }
                            }
                        }
                        // End Tab Icon/image
                        ?>

                    </<?php echo $settings['tabs_title_tag']; ?>>

                <?php endforeach;?>
            </div>

            <div class="wgl-tabs_content-wrap"><?php
                foreach ( $settings['tabs_tab'] as $index => $item ) :

                    $tab_count = $index + 1;
                    $tab_content_key = $this->get_repeater_setting_key( 'tab_content', 'tabs_tab', $index );
                    $this->add_render_attribute( $tab_content_key, [
                        'data-tab-id' => 'wgl-tab_' . $id_int . $tab_count,
                        'class' => [ 'wgl-tabs_content' ],
                    ] );

                    ?>
                    <div <?php echo $this->get_render_attribute_string($tab_content_key); ?>>
                    <?php
                        if ($item['tabs_content_type'] == 'content') {
                            echo do_shortcode($item['tabs_content']);
                        } else if($item['tabs_content_type'] == 'template'){
                            $id = $item['tabs_content_templates'];
                            $wgl_frontend = new Frontend;
                            echo $wgl_frontend->get_builder_content_for_display( $id, false );
                        }
                    ?>
                    </div>

                <?php endforeach;?>
            </div>

        </div>
        <?php

    }

}