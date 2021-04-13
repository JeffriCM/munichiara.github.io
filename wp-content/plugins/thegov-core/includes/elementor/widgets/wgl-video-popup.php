<?php
namespace WglAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Embed;
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

class Wgl_Video_Popup extends Widget_Base {

    public function get_name() {
        return 'wgl-video-popup';
    }

    public function get_title() {
        return esc_html__('Wgl Video Popup', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-video-popup';
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

        $this->start_controls_section('wgl_video_popup_section',
            array(
                'label'         => esc_html__('Video Popup Settings', 'thegov-core'),
            )
        );

        $this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'thegov-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
			]
        );

        $this->add_control(
			'link',
			[
				'label' => esc_html__( 'Video Link', 'thegov-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your URL', 'thegov-core' ),
				'description' => esc_html__( 'Enter video link from youtube or vimeo.', 'thegov-core' ),
				'default' => 'https://www.youtube.com/watch?v=TKnufs85hXk',
				'label_block' => true,
                'separator' => 'after',
			]
        );

        $this->add_control(
            'title_pos',
            array(
                'label' => esc_html__( 'Title Position', 'thegov-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top'      => [
                        'title' => esc_html__('Top', 'thegov-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right'          => [
                        'title' => esc_html__('Right', 'thegov-core'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'bot'         => [
                        'title' => esc_html__('Bottom', 'thegov-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'left'         => [
                        'title' => esc_html__('Left', 'thegov-core'),
                        'icon' => 'eicon-h-align-left',
                    ],
                ],
                'label_block' => false,
                'default' => 'bot',
                'toggle' => true,
            )
        );

        $this->add_responsive_control(
			'button_pos',
			[
				'label' => esc_html__( 'Video Popup Button Alignment', 'thegov-core' ),
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
					'inline' => [
						'title' => esc_html__( 'Inline', 'thegov-core' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'default' => 'center',
			]
        );

        $this->add_control(
			'bg_image',
			[
                'label'       => esc_html__( 'Background Image', 'thegov-core' ),
                'type'        => Controls_Manager::MEDIA,
                'label_block' => true,
                'description' => esc_html__( 'Select video background image.', 'thegov-core'),
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'animation_style',
            [
                'label'             => esc_html__('Select Animation Style', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'circles'       => esc_html__('Pulsing Circles', 'thegov-core'),
                    'ring_pulse'    => esc_html__('Pulsing Ring', 'thegov-core'),
                    'ring_rotate'   => esc_html__('Rotating Ring', 'thegov-core'),
                ],
                'default'           => 'circles',
            ]
        );

        $this->add_control(
            'always_run_animation',
            array(
                'label'        => esc_html__('Always Run Animation','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'description'  => esc_html__( 'Run until hover state.', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Styles options
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
			'title_style',
			[
				'label' => esc_html__( 'Title Styles', 'thegov-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

        $this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'selector' => '{{WRAPPER}} .title',
			]
        );

        $this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'thegov-core' ),
				'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
				'selectors' => [
					'{{WRAPPER}} .title' => 'color: {{VALUE}};',
				],
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

        $this->end_controls_section();

        $this->start_controls_section(
			'button_style',
			[
				'label' => esc_html__( 'Button Styles', 'thegov-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

        $this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .videobox_link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
            'button_size',
            [
                'label' => esc_html__( 'Button Size', 'thegov-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 200,
                    ],
                ],
                'size_units' => [ 'px' ],
                'default' => [
                    'size' => 80,
                    'unit' => 'px',
                ],
                'description' => esc_html__( 'Enter button diameter in pixels.', 'thegov-core' ),
                'selectors' => [
                    '{{WRAPPER}} .videobox_link' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'bg_color',
				'label' => esc_html__( 'Button Background', 'thegov-core' ),
				'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .videobox_link',
			]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'button_border',
                'label'    => esc_html__( 'Border Type', 'thegov-core' ),
                'selector' => '{{WRAPPER}} .videobox_link',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'button_shadow',
                'selector' =>  '{{WRAPPER}} .videobox_link',
            ]
        );

		$this->add_control(
            'triangle_size',
            [
                'label' => esc_html__( 'Triangle Size', 'thegov-core' ),
                'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', '%'],
                'default' => [
                    'size' => 25,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
			'triangle_color',
			[
				'label' => esc_html__( 'Triangle Color', 'thegov-core' ),
				'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
				'selectors' => [
					'{{WRAPPER}} .videobox_icon' => 'fill: {{VALUE}};',
				],
			]
        );

        $this->add_control(
            'triangle_corners',
            array(
                'label'        => esc_html__('Rounded Corners','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
			'anim_color',
			[
				'label' => esc_html__( 'Animation Circles Color', 'thegov-core' ),
				'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
				'selectors' => [
					'{{WRAPPER}} .videobox_animation' => 'color: {{VALUE}};',
				],
			]
        );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        $triangle_svg = $animated_element = '';

        $this->add_render_attribute( 'video-wrap', [
            'class' => [
                'wgl-video_popup',
                'button_align-'.$settings['button_pos'],
                'animation_'.$settings['animation_style'],
                'title_pos-'.$settings['title_pos'],
                !empty($settings['bg_image']['url']) ? 'with_image' : '',
                (bool)$settings['always_run_animation'] ? 'always-run-animation' : '',
            ],
        ] );

        // Animation element output
        switch ($settings['animation_style']) {
            case 'circles':
                $animated_element .= '<div class="videobox_animation circle_1"></div>';
                $animated_element .= '<div class="videobox_animation circle_2"></div>';
                $animated_element .= '<div class="videobox_animation circle_3"></div>';
                break;
            case 'ring_pulse':
                $animated_element .= '<div class="videobox_animation ring_1"></div>';
                break;
            case 'ring_rotate':
                $svg_ring_circle_color = !empty($settings['anim_color']) ? 'rgba('.\Thegov_Theme_Helper::HexToRGB($settings['anim_color']).', 0.1)' : 'rgba(0,0,0,0.1)';
                $svg_ring = '<svg class="ring_1" viewBox="0 0 202 202">';
                    $svg_ring .= '<g fill="none" stroke-width="1">';
                        $svg_ring .= '<circle stroke="'.$svg_ring_circle_color.'" cx="101" cy="101" r="100"/>';
                        $svg_ring .= '<path stroke="'.esc_attr($settings['anim_color']).'" d="M74,197.3c-33.5-9.4-59.9-35.8-69.3-69.2"/>';
                        $svg_ring .= '<path stroke="'.esc_attr($settings['anim_color']).'" d="M128,4.7c33.5,9.4,59.9,35.8,69.3,69.3"/>';
                    $svg_ring .= '</g>';
                $svg_ring .= '</svg>';
                $animated_element .= '<div class="videobox_animation">';
                $animated_element .= $svg_ring;
                $animated_element .= '</div>';
                break;
        }

        // Triangle svg output
        switch ($settings['triangle_corners']) {
            case false:
                $triangle_svg .= '<svg class="videobox_icon" viewBox="0 0 10 10"><polygon points="1,0 1,10 8.5,5"/></svg>';
                break;
            case true:
                $triangle_svg .= '<svg class="videobox_icon" viewBox="0 0 232 232"><path d="M203,99L49,2.3c-4.5-2.7-10.2-2.2-14.5-2.2 c-17.1,0-17,13-17,16.6v199c0,2.8-0.07,16.6,17,16.6c4.3,0,10,0.4,14.5-2.2 l154-97c12.7-7.5,10.5-16.5,10.5-16.5S216,107,204,100z"/></svg>';
                break;
        }

        // Render html
        $uniqrel = uniqid();

        $output = '<div '.($this->get_render_attribute_string( 'video-wrap' )).'>';
            $output .= '<div class="videobox_content">';
                $output .= !empty($settings['bg_image']['url']) ? '<div class="videobox_background">'.wp_get_attachment_image( $settings['bg_image']['id'] , 'full' ).'</div>' : '';
                $output .= !empty($settings['bg_image']['url']) ? '<div class="videobox_link_wrapper">' : '';
                $output .= !empty($settings['title']) ? '<'.$settings['title_tag'].' class="title">'.esc_html__($settings['title']).'</'.$settings['title_tag'].'>' : '';
                $lightbox_url = Embed::get_embed_url( $settings['link'], [], [] );
                $lightbox_options = [
                    'type' => 'video',
                    'url' => $lightbox_url,
                    'modalOptions' => [
                        'id' => 'elementor-lightbox-' . $this->get_id(),
                    ],
                ];
                $this->add_render_attribute( 'video-lightbox', [
                    'class' => 'videobox_link videobox',
                    'data-elementor-open-lightbox' => 'yes',
                    'data-elementor-lightbox' =>  wp_json_encode( $lightbox_options ),
                ] );
                $output .= '<div '. $this->get_render_attribute_string( 'video-lightbox' ) . '>';
                    $output .= $triangle_svg;
                    $output .= $animated_element;
                $output .= '</div>';
                $output .= !empty($settings['bg_image']['url']) ? '</div>' : '';
            $output .= '</div>';
        $output .= '</div>';

        echo \Thegov_Theme_Helper::render_html($output);

    }

}