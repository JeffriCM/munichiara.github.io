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

class Wgl_Striped_Services extends Widget_Base {

    public function get_name() {
        return 'wgl-striped-services';
    }

    public function get_title() {
        return esc_html__('Wgl Striped Services', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-striped-services';
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
        $main_font_color = esc_attr(\Thegov_Theme_Helper::get_option('main-font')['color']);
        $header_font_color = esc_attr(\Thegov_Theme_Helper::get_option('header-font')['color']);

        /* Start General Settings Section */
        $this->start_controls_section('wgl_striped_services_section',
            array(
                'label'         => esc_html__('General Settings', 'thegov-core'),
            )
        );

        $this->add_responsive_control(
            'interval',
            array(
                'label' => esc_html__( 'Services Height', 'thegov-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1000,
                    ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
					'size' => 850,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 850,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 750,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-striped-services' => 'height: {{SIZE}}{{UNIT}};',
				],
            )
        );

        /*End General Settings Section*/
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Content
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section('wgl_content_section',
            array(
                'label'         => esc_html__('Content', 'thegov-core'),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'serv_title',
            array(
                'label' => esc_html__('Title', 'thegov-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Service Title', 'thegov-core'),
                'dynamic' => ['active' => true],
            )
        );

        $repeater->add_control(
            'serv_subtitle',
            array(
                'label' => esc_html__('Subtitle', 'thegov-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Service Subtitle', 'thegov-core'),
            )
        );

        $repeater->add_control(
            'bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#323232',
                'condition' => [
                    'thumbnail[url]' => '',
                ],
            )
        );

        $repeater->add_control(
            'thumbnail',
            array(
                'label'       => esc_html__( 'Thumbnail', 'thegov-core' ),
                'type'        => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => [
                    'url' => '',
                ],
            )
        );

        $repeater->add_responsive_control(
            'bg_position',
            array(
                'label' => esc_html__( 'Position', 'Background Control', 'thegov-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'top left',
                'responsive' => true,
                'options' => [
                    'top left' => esc_html__( 'Top Left', 'Background Control', 'thegov-core' ),
                    'top center' => esc_html__( 'Top Center', 'Background Control', 'thegov-core' ),
                    'top right' => esc_html__( 'Top Right', 'Background Control', 'thegov-core' ),
                    'center left' => esc_html__( 'Center Left', 'Background Control', 'thegov-core' ),
                    'center center' => esc_html__( 'Center Center', 'Background Control', 'thegov-core' ),
                    'center right' => esc_html__( 'Center Right', 'Background Control', 'thegov-core' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'Background Control', 'thegov-core' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'Background Control', 'thegov-core' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'Background Control', 'thegov-core' ),

                ],
                'condition' => [
                    'thumbnail[url]!' => '',
                ],
            )
        );

        $repeater->add_control(
            'bg_size',
            array(
                'label' => esc_html__( 'Size', 'Background Control', 'thegov-core' ),
                'type' => Controls_Manager::SELECT,
                'responsive' => true,
                'default' => 'cover',
                'options' => [
                    'auto' => esc_html__( 'Auto', 'Background Control', 'thegov-core' ),
                    'cover' => esc_html__( 'Cover', 'Background Control', 'thegov-core' ),
                    'contain' => esc_html__( 'Contain', 'Background Control', 'thegov-core' ),
                ],
                'condition' => [
                    'thumbnail[url]!' => '',
                ],
            )
        );

        $repeater->add_control(
            'serv_link',
            array(
                'label'             => esc_html__('Add Link', 'thegov-core'),
                'type'              => Controls_Manager::URL,
                'label_block' => true,
            )
        );

        $this->add_control(
            'items',
            array(
                'label'   => esc_html__( 'Layers', 'thegov-core' ),
                'type'    => Controls_Manager::REPEATER,
                'default' => [
                    ['serv_title' => esc_html__('Service Title', 'thegov-core')],
                ],
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{serv_title}}',
                'description' => esc_html__( 'Enter services height in pixels', 'thegov-core' ),
            )
        );

        $this->add_control(
            'deprecated_notice',
            [
                'type' => Controls_Manager::HEADING,
                'label'   => esc_html__( 'Add less than 4 items for proper rendering', 'thegov-core' ),
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section
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
                'name' => 'title_typo',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_family']],
                ],
                'selector' => '{{WRAPPER}} .service-item_title',
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .service-item_title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
			'title_tag',
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
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .service-item_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'subtitle_style_section',
            array(
                'label'     => esc_html__( 'Subtitle', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'subtitle_typo',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_family']],
                ],
                'selector' => '{{WRAPPER}} .service-item_subtitle',
            )
        );

        $this->add_control(
            'subtitle_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .service-item_subtitle' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
			'subtitle_tag',
			[
				'label' => esc_html__( 'Subtitle HTML Tag', 'thegov-core' ),
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
			]
        );

        $this->add_responsive_control(
			'subtitle_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .service-item_subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'striped-services', [
			'class' => [
                'wgl-striped-services',
            ],
        ] );

        ?><div <?php echo $this->get_render_attribute_string( 'striped-services' ); ?>><?php

        foreach ( $settings['items'] as $index => $item ) {

            $item_wrap = $this->get_repeater_setting_key( 'item_wrap', 'items' , $index );
            $this->add_render_attribute( $item_wrap, [
                'class' => [
                    'service-item',
                    (!empty($item['thumbnail']['url']) ? '' : 'no-image'),
                ],
                'style' => [
                    (!empty($item['thumbnail']['url']) ? 'background-image: url('.esc_url($item['thumbnail']['url']).');' : ''),
                    ($item['bg_position'] != '' ? 'background-position: '.esc_attr($item['bg_position']).';' : ''),
                    ($item['bg_size'] != '' ? 'background-size: '.esc_attr($item['bg_size']).';' : ''),
                    ($item['bg_color'] != '' ? 'background-color: '.esc_attr($item['bg_color']).';' : ''),
                ]
            ] );

            $image = $this->get_repeater_setting_key( 'image', 'items' , $index );
            $this->add_render_attribute( $image, [
                'src' => esc_url($item['thumbnail']['url']),
                'alt' => Control_Media::get_image_alt( $item['thumbnail'] ),
            ] );

            $serv_link = $this->get_repeater_setting_key( 'serv_link', 'items' , $index );

            $this->add_render_attribute($serv_link, 'class', 'service-item_link');
            if (isset($item['serv_link']['url'])) $this->add_link_attributes($serv_link, $item['serv_link']);

            ?>
            <div <?php echo $this->get_render_attribute_string( $item_wrap ); ?>><?php
                if (!empty($item['serv_link']['url'])) : ?><a <?php echo $this->get_render_attribute_string( $serv_link ); ?>><?php endif; ?>
                    <div class="service-item_content"><?php
                        if (!empty($item['serv_title'])) {
                            ?><<?php echo $settings['title_tag']; ?> class="service-item_title"><?php echo esc_html($item['serv_title']); ?></<?php echo $settings['title_tag']; ?>><?php
                        }
                        if (!empty($item['serv_subtitle'])) {
                            ?><<?php echo $settings['subtitle_tag']; ?> class="service-item_subtitle"><?php echo esc_html($item['serv_subtitle']); ?></<?php echo $settings['subtitle_tag']; ?>><?php
                        }?>
                    </div><?php
                if (!empty($item['serv_link']['url'])) : ?></a><?php endif; ?>
            </div><?php

        }

        ?></div><?php

    }

}