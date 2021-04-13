<?php
namespace WglAddons\Widgets;

use WglAddons\Includes\Wgl_Loop_Settings;
use WglAddons\Includes\Wgl_Carousel_Settings;
use WglAddons\Templates\WglTeam;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Wgl_Team extends Widget_Base {

    public function get_name() {
        return 'wgl-team';
    }

    public function get_title() {
        return esc_html__('Wgl Team', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-team';
    }

    public function get_categories() {
        return [ 'wgl-extensions' ];
    }

    // Adding the controls fields for the premium title
    // This will controls the animation, colors and background, dimensions etc
    protected function register_controls() {
        $theme_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-custom-color'));
        $theme_color_secondary = esc_attr(\Thegov_Theme_Helper::get_option('theme-secondary-color'));
        $header_font = \Thegov_Theme_Helper::get_option('header-font');

        /* Start General Settings Section */
        $this->start_controls_section('wgl_team_section',
            array(
                'label'         => esc_html__('Team Posts Settings', 'thegov-core'),
            )
        );

        $this->add_control('posts_per_line',
            array(
                'label'             => esc_html__('Columns in Row', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    '1'          => esc_html__('1', 'thegov-core'),
                    '2'          => esc_html__('2', 'thegov-core'),
                    '3'          => esc_html__('3', 'thegov-core'),
                    '4'          => esc_html__('4', 'thegov-core'),
                    '5'          => esc_html__('5', 'thegov-core'),
                    '6'          => esc_html__('6', 'thegov-core'),
                ],
                'default'           => '3',
            )
        );

        $this->add_control('info_align',
            array(
                'label'             => esc_html__('Team Info Alignment', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'left'          => esc_html__('Left', 'thegov-core'),
                    'center'        => esc_html__('Center', 'thegov-core'),
                    'right'         => esc_html__('Right', 'thegov-core'),
                ],
                'default'           => 'left',
            )
        );

        $this->add_control('grayscale_anim',
            array(
                'label'        => esc_html__('Add Grayscale Animation','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control('info_anim',
            array(
                'label'        => esc_html__('Add Info Fade Animation','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control('single_link_wrapper',
            array(
                'label'        => esc_html__('Add Link for Image','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control('single_link_heading',
            array(
                'label'        => esc_html__('Add Link for Heading','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        /*End General Settings Section*/
        $this->end_controls_section();

                $this->start_controls_section(
            'display_section',
            array(
                'label' => esc_html__('Display', 'thegov-core' ),
            )
        );

        $this->add_control('hide_title',
            array(
                'label'        => esc_html__('Hide Title','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control('hide_department',
            array(
                'label'        => esc_html__('Hide Department','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control('hide_counter',
            array(
                'label'        => esc_html__('Hide Counter','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control('hide_soc_icons',
            array(
                'label'        => esc_html__('Hide Social Icons','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control('hide_content',
            array(
                'label'        => esc_html__('Hide Content','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control('letter_count',
            array(
                'label'       => esc_html__('Content Letters Count', 'thegov-core'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '100',
                'min'         => 1,
                'step'        => 1,
                'condition'     => [
                    'hide_content!'  => 'yes',
                ]
            )
        );


        $this->end_controls_section();

        Wgl_Carousel_Settings::options($this);

        /*-----------------------------------------------------------------------------------*/
        /*  Build Query Section
        /*-----------------------------------------------------------------------------------*/

        Wgl_Loop_Settings::init( $this, array('post_type' => 'team', 'hide_cats' => true,
                    'hide_tags' => true) );

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'item_style_section',
            [
                'label' => esc_html__( 'Items Style', 'thegov-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label' => esc_html__( 'Gap Items', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'top' => 0,
                    'left' => 15,
                    'right' => 15,
                    'bottom' => 50,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl_module_team .team-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wgl_module_team .team-items_wrap' => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right: -{{RIGHT}}{{UNIT}}; margin-bottom: -{{BOTTOM}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'background_style_section',
            array(
                'label' => esc_html__( 'Overlay', 'thegov-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'bg_color_type',
            array(
                'label' => esc_html__('Customize Backgrounds','thegov-core' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'thegov-core' ),
                'label_off' => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->start_controls_tabs( 'background_color_tabs' );

        $this->start_controls_tab(
            'custom_background_color_normal',
            array(
                'label' => esc_html__( 'Normal' , 'thegov-core' ),
                'condition' => [ 'bg_color_type' => 'yes' ],
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'background_color',
                'label' => esc_html__( 'Background Idle', 'thegov-core' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wgl_module_team .team-image:before',
                'condition' => [ 'bg_color_type' => 'yes' ],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_background_color_hover',
            array(
                'label' => esc_html__( 'Hover' , 'thegov-core' ),
                'condition' => [ 'bg_color_type' => 'yes' ],
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'background_hover_color',
                'label' => esc_html__( 'Background Hover', 'thegov-core' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wgl_module_team .team-image:after',
                'condition' => [ 'bg_color_type' => 'yes' ],
            )
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

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
                'name' => 'title_team_headings',
                'selector' => '{{WRAPPER}} .team-title',
            )
        );

        $this->add_control(
            'custom_title_color',
            array(
                'label'        => esc_html__('Customize Colors','thegov-core' ),

                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->start_controls_tabs( 'title_color_tabs' );

        $this->start_controls_tab(
            'custom_title_color_normal',
            array(
                'label' => esc_html__( 'Normal' , 'thegov-core' ),
                'condition'     => [
                    'custom_title_color'   => 'yes',
                ],
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => esc_html__( 'Title Idle', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $header_font['color'],
                'selectors' => array(
                    '{{WRAPPER}} .team-title' => 'color: {{VALUE}}',
                ),
                'condition'     => [
                    'custom_title_color'   => 'yes',
                ],
            )
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_title_color_hover',
            array(
                'label' => esc_html__( 'Hover' , 'thegov-core' ),
                'condition'     => [
                    'custom_title_color'   => 'yes',
                ],
            )
        );

        $this->add_control(
            'title_hover_color',
            array(
                'label' => esc_html__( 'Title Hover', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($theme_color),
                'selectors' => array(
                    '{{WRAPPER}} .team-title:hover' => 'color: {{VALUE}}',
                ),
                'condition'     => [
                    'custom_title_color'   => 'yes',
                ],
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'department_style_section',
            array(
                'label'     => esc_html__( 'Meta Info', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'custom_depart_color',
            array(
                'label'        => esc_html__('Customize Color','thegov-core' ),

                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'depart_color',
            array(
                'label' => esc_html__( 'Meta Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $theme_color,
                'selectors' => array(
                    '{{WRAPPER}} .team-department, {{WRAPPER}} .wgl_module_team .team-meta_info .team-counter' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wgl_module_team .team-meta_info .line' => 'background: {{VALUE}}',
                ),
                'condition'     => [
                    'custom_depart_color'   => 'yes',
                ],
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'soc_icons_style_section',
            array(
                'label'     => esc_html__( 'Social Icons', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'custom_soc_color',
            array(
                'label'        => esc_html__('Customize Colors','thegov-core' ),

                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->start_controls_tabs( 'soc_color_tabs' );

        $this->start_controls_tab(
            'custom_soc_color_normal',
            array(
                'label' => esc_html__( 'Normal' , 'thegov-core' ),
                'condition'     => [
                    'custom_soc_color'   => 'yes',
                ],
            )
        );

        $this->add_control(
            'soc_color',
            array(
                'label' => esc_html__( 'Icon Idle', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => $header_font['color'],
                'selectors' => array(
                    '{{WRAPPER}} .team-item_info .team-info_icons .team-icon a' => 'color: {{VALUE}}',
                ),
                'condition'     => [
                    'custom_soc_color'   => 'yes',
                ],
            )
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_soc_color_hover',
            array(
                'label' => esc_html__( 'Hover' , 'thegov-core' ),
                'condition'     => [
                    'custom_soc_color'   => 'yes',
                ],
            )
        );

        $this->add_control(
            'soc_hover_color',
            array(
                'label' => esc_html__( 'Icon Hover', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#909aa3',
                'selectors' => array(
                    '{{WRAPPER}} .team-item_info .team-info_icons .team-icon a:hover' => 'color: {{VALUE}}',
                ),
                'condition'     => [
                    'custom_soc_color'   => 'yes',
                ],
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'custom_soc_bg_color',
            array(
                'label'        => esc_html__('Customize Backgrounds','thegov-core' ),

                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->start_controls_tabs( 'soc_background_tabs' );

        $this->start_controls_tab(
            'custom_soc_bg_normal',
            array(
                'label' => esc_html__( 'Normal' , 'thegov-core' ),
                'condition'     => [
                    'custom_soc_bg_color'   => 'yes',
                ],
            )
        );

        $this->add_control(
            'soc_bg_color',
            array(
                'label' => esc_html__( 'Background Idle', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f3f3f3',
                'selectors' => array(
                    '{{WRAPPER}} .team-item_info .team-info_icons .team-icon a' => 'background: {{VALUE}}',
                ),
                'condition'     => [
                    'custom_soc_bg_color'   => 'yes',
                ],
            )
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_soc_bg_hover',
            array(
                'label' => esc_html__( 'Hover' , 'thegov-core' ),
                'condition'     => [
                    'custom_soc_bg_color'   => 'yes',
                ],
            )
        );

        $this->add_control(
            'soc_bg_hover_color',
            array(
                'label' => esc_html__( 'Background Hover', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f3f3f3',
                'selectors' => array(
                    '{{WRAPPER}} .team-item_info .team-info_icons .team-icon:hover a' => 'background: {{VALUE}}',
                ),
                'condition'     => [
                    'custom_soc_bg_color'   => 'yes',
                ],
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


    }

    protected function render() {
        $atts = $this->get_settings_for_display();

        $team = new WglTeam();
        echo $team->render($atts);

    }

}