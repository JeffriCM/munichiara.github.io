<?php
namespace WglAddons\Widgets;

use WglAddons\Includes\Wgl_Loop_Settings;
use WglAddons\Includes\Wgl_Carousel_Settings;
use WglAddons\Templates\WglEvents;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Wgl_Events extends Widget_Base {

    public function get_name() {
        return 'wgl-events';
    }

    public function get_title() {
        return esc_html__('Wgl Events', 'thegov-core' );
    }

    public function get_icon() {
        return 'wgl-events';
    }

    public function get_script_depends() {
        return [
            'slick',
            'jarallax',
            'jarallax-video',
            'imagesloaded',
            'isotope',
            'wgl-elementor-extensions-widgets',
        ];
    }

    public function get_categories() {
        return [ 'wgl-extensions' ];
    }

    // Adding the controls fields for the premium title
    // This will controls the animation, colors and background, dimensions etc
    protected function register_controls() {
        $theme_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-custom-color'));
        $theme_secondary_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-secondary-color'));
        $main_font_color = esc_attr(\Thegov_Theme_Helper::get_option('main-font')['color']);
        $header_font_color = esc_attr(\Thegov_Theme_Helper::get_option('header-font')['color']);

        /* Start General Settings Section */
        $this->start_controls_section('wgl_events_section',
            array(
                'label'         => esc_html__('Settings', 'thegov-core'),
            )
        );

        /*Title Text*/
        $this->add_control('events_title',
            array(
                'label'         => esc_html__('Title', 'thegov-core'),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'dynamic'       => [ 'active' => true ]
            )
        );

        $this->add_control('events_subtitle',
            array(
                'label'         => esc_html__('Sub Title', 'thegov-core'),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'dynamic'       => [ 'active' => true ]
            )
        );

        $this->add_control(
            'events_columns',
            array(
                'label'          => esc_html__( 'Grid Columns Amount', 'thegov-core' ),
                'type'           => Controls_Manager::SELECT,

                'options'        => array(
                    '12' => esc_html__( 'One', 'thegov-core' ),
                    '6'  => esc_html__( 'Two', 'thegov-core' ),
                    '4'  => esc_html__( 'Three', 'thegov-core' ),
                    '3'  =>esc_html__( 'Four', 'thegov-core' )
                ),
                'default'        => '4',
                'tablet_default' => 'inherit',
                'mobile_default' => '1',
                'frontend_available' => true,
                'label_block'  => true,
            )
        );

        $this->add_control('events_layout',
            array(
                'label'         => esc_html__( 'Layout', 'thegov-core' ),
                'type'          => 'wgl-radio-image',
                'options'       => [
                    'grid'      => [
                        'title'=> esc_html__( 'Grid', 'thegov-core' ),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/layout_grid.png',
                    ],
                    'masonry'    => [
                        'title'=> esc_html__( 'Masonry', 'thegov-core' ),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/layout_masonry.png',
                    ],
                    'carousel'     => [
                        'title'=> esc_html__( 'Carousel', 'thegov-core' ),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/layout_carousel.png',
                    ],
                ],
                'default'       => 'grid',            )
        );

        $this->add_control('show_filter',
            array(
                'label'        => esc_html__('Show Filter','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control('filter_align',
            array(
                'label'             => esc_html__('Filter Align', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'left'          => esc_html__('Left', 'thegov-core'),
                    'right'         => esc_html__('Right', 'thegov-core'),
                    'center'        => esc_html__('Сenter', 'thegov-core'),
                ],
                'default'           => 'left',
                'condition'     => [
                    'show_filter'  => 'yes',
                ]
            )
        );

        $this->add_control('events_navigation',
            array(
                'label'             => esc_html__('Navigation Type', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'none'          => esc_html__('None', 'thegov-core'),
                    'pagination'    => esc_html__('Pagination', 'thegov-core'),
                    'load_more'     => esc_html__('Load More', 'thegov-core'),
                ],
                'default'           => 'none',
                'condition'     => [
                    'events_layout'   => array('grid', 'masonry')
                ]
            )
        );

        $this->add_control('events_navigation_align',
            array(
                'label'             => esc_html__('Navigation\'s Alignment', 'thegov-core'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'left'          => esc_html__('Left', 'thegov-core'),
                    'center'        => esc_html__('Center', 'thegov-core'),
                    'right'         => esc_html__('Right', 'thegov-core'),
                ],
                'default'           => 'left',
                'condition'         => [
                    'events_navigation'   => 'pagination'
                ]
            )
        );

        $this->add_control(
            'spacer_navigation',
            array(
                'label' => esc_html__( 'Navigation Spacer Top', 'thegov-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => [ 'px', 'em', 'rem', 'vw' ],
                'condition'     => [
                    'events_navigation'   => 'pagination',
                    'events_layout'   => array('grid', 'masonry')
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '-20',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            )
        );

        $this->add_control('items_load',
            array(
                'label'         => esc_html__('Items to be loaded', 'thegov-core'),
                'type'          => Controls_Manager::TEXT,
                'default'       => esc_html__('4','thegov-core'),
                'condition'     => [
                    'events_navigation'   => 'load_more',
                    'events_layout'   => array('grid', 'masonry')
                ]
            )
        );

        $this->add_control('name_load_more',
            array(
                'label'         => esc_html__('Button Text', 'thegov-core'),
                'type'          => Controls_Manager::TEXT,
                'default'       => esc_html__('Load More','thegov-core'),
                'condition'     => [
                    'events_navigation'   => 'load_more',
                    'events_layout'   => array('grid', 'masonry')
                ]
            )
        );

        $this->add_control(
            'spacer_load_more',
            array(
                'label' => esc_html__( 'Button Spacer Top', 'thegov-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 200,
                    ],
                ],
                'size_units' => [ 'px', 'em', 'rem', 'vw' ],
                'condition'     => [
                    'events_navigation'   => 'load_more',
                    'events_layout'   => array('grid', 'masonry')
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '20',
                ],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
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

        $this->add_control(
            'hide_media',
            array(
                'label'        => esc_html__('Hide Media?','thegov-core' ),

                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'hide_events_title',
            array(
                'label'        => esc_html__('Hide Title?','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'hide_content',
            array(
                'label'        => esc_html__('Hide Excerpt?','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'hide_postmeta',
            array(
                'label'        => esc_html__('Hide all post-meta?','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'meta_categories',
            array(
                'label'        => esc_html__('Hide post-meta categories?','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
                'condition'     => [
                    'hide_postmeta!'   => 'yes',
                ]
            )
        );

        $this->add_control(
            'meta_author',
            array(
                'label'        => esc_html__('Hide post-meta author?','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'     => [
                    'hide_postmeta!'   => 'yes',
                ]
            )
        );

        $this->add_control(
            'meta_comments',
            array(
                'label'        => esc_html__('Hide post-meta comments?','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'     => [
                    'hide_postmeta!'   => 'yes',
                ]
            )
        );

        $this->add_control(
            'meta_location',
            array(
                'label'        => esc_html__('Hide post-meta location?','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
                'condition'     => [
                    'hide_postmeta!'   => 'yes',
                ]
            )
        );

        $this->add_control(
            'meta_date',
            array(
                'label'        => esc_html__('Hide post-meta date?','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
                'condition'     => [
                    'hide_postmeta!'   => 'yes',
                ]
            )
        );

        $this->add_control(
            'read_more_hide',
            array(
                'label'        => esc_html__('Hide \'Read More\' button?','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control('read_more_text',
            array(
                'label'         => esc_html__('Read More Text', 'thegov-core'),
                'type'          => Controls_Manager::TEXT,
                'default'       => esc_html__('Read More','thegov-core'),
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'read_more_hide'   => '',
                ]
            )
        );

        $this->add_control('content_letter_count',
            array(
                'label'       => esc_html__('Characters Amount in Content', 'thegov-core'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '95',
                'min'         => 1,
                'step'        => 1,
                'condition'     => [
                    'hide_content'   => '',
                ]
            )
        );

        $this->end_controls_section();


        /* Start Carousel General Settings Section */
        $this->start_controls_section('wgl_carousel_section',
            array(
                'label'         => esc_html__('Carousel Options', 'thegov-core'),
                'condition'     => [
                    'events_layout'   => 'carousel',
                ]
            )
        );

        $this->add_control(
            'autoplay',
            array(
                'label'        => esc_html__('Autoplay','thegov-core' ),

                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control('autoplay_speed',
            array(
                'label'       => esc_html__('Autoplay Speed', 'thegov-core'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '3000',
                'min'         => 1,
                'step'        => 1,
                'condition'     => [
                    'autoplay'  => 'yes',
                ]
            )
        );

        $this->add_control(
            'use_pagination',
            array(
                'label'        => esc_html__('Add Pagination control','thegov-core' ),

                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control('pag_type',
            array(
                'label'         => esc_html__( 'Pagination Type', 'thegov-core' ),
                'type'          => 'wgl-radio-image',
                'options'       => [
                    'circle'      => [
                        'title'=> esc_html__( 'Circle', 'thegov-core' ),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/pag_circle.png',
                    ],
                    'circle_border' => [
                        'title'=> esc_html__( 'Empty Circle', 'thegov-core' ),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/pag_circle_border.png',
                    ],
                    'square'    => [
                        'title'=> esc_html__( 'Square', 'thegov-core' ),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/pag_square.png',
                    ],
                    'square_border'    => [
                        'title'=> esc_html__( 'Empty Square', 'thegov-core' ),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/pag_square_border.png',
                    ],
                    'line'    => [
                        'title'=> esc_html__( 'Line', 'thegov-core' ),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/pag_line.png',
                    ],
                    'line_circle'    => [
                        'title'=> esc_html__( 'Line - Circle', 'thegov-core' ),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_composer_addon/icons/pag_line_circle.png',
                    ],
                ],
                'default'       => 'circle',
                'condition'     => [
                    'use_pagination'  => 'yes',
                ]
            )
        );

        $this->add_control('pag_offset',
            array(
                'label'       => esc_html__('Pagination Top Offset', 'thegov-core'),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 1,
                'step'        => 1,
                'default'     => 70,
                'condition'     => [
                    'use_pagination'  => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-carousel .slick-dots' => 'margin-top: {{VALUE}}px;',
                ],
            )
        );

        $this->add_control(
            'custom_pag_color',
            array(
                'label'        => esc_html__('Custom Pagination Color','thegov-core' ),

                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'pag_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default'      => esc_attr($theme_color),
                'condition'     => [
                    'custom_pag_color'  => 'yes',
                ]
            )
        );

        $this->add_control(
            'use_navigation',
            array(
                'label'        => esc_html__('Add Navigation control','thegov-core' ),

                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );


        $this->add_control('custom_resp',
            array(
                'label'        => esc_html__('Customize Responsive','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'heading_desktop',
            array(
                'label' => esc_html__( 'Desktop Settings', 'thegov-core' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
                'condition'         => [
                    'custom_resp'   => 'yes',
                ]
            )
        );

        $this->add_control('resp_medium',
            array(
                'label'             => esc_html__('Desktop Screen Breakpoint', 'thegov-core'),
                'type'              => Controls_Manager::NUMBER,
                'default'           => '1025',
                'min'               => 1,
                'step'              => 1,
                'condition'         => [
                    'custom_resp'   => 'yes',
                ]
            )
        );

        $this->add_control('resp_medium_slides',
            array(
                'label'             => esc_html__('Slides to show', 'thegov-core'),
                'type'              => Controls_Manager::NUMBER,
                'min'               => 1,
                'step'              => 1,
                'condition'         => [
                    'custom_resp'   => 'yes',
                ]
            )
        );

        $this->add_control(
            'heading_tablet',
            array(
                'label' => esc_html__( 'Tablet Settings', 'thegov-core' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
                'condition'         => [
                    'custom_resp'   => 'yes',
                ]
            )
        );

        $this->add_control('resp_tablets',
            array(
                'label'             => esc_html__('Tablet Screen Breakpoint', 'thegov-core'),
                'type'              => Controls_Manager::NUMBER,
                'default'           => '800',
                'min'               => 1,
                'step'              => 1,
                'condition'         => [
                    'custom_resp'   => 'yes',
                ]
            )
        );

        $this->add_control('resp_tablets_slides',
            array(
                'label'             => esc_html__('Slides to show', 'thegov-core'),
                'type'              => Controls_Manager::NUMBER,
                'min'               => 1,
                'step'              => 1,
                'condition'         => [
                    'custom_resp'   => 'yes',
                ]
            )
        );

        $this->add_control(
            'heading_mobile',
            array(
                'label' => esc_html__( 'Mobile Settings', 'thegov-core' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
                'condition'         => [
                    'custom_resp'   => 'yes',
                ]
            )
        );

        $this->add_control('resp_mobile',
            array(
                'label'             => esc_html__('Mobile Screen Breakpoint', 'thegov-core'),
                'type'              => Controls_Manager::NUMBER,
                'default'           => '480',
                'min'               => 1,
                'step'              => 1,
                'condition'         => [
                    'custom_resp'   => 'yes',
                ]
            )
        );

        $this->add_control('resp_mobile_slides',
            array(
                'label'             => esc_html__('Slides to show', 'thegov-core'),
                'type'              => Controls_Manager::NUMBER,
                'min'               => 1,
                'step'              => 1,
                'condition'         => [
                    'custom_resp'   => 'yes',
                ]
            )
        );

        /*End General Settings Section*/
        $this->end_controls_section();



        /*-----------------------------------------------------------------------------------*/
        /*  Build Query Section
        /*-----------------------------------------------------------------------------------*/

        Wgl_Loop_Settings::init( $this, array('post_type' => 'event', 'events_order' => true, 'hide_cats' => true,
                    'hide_tags' => true) );

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section
        /*-----------------------------------------------------------------------------------*/

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Filter Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'filter_cats_style_section',
            array(
                'label'     => esc_html__( 'Filter', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'show_filter'   => 'yes',
                ],
            )
        );

        $this->add_responsive_control(
            'filter_cats_padding',
            array(
                'label' => esc_html__( 'Filter padding', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'   => [
                    'top'       => 0,
                    'left'      => 0,
                    'right'     => 0,
                    'bottom'    => 0,
                    'unit'      => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-filter a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_responsive_control(
            'filter_cats_margin',
            array(
                'label' => esc_html__( 'Filter margin', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'   => [
                    'top'       => 0,
                    'left'      => 0,
                    'right'     => 30,
                    'bottom'    => 0,
                    'unit'      => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-filter a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_fonts_filter_cats',
                'selector' => '{{WRAPPER}} .wgl-filter a',
            )
        );


        $this->start_controls_tabs( 'filter_cats_color_tab' );

        $this->start_controls_tab(
            'custom_filter_cats_color_normal',
            array(
                'label' => esc_html__( 'Normal' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'filter_cats_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($main_font_color),
                'selectors' => array(
                    '{{WRAPPER}} .wgl-filter a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'filter_cats_background',
            array(
                'label' => esc_html__( 'Background', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-filter a' => 'background: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_filter_cats_color_hover',
            array(
                'label' => esc_html__( 'Active' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'filter_cats_color_hover',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($theme_color),
                'selectors' => array(
                    '{{WRAPPER}} .wgl-filter a.active' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-filter a:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'filter_cats_background_hover',
            array(
                'label' => esc_html__( 'Background', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wgl-filter a.active' => 'background: {{VALUE}};border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-filter a:hover' => 'background: {{VALUE}};border-bottom-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'filter_cats_border',
                'label'    => esc_html__( 'Border Type', 'thegov-core' ),
                'default' => '1px',
                'selector' => '{{WRAPPER}} .wgl-filter a',
            )
        );

        $this->add_control(
            'filter_cats_radius',
            array(
                'label' => esc_html__( 'Border Radius', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'   => [
                    'top'       => 0,
                    'left'      => 0,
                    'right'     => 0,
                    'bottom'    => 0,
                    'unit'      => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-filter a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'filter_cats_shadow',
                'selector' =>  '{{WRAPPER}} .wgl-filter a',
            )
        );

        $this->end_controls_section();



        /*-----------------------------------------------------------------------------------*/
        /*  Style Section(Headings Section)
        /*-----------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'headings_style_section',
            array(
                'label'     => esc_html__( 'Headings', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control('heading_tag',
            array(
                'label'         => esc_html__('Heading tag', 'thegov-core'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'h4',
                'options'       => [
                    'h1'    => 'H1',
                    'h2'    => 'H2',
                    'h3'    => 'H3',
                    'h4'    => 'H4',
                    'h5'    => 'H5',
                    'h6'    => 'H6',
                ],
            )
        );

        $this->add_responsive_control(
            'heading_margin',
            array(
                'label' => esc_html__( 'Heading margin', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'   => [
                    'top'       => 0,
                    'left'      => 0,
                    'right'     => 0,
                    'bottom'    => 14,
                    'unit'      => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .events-post_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );


        $this->start_controls_tabs( 'headings_color' );

        $this->start_controls_tab(
            'custom_headings_color_normal',
            array(
                'label' => esc_html__( 'Normal' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'custom_headings_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .events-post_title a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_headings_color_hover',
            array(
                'label' => esc_html__( 'Hover' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'custom_hover_headings_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($theme_color),
                'selectors' => array(
                    '{{WRAPPER}} .events-post .events-post-hero_wrapper .events-post_title a:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_fonts_events_headings',
                'selector' => '{{WRAPPER}} .events-post_title, {{WRAPPER}} .events-post_title > a',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meta_info_style_section',
            array(
                'label'     => esc_html__( 'Meta Info', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'custom_main_location_margin',
            array(
                'label' => esc_html__( 'Location margin', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'   => [
                    'top'       => 0,
                    'left'      => 0,
                    'right'     => 0,
                    'bottom'    => 0,
                    'unit'      => 'px',
                    'isLinked'  => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .events-post_location' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_control(
            'custom_main_location_color',
            array(
                'label' => esc_html__( 'Location Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .events-post_location' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_fonts_events_location',
                'selector' => '{{WRAPPER}} .events-post_location',
            )
        );

        $this->add_control(
            'hr_meta_location',
            array(
                'type' => Controls_Manager::DIVIDER,
            )
        );

        $this->add_responsive_control(
            'custom_main_meta_margin',
            array(
                'label' => esc_html__( 'Meta Info margin (date, author, comments)', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'   => [
                    'top'       => 6,
                    'left'      => 0,
                    'right'     => 0,
                    'bottom'    => -4,
                    'unit'      => 'px',
                    'isLinked'  => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .meta-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_control(
            'custom_main_meta_color',
            array(
                'label' => esc_html__( 'Meta Info Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#dcdcdc',
                'selectors' => array(
                    '{{WRAPPER}} .meta-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .meta-wrapper span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .meta-wrapper a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .meta-wrapper span + span:before' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_fonts_events_meta',
                'selector' => '{{WRAPPER}} .meta-wrapper',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_style_section',
            array(
                'label'     => esc_html__( 'Content', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'content_margin',
            array(
                'label' => esc_html__( 'Margin', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'   => [
                    'top'       => 16,
                    'left'      => 0,
                    'right'     => 0,
                    'bottom'    => 0,
                    'unit'      => 'px',
                    'isLinked'  => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .events-post_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );


        $this->add_control(
            'custom_content_color',
            array(
                'label' => esc_html__( 'Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => array(
                    '{{WRAPPER}} .events-post_text' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'custom_fonts_blog_content',
                'selector' => '{{WRAPPER}} .events-post_text',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'media_style_section',
            array(
                'label'     => esc_html__( 'Media', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'media_padding',
            array(
                'label' => esc_html__( 'Padding', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'   => [
                    'top'       => 50,
                    'left'      => 48,
                    'right'     => 48,
                    'bottom'    => 44,
                    'unit'      => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .events-post-hero-content_wrapper .events-post-hero_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .events-post-hero-content_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_control(
            'hr_media_background',
            array(
                'type' => Controls_Manager::DIVIDER,
            )
        );

        $this->start_controls_tabs( 'tabs_media_style' );

        $this->start_controls_tab(
            'tab_media_normal',
            [
                'label' => esc_html__( 'Normal', 'thegov-core' ),
            ]
        );

        $this->add_control(
            'custom_events_mask',
            array(
                'label'        => esc_html__('Custom Image Idle Overlay','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );


        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'custom_image_mask_color',
                'label' => esc_html__( 'Background', 'thegov-core' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'default'  => 'rgba( '.\Thegov_Theme_Helper::hexToRGB($header_font_color).',0.1)',
                'condition'     => [
                    'custom_events_mask'   => 'yes',
                ],
                'selector' => '{{WRAPPER}} .events-post_bg_media:before',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_media_hover',
            [
                'label' => esc_html__( 'Hover', 'thegov-core' ),
            ]
        );

        $this->add_control(
            'custom_media_headings_color',
            array(
                'label' => esc_html__( 'Headings Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($header_font_color),
                'selectors' => array(
                    '{{WRAPPER}} .events-post-hero_wrapper:hover .events-post_title a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'custom_media_date_color',
            array(
                'label' => esc_html__( 'Meta Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#bcbcbc',
                'selectors' => array(
                    '{{WRAPPER}} .events-post-hero_wrapper:hover .meta-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .events-post-hero_wrapper:hover .meta-wrapper span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .events-post-hero_wrapper:hover .meta-wrapper a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .events-post-hero_wrapper:hover .meta-wrapper span + span:before' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'custom_media_location_color',
            array(
                'label' => esc_html__( 'Location Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($main_font_color),
                'selectors' => array(
                    '{{WRAPPER}} .events-post-hero_wrapper:hover .events-post_location' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'custom_media_content_color',
            array(
                'label' => esc_html__( 'Content Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($main_font_color),
                'selectors' => array(
                    '{{WRAPPER}} .events-post-hero_wrapper:hover .events-post_text' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'custom_events_hover_mask',
            array(
                'label'        => esc_html__('Custom Image Hover Overlay','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );


        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'custom_image_hover_mask_color',
                'label' => esc_html__( 'Background', 'thegov-core' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'default'  => 'rgba(50,50,50,1)',
                'condition'     => [
                    'custom_events_hover_mask'   => 'yes',
                ],
                'selector' => '{{WRAPPER}} .events-post .events-post_bg_media:after',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'without_media_style_section',
            array(
                'label'     => esc_html__( 'Without Media', 'thegov-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );


        $this->add_responsive_control(
            'without_media_padding',
            array(
                'label' => esc_html__( 'Padding', 'thegov-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'   => [
                    'top'       => 50,
                    'left'      => 48,
                    'right'     => 48,
                    'bottom'    => 44,
                    'unit'      => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .format-standard.format-no_featured .events-post-hero_wrapper .events-post-hero-content_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );

        $this->add_control(
            'hr_headings_color',
            array(
                'type' => Controls_Manager::DIVIDER,
            )
        );


        $this->start_controls_tabs( 'headings_standard_color' );

        $this->start_controls_tab(
            'custom_standard_headings_color_normal',
            array(
                'label' => esc_html__( 'Normal' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'custom_standard_headings_color',
            array(
                'label' => esc_html__( 'Headings Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($header_font_color),
                'selectors' => array(
                    '{{WRAPPER}} .format-standard.format-no_featured .events-post_title a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_standard_headings_color_hover',
            array(
                'label' => esc_html__( 'Hover' , 'thegov-core' ),
            )
        );

        $this->add_control(
            'custom_standard_hover_headings_color',
            array(
                'label' => esc_html__( 'Title Hover Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($theme_color),
                'selectors' => array(
                    '{{WRAPPER}} .format-standard.format-no_featured .events-post_title a:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'hr_meta_color',
            array(
                'type' => Controls_Manager::DIVIDER,
            )
        );

        $this->start_controls_tabs( 'tabs_meta_standard_info' );

        $this->start_controls_tab(
            'tab_meta_standard_info_normal',
            [
                'label' => esc_html__( 'Normal', 'thegov-core' ),
            ]
        );

        $this->add_control(
            'custom_meta_standard_color',
            array(
                'label' => esc_html__( 'Meta Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#bcbcbc',
                'selectors' => array(
                    '{{WRAPPER}} .format-no_featured  .events-post-hero_content  .meta-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .format-no_featured  .events-post-hero_content .meta-wrapper a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .format-no_featured  .events-post-hero_content .meta-wrapper span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .format-no_featured  .events-post-hero_content .meta-wrapper span + span:before' => 'color: {{VALUE}};',
                ),
            )
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_meta_standard_hover',
            [
                'label' => esc_html__( 'Hover', 'thegov-core' ),
            ]
        );

        $this->add_control(
            'custom_meta_standard_color_hover',
            array(
                'label' => esc_html__( 'Meta Hover Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($theme_color),
                'selectors' => array(
                    '{{WRAPPER}} .format-no_featured .meta-wrapper a:hover' => 'color: {{VALUE}};',
                ),
            )
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_control(
            'hr_content_color',
            array(
                'type' => Controls_Manager::DIVIDER,
            )
        );

        $this->add_control(
            'custom_standard_content_color',
            array(
                'label' => esc_html__( 'Content Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($main_font_color),
                'selectors' => array(
                    '{{WRAPPER}} .format-standard.format-no_featured .events-post_text' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'hr_location_color',
            array(
                'type' => Controls_Manager::DIVIDER,
            )
        );
        $this->add_control(
            'custom_standard_location_color',
            array(
                'label' => esc_html__( 'Location Color', 'thegov-core' ),
                'type' => Controls_Manager::COLOR,
                'default' => esc_attr($main_font_color),
                'selectors' => array(
                    '{{WRAPPER}} .format-standard.format-no_featured .events-post_location' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'hr_bg_color',
            array(
                'type' => Controls_Manager::DIVIDER,
            )
        );


        $this->add_control(
            'custom_events_bg_item',
            array(
                'label'        => esc_html__('Custom Items Background','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );


        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'custom_bg_color',
                'label' => esc_html__( 'Background', 'thegov-core' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'default'  => 'rgba(247,247,247,1)',
                'condition'     => [
                    'custom_events_bg_item'   => 'yes',
                ],
                'selector' => '{{WRAPPER}} .format-standard.format-no_featured .events-post-hero_wrapper',
            )
        );

        $this->add_control(
            'custom_events_bg_item_hover',
            array(
                'label'        => esc_html__('Custom Items Hover Background','thegov-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'thegov-core' ),
                'label_off'    => esc_html__( 'Off', 'thegov-core' ),
                'return_value' => 'yes',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'custom_bg_color_hover',
                'label' => esc_html__( 'Hover Background', 'thegov-core' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'default'  => 'rgba(247,247,247,1)',
                'condition'     => [
                    'custom_events_bg_item_hover'   => 'yes',
                ],
                'selector' => '{{WRAPPER}} .format-standard.format-no_featured .events-post-hero_wrapper:hover:after',
            )
        );

        $this->end_controls_section();
    }


    protected function render() {
        $atts = $this->get_settings_for_display();

        $events = new WglEvents();
        echo $events->render($atts);
    }

}