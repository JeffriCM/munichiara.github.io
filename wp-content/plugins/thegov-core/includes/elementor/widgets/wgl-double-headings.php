<?php
namespace WglAddons\Widgets;

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
use Elementor\Group_Control_Css_Filter;


if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Wgl_Double_Headings extends Widget_Base {

    public function get_name() {
        return 'wgl-double-headings';
    }

    public function get_title() {
        return esc_html__('Wgl Double Headings', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-double-headings';
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
        /*  Content
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section('wgl_double_headings_section',
            array(
                'label'         => esc_html__('Double Headings Settings', 'thegov-core'),
            )
        );

        $this->add_control('sub_pos',
            array(
                'label'             => esc_html__('Subtitle Position', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'column'           => esc_html__('Top', 'thegov-core'),
                    'column-reverse'        => esc_html__('Bottom', 'thegov-core'),
                ],
                'default'           => 'column',
				'selectors' => [
					'{{WRAPPER}} .wgl-double_heading' => 'flex-direction: {{VALUE}};',
				],
            )
        );

        $this->add_control(
			'subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'thegov-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
                'placeholder' => esc_html__( 'Subtitle', 'thegov-core' ),
                'separator' => 'after',
			]
        );

        $this->add_control(
			'title_1',
			[
				'label' => esc_html__( 'Title 1st Part', 'thegov-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
                'default' => esc_html__( 'This is the heading​', 'thegov-core' ),
                'placeholder' => esc_html__( 'This is the heading​', 'thegov-core' ),
			]
        );

        $this->add_control(
			'title_2',
			[
				'label' => esc_html__( 'Title 2nd Part', 'thegov-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
                'default' => '',
                'placeholder' => esc_html__( '2nd Title', 'thegov-core' ),
			]
        );

        $this->add_control(
			'title_3',
			[
				'label' => esc_html__( 'Title 3rd Part', 'thegov-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
                'default' => '',
                'placeholder' => esc_html__( '3rd Title', 'thegov-core' ),
                'separator' => 'after',
			]
        );

        $this->add_control(
            'title_div',
            array(
                'label' => esc_html__('Add Divider after Title', 'thegov-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'prefix_class' => 'divider_',
            )
        );

        $this->add_control(
			'align',
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
				],
				'default' => 'left',
			]
        );

        $this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Title Tag', 'thegov-core' ),
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
				'default' => 'h3',
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'thegov-core' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'thegov-core' ),
			]
		);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Styles options
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Double Headings Styles', 'thegov-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

        $this->add_control(
			'title_1_h',
			[
				'label' => esc_html__( 'Title 1st', 'thegov-core' ),
				'type' => Controls_Manager::HEADING,
			]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_1_typo',
				'selector' => '{{WRAPPER}} .dbl-title_1',
			]
        );

        $this->add_control(
			'title_1_color',
			[
				'label' => esc_html__( 'Color', 'thegov-core' ),
				'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
				'selectors' => [
					'{{WRAPPER}} .dbl-title_1' => 'color: {{VALUE}};',
				],
			]
        );

        $this->add_responsive_control('title_1_display',
            array(
                'label'             => esc_html__('Display', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'block'           => esc_html__('Block', 'thegov-core'),
                    'inline'        => esc_html__('Inline', 'thegov-core'),
                ],
                'default'           => 'inline',
				'selectors' => [
					'{{WRAPPER}} .dbl-title_1' => 'display: {{VALUE}};',
				],
                'separator' => 'after',
            )
        );

        $this->add_control(
			'title_2_h',
			[
				'label' => esc_html__( 'Title 2nd', 'thegov-core' ),
				'type' => Controls_Manager::HEADING,
                'condition'     => [
                    'title_2!'  => '',
                ]
			]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_2_typo',
				'selector' => '{{WRAPPER}} .dbl-title_2',
                'condition'     => [
                    'title_2!'  => '',
                ]
			]
        );

        $this->add_control(
			'title_2_color',
			[
				'label' => esc_html__( 'Color', 'thegov-core' ),
				'type' => Controls_Manager::COLOR,
                'default' => $theme_color,
				'selectors' => [
					'{{WRAPPER}} .dbl-title_2' => 'color: {{VALUE}};',
				],
                'condition'     => [
                    'title_2!'  => '',
                ]
			]
        );

        $this->add_responsive_control('title_2_display',
            array(
                'label'             => esc_html__('Display', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'block'           => esc_html__('Block', 'thegov-core'),
                    'inline'        => esc_html__('Inline', 'thegov-core'),
                ],
                'default'           => 'inline',
				'selectors' => [
					'{{WRAPPER}} .dbl-title_2' => 'display: {{VALUE}};',
				],
                'separator' => 'after',
                'condition'     => [
                    'title_2!'  => '',
                ]
            )
        );

        $this->add_control(
			'title_3_h',
			[
				'label' => esc_html__( 'Title 3rd', 'thegov-core' ),
				'type' => Controls_Manager::HEADING,
                'condition'     => [
                    'title_3!'  => '',
                ]
			]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_3_typo',
				'selector' => '{{WRAPPER}} .dbl-title_3',
                'condition'     => [
                    'title_3!'  => '',
                ]
			]
        );

        $this->add_control(
			'title_3_color',
			[
				'label' => esc_html__( 'Color', 'thegov-core' ),
				'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
				'selectors' => [
					'{{WRAPPER}} .dbl-title_3' => 'color: {{VALUE}};',
				],
                'condition'     => [
                    'title_3!'  => '',
                ]
			]
        );

        $this->add_responsive_control('title_3_display',
            array(
                'label'             => esc_html__('Display', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'block'           => esc_html__('Block', 'thegov-core'),
                    'inline'        => esc_html__('Inline', 'thegov-core'),
                ],
                'default'           => 'inline',
				'selectors' => [
					'{{WRAPPER}} .dbl-title_3' => 'display: {{VALUE}};',
				],
                'separator' => 'after',
                'condition'     => [
                    'title_3!'  => '',
                ]
            )
        );

        $this->add_control(
			'subtitle_h',
			[
				'label' => esc_html__( 'Subtitle', 'thegov-core' ),
				'type' => Controls_Manager::HEADING,
                'condition'     => [
                    'subtitle!'  => '',
                ]
			]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typo',
				'selector' => '{{WRAPPER}} .dbl-subtitle',
                'condition'     => [
                    'subtitle!'  => '',
                ]
			]
        );

        $this->add_control(
			'subtitle_color',
			[
				'label' => esc_html__( 'Color', 'thegov-core' ),
				'type' => Controls_Manager::COLOR,
                'default' => $theme_color,
				'selectors' => [
					'{{WRAPPER}} .dbl-subtitle' => 'color: {{VALUE}};',
				],
                'separator' => 'after',
                'condition'     => [
                    'subtitle!'  => '',
                ]
			]
        );

        $this->add_responsive_control(
            'subtitle_margin',
            array(
                'label' => esc_html__( 'Subtitle Offset', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default'   => [
                    'top' => 12,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit'  => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dbl-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        if ( !empty( $settings['link']['url'] ) ) {
            $this->add_render_attribute('link', 'class', 'dbl-title_link');
            $this->add_link_attributes('link', $settings['link']);
        }

        $this->add_render_attribute( 'heading_wrapper', [
            'class' => [
                'wgl-double_heading',
                'a'.$settings['align'],
            ],
        ] );

        ?><div <?php echo $this->get_render_attribute_string( 'heading_wrapper' ); ?>><?php
            if ( !empty($settings['subtitle']) ) : ?><div class="dbl-subtitle"><span><?php echo $settings['subtitle']; ?></span></div><?php endif;
            if ( !empty($settings['link']['url']) ) : ?><a <?php echo $this->get_render_attribute_string( 'link' ); ?>><?php endif;?>
                <<?php echo $settings['title_tag']; ?> class="dbl-title_wrapper"><?php
                if ( !empty($settings['title_1']) ) : ?><span class="dbl-title dbl-title_1"><?php echo $settings['title_1']; ?></span> <?php endif;
                if ( !empty($settings['title_2']) ) : ?><span class="dbl-title dbl-title_2"><?php echo $settings['title_2']; ?></span> <?php endif;
                if ( !empty($settings['title_3']) ) : ?><span class="dbl-title dbl-title_3"><?php echo $settings['title_3']; ?></span> <?php endif;?>
                </<?php echo $settings['title_tag']; ?>><?php
            if ( !empty($settings['link']['url']) ) : ?></a><?php endif;?>
        </div><?php

    }

}