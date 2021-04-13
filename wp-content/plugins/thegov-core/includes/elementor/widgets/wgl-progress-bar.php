<?php
namespace WglAddons\Widgets;

use WglAddons\Includes\Wgl_Icons;
use WglAddons\Includes\Wgl_Loop_Settings;
use WglAddons\Includes\Wgl_Carousel_Settings;
use WglAddons\Templates\WglProgressBar;
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

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Wgl_Progress_Bar extends Widget_Base {

    public function get_name() {
        return 'wgl-progress-bar';
    }

    public function get_title() {
        return esc_html__('Wgl Progress Bar', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-progress-bar';
    }

    public function get_categories() {
        return [ 'wgl-extensions' ];
    }

    public function get_script_depends() {
        return [
            'appear',
        ];
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
        $this->start_controls_section('wgl_progress_bar_section',
            array(
                'label'         => esc_html__('Progress Bar Settings', 'thegov-core'),
            )
        );

        $this->add_control('progress_title',
            array(
                'label'             => esc_html__('Title', 'thegov-core'),
                'type'              => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your title', 'thegov-core' ),
				'default' => esc_html__( 'My Skill', 'thegov-core' ),
				'label_block' => true,
            )
        );

		$this->add_control(
			'value',
			[
				'label' => esc_html__( 'Value', 'thegov-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 50,
					'unit' => '%',
				],
				'label_block' => true,
			]
        );

        $this->add_control('units',
            array(
                'label'             => esc_html__('Units', 'thegov-core'),
                'type'              => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your units', 'thegov-core' ),
				'description' => esc_html__( 'Enter measurement units (Example: %, px, points, etc.)', 'thegov-core' ),
				'default' => esc_html__( '%', 'thegov-core' ),
				'label_block' => true,
            )
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
            'title_style_section',
            array(
                'label'     => esc_html__( 'Title', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'progress_title_typo',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_family']],
                    'font_weight' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_weight']],
                ],
                'selector' => '{{WRAPPER}} .progress_label',
            )
        );

        $this->add_control(
			'progress_title_tag',
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
				'default' => 'div',
				'separator' => 'before',
			]
		);

        $this->add_control(
            'custom_title_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($header_font_color),
                'selectors' => array(
                    '{{WRAPPER}} .progress_label' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
                'default'   => [
                    'top'       => 0,
                    'left'      => 0,
                    'right'     => 0,
                    'bottom'    => 5,
                    'unit'      => 'px',
                ],
				'selectors' => [
					'{{WRAPPER}} .progress_label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Value Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'value_style_section',
            array(
                'label'     => esc_html__( 'Value', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'progress_value_typo',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_family']],
                ],
                'selector' => '{{WRAPPER}} .progress_value_wrap',
            )
        );

        $this->add_control(
            'custom_value_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
                'selectors' => array(
                    '{{WRAPPER}} .progress_value_wrap' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'custom_value_color_bg',
            array(
                'label' => esc_html__( 'Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => array(
                    '{{WRAPPER}} .progress_value_wrap' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
			'value_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .progress_value_wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

		$this->add_control(
			'value_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
				'selectors' => [
					'{{WRAPPER}} .progress_value_wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'value_pos',
			[
				'label' => esc_html__( 'Value Position', 'thegov-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fixed' => esc_html__('Fixed', 'thegov-core' ),
					'dynamic' => esc_html__('Dynamic', 'thegov-core' ),
				],
				'default' => 'fixed',
			]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Progress Bar Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'bar_style_section',
            array(
                'label'     => esc_html__( 'Progress Bar', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

		$this->add_control(
			'bar_height',
			[
				'label' => esc_html__( 'Progress Bar Height', 'thegov-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
				'default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'label_block' => true,
                'selectors' => array(
                    '{{WRAPPER}} .progress_bar_wrap' => 'height: {{SIZE}}{{UNIT}};',
                ),
			]
        );

        $this->add_control(
            'bar_bg_color',
            array(
                'label' => esc_html__( 'Background Bar Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#e5e5e5',
                'selectors' => array(
                    '{{WRAPPER}} .progress_bar_wrap' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'bar_color',
            array(
                'label' => esc_html__( 'Bar Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($theme_color),
                'selectors' => array(
                    '{{WRAPPER}} .progress_bar' => 'background-color: {{VALUE}}; color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
			'bar_padding',
			[
				'label' => esc_html__( 'Padding', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .progress_bar_wrap-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'bar_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .progress_bar_wrap-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

		$this->add_control(
			'bar_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 8,
                    'right' => 8,
                    'bottom' => 8,
                    'left' => 8,
                ],
				'selectors' => [
					'{{WRAPPER}} .progress_bar_wrap, {{WRAPPER}} .progress_bar, {{WRAPPER}} .progress_bar_wrap-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'bar_box_shadow',
				'selector' => '{{WRAPPER}} .progress_bar_wrap',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .progress_bar_wrap-wrap',
                'separator' => 'before',
			]
		);

        $this->end_controls_section();

    }

    public function render(){

        $settings = $this->get_settings_for_display();

        wp_enqueue_script('appear', get_template_directory_uri() . '/js/jquery.appear.js', array(), false, false);

        $this->add_render_attribute( 'progress_bar', [
			'class' => [
                'wgl-progress_bar',
                ($settings['value_pos'] == 'dynamic' ? 'dynamic-value' : ''),
            ],
        ] );

        $this->add_render_attribute( 'bar', [
			'class' => 'progress_bar',
			'data-width' => esc_attr((int)$settings['value']['size']),
        ] );

        $this->add_render_attribute( 'label', [
			'class' => 'progress_label',
        ] );

        ?>
        <div <?php echo $this->get_render_attribute_string( 'progress_bar' ); ?>>
            <div class="progress_wrap">
                <div class="progress_label_wrap">
                    <?php if (!empty($settings['progress_title'])) { ?>
                        <<?php echo esc_attr($settings['progress_title_tag']); ?> <?php echo $this->get_render_attribute_string( 'label' ); ?>><?php
                            echo esc_html($settings['progress_title']);
                        ?></<?php echo esc_attr($settings['progress_title_tag']); ?>>
                    <?php } ?>
                    <div class="progress_value_wrap">
                        <?php if (!empty($settings['value']['size'])) { ?>
                            <span class="progress_value"><?php echo esc_html((int)$settings['value']['size']); ?></span>
                        <?php } ?>
                        <?php if (!empty($settings['units'])) { ?>
                            <span class="progress_units"><?php echo esc_html($settings['units']); ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="progress_bar_wrap-wrap">
                    <div class="progress_bar_wrap">
                        <div <?php echo $this->get_render_attribute_string( 'bar' ); ?>></div>
                    </div>
                </div>
            </div>
        </div>
        <?php

    }

}