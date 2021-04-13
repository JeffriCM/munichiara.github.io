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

class Wgl_Counter extends Widget_Base {

    public function get_name() {
        return 'wgl-counter';
    }

    public function get_title() {
        return esc_html__('Wgl Counter', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-counter';
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

        /*-----------------------------------------------------------------------------------*/
        /*  Build Icon/Image Box
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section('wgl_counter_content',
            array(
                'label'         => esc_html__('Counter Content', 'thegov-core'),
            )
        );
        Wgl_Icons::init( $this, array( 'label' => esc_html__('Counter ', 'thegov-core'), 'output' => '','section' => false, 'prefix' => '' ) );

        $this->add_control(
            'start_value',
            [
                'label' => esc_html__( 'Start Value', 'thegov-core' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 0,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'end_value',
            [
                'label' => esc_html__( 'End Value', 'thegov-core' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 10,
                'default' => 120,
            ]
        );

        $this->add_control('prefix',
            array(
                'label'         => esc_html__('Counter Prefix', 'thegov-core'),
                'type'          => Controls_Manager::TEXT,
            )
        );

        $this->add_control('suffix',
            array(
                'label'         => esc_html__('Counter Suffix', 'thegov-core'),
                'type'          => Controls_Manager::TEXT,
                'placeholder'       => esc_html__('+', 'thegov-core'),
            )
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__( 'Animation Speed', 'thegov-core' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 100,
                'default' => 2000,
            ]
        );

        $this->add_control('counter_title',
            array(
                'label'         => esc_html__('Title', 'thegov-core'),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'default'       => esc_html__('This is the heading​', 'thegov-core'),
            )
        );

        $this->add_control(
            'title_block',
            array(
                'label' => esc_html__('Title Display Block', 'thegov-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            )
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
            )
        );

        /*End General Settings Section*/
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Headings Section)
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Media', 'thegov-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => esc_html__( 'Icon Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $theme_color,
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'color: {{VALUE}};',
                ],
                'condition'     => [
                    'icon_type'   => 'font',
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_size',
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
                    'size' => 58,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'icon_type'   => 'font',
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_space',
            array(
                'label' => esc_html__( 'Margin', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'   => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 20,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_media-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_responsive_control(
            'icon_padding',
            array(
                'label' => esc_html__( 'Padding', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_media-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_control(
            'counter_icon_border_radius',
            array(
                'label' => esc_html__( 'Border Radius', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_media-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            )
        );

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'media_background',
				'label' => esc_html__( 'Background', 'thegov-core' ),
				'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wgl-counter_media-wrap',
			]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'counter_icon_border',
				'selector' => '{{WRAPPER}} .wgl-counter_media-wrap'
			]
        );

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'counter_icon_shadow',
				'selector' => '{{WRAPPER}} .wgl-counter_media-wrap',
			]
		);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Headings Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'value_style_section',
            array(
                'label'     => esc_html__( 'Value', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'value_offset',
            array(
                'label' => esc_html__( 'Value Offset', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_value-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_fonts_value',
                'selector' => '{{WRAPPER}} .wgl-counter_value-wrap',
            )
        );

        $this->add_control(
            'value_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $theme_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-counter_value-wrap' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // Title Styles

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
                'description'   => esc_html__( 'Choose your tag for counter title', 'thegov-core' ),
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

        $this->add_responsive_control(
            'title_offset',
            array(
                'label' => esc_html__( 'Title Offset', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default'   => [
                    'top' => 15,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit'  => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_fonts_title',
                'selector' => '{{WRAPPER}} .wgl-counter_title',
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-counter_title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // Item Styles

        $this->start_controls_section(
            'counter_style_section',
            array(
                'label'     => esc_html__( 'Item', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'counter_offset',
            array(
                'label' => esc_html__( 'Margin', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_responsive_control(
            'counter_padding',
            array(
                'label' => esc_html__( 'Padding', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_control(
            'counter_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'counter_color_tab' );

        $this->start_controls_tab(
            'custom_counter_color_normal',
            array(
                'label' => esc_html__( 'Normal' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'bg_counter_color',
            array(
                'label' => esc_html__( 'Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .wgl-counter' => 'background-color: {{VALUE}};'
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'counter_border',
                'label'    => esc_html__( 'Border Type', 'thegov-core' ),
                'selector' => '{{WRAPPER}} .wgl-counter',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'counter_shadow',
                'selector' =>  '{{WRAPPER}} .wgl-counter',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_counter_color_hover',
            array(
                'label' => esc_html__( 'Hover' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'bg_counter_color_hover',
            array(
                'label' => esc_html__( 'Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}}:hover .wgl-counter' => 'background-color: {{VALUE}};'
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'counter_border_hover',
                'label'    => esc_html__( 'Border Type', 'thegov-core' ),
                'selector' => '{{WRAPPER}}:hover .wgl-counter',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'counter_shadow_hover',
                'selector' =>  '{{WRAPPER}}:hover .wgl-counter',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    public function render(){

        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'counter', [
			'class' => [
                'wgl-counter',
                'a'.$settings['alignment'],
                (bool)$settings['title_block'] ? 'title-block' : 'title-inline'
            ],
        ] );

        $this->add_render_attribute( 'counter_value', [
			'class' => [
                'wgl-counter_value',
                'value-placeholder'
            ],
			'data-start-value' => $settings['start_value'],
			'data-end-value' => $settings['end_value'],
			'data-speed' => $settings['speed'],
        ] );

        // Icon/Image output
        ob_start();
        if (!empty($settings['icon_type'])) {
            $icons = new Wgl_Icons;
            echo $icons->build($this, $settings, array());
        }
        $counter_media = ob_get_clean();

        ?>
        <div <?php echo $this->get_render_attribute_string( 'counter' ); ?>>
            <div class="wgl-counter_wrap"><?php
                if ($settings['icon_type'] != '') {?>
                <div class="wgl-counter_media-wrap"><?php
                    if (!empty($counter_media)){
                        echo $counter_media;
                    }?>
                </div><?php
                }?>
                <div class="wgl-counter_content-wrap">
                    <div class="wgl-counter_value-wrap"><?php
                        if (!empty($settings['prefix'])) {?>
                            <span class="wgl-counter_prefix"><?php echo $settings['prefix'];?></span><?php
                        }
                        if (!empty($settings['end_value'])) {?>
                            <div class="wgl-counter_value-placeholder">
                                <span <?php echo $this->get_render_attribute_string( 'counter_value' ); ?>><?php echo $settings['start_value'];?></span>
                                <span class="wgl-counter_value"><?php echo $settings['end_value'];?></span>
                            </div><?php
                        }
                        if (!empty($settings['suffix'])) {?>
                            <span class="wgl-counter_suffix"><?php echo $settings['suffix'];?></span><?php
                        }?>
                    </div>
                    <?php
                    if (!empty($settings['counter_title'])) {?>
                        <<?php echo $settings['title_tag']; ?> class="wgl-counter_title"><?php echo $settings['counter_title'];?></<?php echo $settings['title_tag']; ?>><?php
                    }?>
                </div>
            </div>
        </div>

        <?php
    }

}