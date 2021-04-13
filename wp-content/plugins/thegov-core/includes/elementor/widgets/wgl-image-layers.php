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

class Wgl_Image_Layers extends Widget_Base {

    public function get_name() {
        return 'wgl-image-layers';
    }

    public function get_title() {
        return esc_html__('Wgl Image Layers', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-image-layers';
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
        $this->start_controls_section('wgl_image_layers_section',
            array(
                'label'         => esc_html__('General Settings', 'thegov-core'),
            )
        );

        $this->add_control(
            'interval',
            array(
                'label' => esc_html__( 'Enter Interval Images Appearing', 'thegov-core' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 50,
				'step' => 50,
				'default' => 600,
                'description' => esc_html__( 'Enter interval in milliseconds', 'thegov-core' ),
            )
        );

        $this->add_control(
            'transition',
            array(
                'label' => esc_html__( 'Enter Transition Speed', 'thegov-core' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 50,
				'step' => 50,
				'default' => 800,
                'description' => esc_html__( 'Enter transition speed in milliseconds', 'thegov-core' ),
            )
        );

        $this->add_control(
            'image_link',
            array(
                'label'             => esc_html__('Add Image Link', 'thegov-core'),
                'type'              => Controls_Manager::URL,
                'label_block'       => true,
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

        $repeater->add_control(
            'top_offset',
            array(
                'label'             => esc_html__('Top Offset', 'thegov-core'),
                'type'              => Controls_Manager::NUMBER,
                'min' => -100,
                'max' => 100,
				'step' => 1,
				'default' => '0',
                'description' => esc_html__( 'Enter offset in %, for example -100% or 100%', 'thegov-core' ),
            )
        );

        $repeater->add_control(
            'left_offset',
            array(
                'label'             => esc_html__('Left Offset', 'thegov-core'),
                'type'              => Controls_Manager::NUMBER,
                'min' => -100,
                'max' => 100,
				'step' => 1,
				'default' => '0',
                'description' => esc_html__( 'Enter offset in %, for example -100% or 100%', 'thegov-core' ),
            )
        );

        $repeater->add_control(
            'image_animation',
            array(
                'label'             => esc_html__('Layer Animation', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'fade_in'          => esc_html__('Fade In', 'thegov-core'),
                    'slide_up'          => esc_html__('Slide Up', 'thegov-core'),
                    'slide_down'          => esc_html__('Slide Down', 'thegov-core'),
                    'slide_left'          => esc_html__('Slide Left', 'thegov-core'),
                    'slide_right'          => esc_html__('Slide Right', 'thegov-core'),
                    'slide_big_up'          => esc_html__('Slide Big Up', 'thegov-core'),
                    'slide_big_down'          => esc_html__('Slide Big Down', 'thegov-core'),
                    'slide_big_left'          => esc_html__('Slide Big Left', 'thegov-core'),
                    'slide_big_right'          => esc_html__('Slide Big Right', 'thegov-core'),
                    'flip_x'          => esc_html__('Flip Horizontally', 'thegov-core'),
                    'flip_y'          => esc_html__('Flip Vertically', 'thegov-core'),
                    'zoom_in'          => esc_html__('Zoom In', 'thegov-core'),
                ],
                'default'           => 'fade_in',
            )
        );

        $repeater->add_control(
            'image_order',
            array(
                'label'             => esc_html__('Image z-index', 'thegov-core'),
                'type'              => Controls_Manager::NUMBER,
				'step'              => 1,
                'default'           => '1',
            )
        );

        $this->add_control(
            'items',
            array(
                'label'   => esc_html__( 'Layers', 'thegov-core' ),
                'type'    => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
            )
        );

        $this->end_controls_section();

    }

    protected function render() {

        wp_enqueue_script('appear', get_template_directory_uri() . '/js/jquery.appear.js', array(), false, false);

        $content = '';
        $animation_delay = 0;
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'image-layers', [
			'class' => [
                'wgl-image-layers',
            ],
        ] );

        if (!empty($settings['image_link']['url'])) {
            $this->add_render_attribute('image_link', 'class', 'image_link');
            $this->add_link_attributes('image_link', $settings['image_link']);
        }

        foreach ( $settings['items'] as $index => $item ) {

            $animation_delay = $animation_delay + $settings['interval'];

            $image_layer = $this->get_repeater_setting_key( 'image_layer', 'items' , $index );
            $this->add_render_attribute( $image_layer, [
                'src' => esc_url($item['thumbnail']['url']),
                'alt' => Control_Media::get_image_alt( $item['thumbnail'] ),
            ] );

            $image_wrapper = $this->get_repeater_setting_key( 'image_wrapper', 'items' , $index );
            $this->add_render_attribute( $image_wrapper, [
                'class' => [
                    'img-layer_image-wrapper',
                    esc_attr($item['image_animation'])
                ],
                'style' => 'z-index: '.esc_attr((int)$item['image_order']),
            ] );

            $layer_item = $this->get_repeater_setting_key( 'layer_item', 'items' , $index );
            $this->add_render_attribute( $layer_item, [
                'class' => ['img-layer_item'],
                'style' => 'transform: translate('.esc_attr($item['top_offset']).'%, '.esc_attr($item['left_offset']).'%);'
            ] );

            $layer_image = $this->get_repeater_setting_key( 'layer_image', 'items' , $index );
            $this->add_render_attribute( $layer_image, [
                'class' => ['img-layer_image'],
                'style' => 'transition: all '.$settings['transition'].'ms; transition-delay: '.$animation_delay.'ms;'
            ] );

            ob_start();

            ?><div <?php echo $this->get_render_attribute_string( $image_wrapper ); ?>>
                <div <?php echo $this->get_render_attribute_string( $layer_item ); ?>>
                    <div <?php echo $this->get_render_attribute_string( $layer_image ); ?>>
                        <img <?php echo $this->get_render_attribute_string( $image_layer ); ?> />
                    </div>
                </div>
            </div> <?php

            $content .= ob_get_clean();
        }


        ?><div <?php echo $this->get_render_attribute_string( 'image-layers' ); ?>><?php
            if ( !empty($settings['image_link']['url']) ) : ?><a <?php echo $this->get_render_attribute_string( 'image_link' ); ?>><?php endif;
                echo $content;
            if ( !empty($settings['image_link']['url']) ) : ?></a><?php endif;
        ?></div><?php

    }

}