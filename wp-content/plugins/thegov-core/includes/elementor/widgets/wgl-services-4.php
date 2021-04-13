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

class Wgl_Services_4 extends Widget_Base {

    public function get_name() {
        return 'wgl-services-4';
    }

    public function get_title() {
        return esc_html__('Wgl Services 4', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-services-4';
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

        Wgl_Icons::init( $this, array( 'label' => esc_html__('Services 4 ', 'thegov-core'), 'output' => '','section' => true, 'prefix' => '' ) );

        /*-----------------------------------------------------------------------------------*/
        /*  Content
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section('wgl_ib_content',
            array(
                'label'         => esc_html__('Service Content', 'thegov-core'),
            )
        );

        $this->add_control('ib_title',
            array(
                'label'         => esc_html__('Title', 'thegov-core'),
                'type'          => Controls_Manager::TEXTAREA,
                'label_block'   => true,
                'default'       => esc_html__('This is the heading​', 'thegov-core'),
                'dynamic'       => [ 'active' => true ]
            )
        );

        $this->add_control('ib_content',
            array(
                'label'             => esc_html__('Service Text', 'thegov-core'),
                'type'    => Controls_Manager::WYSIWYG,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Description Text', 'thegov-core' ),
				'label_block' => true,
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'text_link',
            array(
                'label'             => esc_html__('Link Text', 'thegov-core'),
                'type'              => Controls_Manager::TEXT,
            )
        );

        $repeater->add_control(
            'link',
            array(
                'label'             => esc_html__('Link', 'thegov-core'),
                'type'              => Controls_Manager::URL,
            )
        );

        $this->add_control(
            'items',
            array(
                'label'   => esc_html__( 'Links', 'thegov-core' ),
                'type'    => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{text_link}}',
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
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_wrap' => 'text-align: {{VALUE}};',
                ],
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

        $this->start_controls_tabs(
            'icon_colors',
            [
                'condition' => [
                    'icon_type'  => 'font',
                ],
            ]
        );

        $this->start_controls_tab(
            'icon_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'thegov-core' ),
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => esc_html__( 'Primary Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $second_color,
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'thegov-core' ),
            ]
        );

        $this->add_control(
            'hover_primary_color',
            [
                'label' => esc_html__( 'Primary Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}:hover .wgl-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'icon_space',
            array(
                'label' => esc_html__( 'Margin', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_media-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Size', 'thegov-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 16,
                        'max' => 100,
                    ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 45,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'icon_type'   => 'font',
                ]
            ]
        );

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
                'description'   => esc_html__( 'Choose your tag for service title', 'thegov-core' ),
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
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_fonts_title',
                'selector' => '{{WRAPPER}} .wgl-services_title',
            )
        );


        $this->start_controls_tabs( 'title_color_tab' );

        $this->start_controls_tab(
            'custom_title_color_normal',
            array(
                'label' => esc_html__( 'Normal' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-services_title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_title_color_hover',
            array(
                'label' => esc_html__( 'Hover' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'title_color_hover',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($header_font_color),
                'selectors' => array(
                    '{{WRAPPER}}:hover .wgl-services_title' => 'color: {{VALUE}};',
                    '{{WRAPPER}}:hover .wgl-services_title a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Content Section)
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'content_style_section',
            array(
                'label'     => esc_html__( 'Content', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control('content_tag',
            array(
                'label'         => esc_html__('Content Tag', 'thegov-core'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'div',
                'description'   => esc_html__( 'Choose your tag for service content', 'thegov-core' ),
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
            'content_offset',
            array(
                'label' => esc_html__( 'Content Offset', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_responsive_control(
            'content_padding',
            array(
                'label' => esc_html__( 'Content Padding', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'custom_content_mask_color',
                'label' => esc_html__( 'Background', 'thegov-core' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wgl-services_text',
                'condition'     => [
                    'custom_bg'   => 'custom',
                ],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_fonts_content',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => \Wgl_Addons_Elementor::$typography_3['font_family']],
                    'font_weight' => ['default' => \Wgl_Addons_Elementor::$typography_3['font_weight']],
                ],
                'selector' => '{{WRAPPER}} .wgl-services_text',
            )
        );

        $this->start_controls_tabs( 'content_color_tab' );

        $this->start_controls_tab(
            'custom_content_color_normal',
            array(
                'label' => esc_html__( 'Normal' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'content_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($main_font_color),
                'selectors' => array(
                    '{{WRAPPER}} .wgl-services_text' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_content_color_hover',
            array(
                'label' => esc_html__( 'Hover' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'content_color_hover',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($main_font_color),
                'selectors' => array(
                    '{{WRAPPER}}:hover .wgl-services_text' => 'color: {{VALUE}};'
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Links
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'button_style_section',
            array(
                'label'     => esc_html__( 'Links', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
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
                'selector' => '{{WRAPPER}} .wgl-services_readmore',
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
            'button_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => array(
                    '{{WRAPPER}} .wgl-services_link' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_button_color_hover',
            array(
                'label' => esc_html__( 'Hover' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'button_color_hover',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($theme_color),
                'selectors' => array(
                    '{{WRAPPER}} .wgl-services_link:hover' => 'color: {{VALUE}};'
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    public function render(){

        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'services', [
			'class' => [
                'wgl-services-4',
                'services_'.$settings['alignment']
            ],
        ] );

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

        // Icon/Image output
        ob_start();
        if (!empty($settings['icon_type'])) {
            $icons = new Wgl_Icons;
            echo $icons->build($this, $settings, array());
        }
        $services_media = ob_get_clean();

        ?>
        <div <?php echo $this->get_render_attribute_string( 'services' ); ?>>
            <div class="wgl-services_wrap"><?php
                if ($settings['icon_type'] != '') {?>
                <div class="wgl-services_media-wrap"><?php
                    if (!empty($services_media)){
                        echo $services_media;
                    }?>
                </div><?php
                }?>
                <div class="wgl-services_content-wrap">
                    <<?php echo $settings['title_tag']; ?> class="wgl-services_title"><?php echo wp_kses( $settings['ib_title'], $allowed_html );?></<?php echo $settings['title_tag']; ?>><?php
                    if (!empty($settings['ib_content'])) {?>
                        <<?php echo $settings['content_tag']; ?> class="wgl-services_text"><?php echo wp_kses( $settings['ib_content'], $allowed_html );?></<?php echo $settings['content_tag']; ?>><?php
                    }?>
                    <div class="services_links-wrapper"><?php
                        foreach ( $settings['items'] as $index => $item ) {
                            $services_links = $this->get_repeater_setting_key( 'services_links', 'items' , $index );

                            $this->add_render_attribute($services_links, 'class', 'wgl-services_link');
                            if (isset($item['link']['url'])) $this->add_link_attributes($services_links, $item['link']);

                            ?><div><a <?php echo $this->get_render_attribute_string( $services_links ); ?>><?php echo wp_kses( $item['text_link'], $allowed_html ); ?></a></div><?php
                        }?>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }

}