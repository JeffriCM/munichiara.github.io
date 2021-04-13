<?php
namespace WglAddons\Widgets;

use WglAddons\Includes\Wgl_Icons;
use WglAddons\Includes\Wgl_Loop_Settings;
use WglAddons\Includes\Wgl_Carousel_Settings;
use WglAddons\Templates\WglPricingTable;
use WglAddons\Widgets\Wgl_Button;
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

class Wgl_Pricing_Table extends Widget_Base {

    public function get_name() {
        return 'wgl-pricing-table';
    }

    public function get_title() {
        return esc_html__('Wgl Pricing Table', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-pricing-table';
    }

    public function get_categories() {
        return [ 'wgl-extensions' ];
    }


    protected function register_controls() {
        $theme_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-custom-color'));
        $second_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-secondary-color'));
        $third_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-third-color'));
        $header_font_color = esc_attr(\Thegov_Theme_Helper::get_option('header-font')['color']);
        $main_font_color = esc_attr(\Thegov_Theme_Helper::get_option('main-font')['color']);

        /* Start General Settings Section */
        $this->start_controls_section(
            'wgl_pricing_table_section',
            array(
                'label' => esc_html__('Pricing Table Settings', 'thegov-core'),
            )
        );

        $this->add_control('pricing_title',
            array(
                'label' => esc_html__('Title', 'thegov-core'),
                'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your title', 'thegov-core' ),
				'default' => esc_html__( 'Basic', 'thegov-core' ),
				'label_block' => true,
            )
        );

        $this->add_control('pricing_cur',
            array(
                'label' => esc_html__('Currency', 'thegov-core'),
                'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your currency', 'thegov-core' ),
				'default' => esc_html__( '$', 'thegov-core' ),
				'label_block' => true,
            )
        );

        $this->add_control('pricing_price',
            array(
                'label' => esc_html__('Price', 'thegov-core'),
                'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your price', 'thegov-core' ),
				'default' => esc_html__( '99', 'thegov-core' ),
				'label_block' => true,
            )
        );

        $this->add_control('pricing_quantity',
            array(
                'label' => esc_html__('Quantity unit', 'thegov-core'),
                'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your quantity', 'thegov-core' ),
				'default' => esc_html__( '/ per month', 'thegov-core' ),
				'label_block' => true,
            )
        );

        $this->add_control('pricing_desc',
            array(
                'label' => esc_html__('Description', 'thegov-core'),
                'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your description', 'thegov-core' ),
				'label_block' => true,
            )
        );

        $this->add_control('content',
            array(
                'label' => esc_html__( 'Content', 'thegov-core' ),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
            )
        );

        $this->add_control('hover_animation',
            array(
                'label' => esc_html__('Enable hover animation','thegov-core' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'thegov-core' ),
                'label_off' => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
				'description' => esc_html__( 'Lift up the item on hover.', 'thegov-core' ),
            )
        );

        /*End General Settings Section*/
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Button Section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_pricing_table_button',
            array(
                'label' => esc_html__('Pricing Table Button', 'thegov-core'),
            )
        );


        $this->add_control('button_title',
            array(
                'label' => esc_html__('Button Text', 'thegov-core'),
                'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Buy now', 'thegov-core' ),
				'label_block' => true,
            )
        );

        $this->add_control('link',
            array(
                'label' => esc_html__('Button Link', 'thegov-core'),
                'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
                ],
				'label_block' => true,
            )
        );

        $this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pricing_footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pricing_footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
            'title_style_section',
            array(
                'label' => esc_html__( 'Title', 'thegov-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'pricing_title_typo',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_family']],
                ],
                'selector' => '{{WRAPPER}} .pricing_title',
            )
        );

        $this->add_control(
            'custom_title_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .pricing_title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'custom_bg_title_color',
            array(
                'label' => esc_html__( 'Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $theme_color,
                'selectors' => array(
                    '{{WRAPPER}} .pricing_title' => 'background: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pricing_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pricing_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'title_border',
				'selector' => '{{WRAPPER}} .pricing_title',
			]
		);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Price Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'price_style_section',
            array(
                'label' => esc_html__( 'Price', 'thegov-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'pricing_price_typo',
                'selector' => '{{WRAPPER}} .pricing_price_wrap',
            )
        );

        $this->add_control(
            'custom_price_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
                'selectors' => array(
                    '{{WRAPPER}} .pricing_price_wrap' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
			'price_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pricing_price_wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Header Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'header_style_section',
            array(
                'label' => esc_html__( 'Header', 'thegov-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'header_background',
				'label' => esc_html__( 'Header Background', 'thegov-core' ),
				'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .pricing_header:before',
			]
        );

        $this->add_control(
            'custom_bg_divider_color',
            array(
                'label' => esc_html__( 'Divider Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $theme_color,
                'selectors' => array(
                    '{{WRAPPER}} .pricing_header:before' => 'background: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Description Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'desc_style_section',
            array(
                'label' => esc_html__( 'Description', 'thegov-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'pricing_desc_typo',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_weight' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_weight']],
                ],
                'selector' => '{{WRAPPER}} .pricing_desc',
            )
        );

        $this->add_control(
            'custom_desc_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($main_font_color),
                'selectors' => array(
                    '{{WRAPPER}} .pricing_desc' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
			'desc_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pricing_desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Content Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'content_style_section',
            array(
                'label' => esc_html__( 'Content', 'thegov-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'pricing_content_typo',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_family']],
                ],
                'selector' => '{{WRAPPER}} .pricing_content',
            )
        );

        $this->add_control(
            'custom_content_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $header_font_color,
                'selectors' => array(
                    '{{WRAPPER}} .pricing_content' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
			'contet_margin',
			[
				'label' => esc_html__( 'Margin', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pricing_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Background Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'bg_style_section',
            array(
                'label' => esc_html__( 'Background', 'thegov-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__( 'Background', 'thegov-core' ),
				'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .pricing_plan_wrap',
			]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'bg_border',
				'selector' => '{{WRAPPER}} .pricing_plan_wrap',
			]
		);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Button Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Button', 'thegov-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'thegov-core' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'thegov-core' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
                ],
                'default' => $theme_color,
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'thegov-core' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
                ],
                'default' => $header_font_color,
				'selectors' => [
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'thegov-core' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 30,
                    'right' => 30,
                    'bottom'=> 30,
                    'left'  => 30,
                ],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'thegov-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

        $this->end_controls_section();

    }

    protected function render(){

        $settings = $this->get_settings_for_display();

        if (!empty($settings['button_title'])) {
            // button options array
            $button_options = array(
                'icon_type' => '',
                'text' => $settings['button_title'],
                'link' => $settings['link'],
                'size' => 'xl',
            );
        }

        // Wrapper classes
        $pricing_wrap_classes = (bool)$settings['hover_animation'] ? ' hover-animation' : '';

        // Title output
        $pricing_title_out = !empty($settings['pricing_title']) ? '<h4 class="pricing_title">'.esc_html($settings['pricing_title']).'</h4>' : '';

        // Currency output
        $pricing_cur_out = !empty($settings['pricing_cur']) ? '<span class="pricing_cur">'.esc_html($settings['pricing_cur']).'</span>' : '';

        // Price output
        if (isset($settings['pricing_price'])) {
            preg_match( "/(\d+)(\.| |,)(\d+)$/", $settings['pricing_price'], $matches, PREG_OFFSET_CAPTURE );
            switch (isset($matches[0])) {
                case false:
                    $pricing_price_out = '<div class="pricing_price">'.esc_html($settings['pricing_price']).'</div>';
                    break;
                case true:
                    $pricing_price_out = '<div class="pricing_price">';
                        $pricing_price_out .= esc_html($matches[1][0]);
                        $pricing_price_out .= '<span class="price_decimal">'.esc_html($matches[3][0]).'</span>';
                    $pricing_price_out .= '</div>';
                    break;
            }
        }

        // Price Quantity unit
        $pricing_quantity_unit = !empty($settings['pricing_quantity']) ? '<div class="pricing_quantity-unit">'.esc_html($settings['pricing_quantity']).'</div>' : '';

        // Price description output
        $pricing_desc_out = !empty($settings['pricing_desc']) ? '<div class="pricing_desc">'.esc_html($settings['pricing_desc']).'</div>' : '';

        // Content
        $pricing_content = !empty($settings['content']) ? $settings['content'] : '';

        // Button output
        ob_start();
            echo Wgl_Button::init_button($this, $button_options);
        $pricing_button = ob_get_clean();

        // Render html
        $pricing_inner = '<div class="pricing_header">';
            $pricing_inner .= $pricing_title_out;
            $pricing_inner .= '<div class="pricing_price_wrap">';
                $pricing_inner .= $pricing_price_out;
                $pricing_inner .= $pricing_cur_out;
                $pricing_inner .= $pricing_quantity_unit;
            $pricing_inner .= '</div>';
            $pricing_inner .= $pricing_desc_out;
        $pricing_inner .= '</div>';
        $pricing_inner .= '<div class="pricing_content">';
            $pricing_inner .= $pricing_content;
        $pricing_inner .= '</div>';
        $pricing_inner .= '<div class="pricing_footer">';
            $pricing_inner .= !empty($settings['button_title']) ? $pricing_button : '';
        $pricing_inner .= '</div>';


        $output = '<div class="wgl-pricing_plan'.esc_attr($pricing_wrap_classes).'">';
            $output .= '<div class="pricing_plan_wrap">';
                $output .= $pricing_inner;
            $output .= '</div>';
        $output .= '</div>';

        echo \Thegov_Theme_Helper::render_html($output);

    }

}