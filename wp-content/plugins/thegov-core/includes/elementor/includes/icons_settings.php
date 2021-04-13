<?php

namespace WglAddons\Includes;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
* Wgl Elementor Icons Settins
*
*
* @class        Wgl_Icons
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/

if (!class_exists('Wgl_Icons')) {
    class Wgl_Icons{

        private static $instance = null;
        public static function get_instance( ) {
            if ( null == self::$instance ) {
                self::$instance = new self( );
            }

            return self::$instance;
        }

        public function build($self, $atts, $pref){
            $icon_builder = new Wgl_Icon_Builder();
            return $icon_builder->build( $self, $atts, $pref );
        }

        public static function init($self, $array = array()){
            // var_dump($array);

           // self::$arr = $array;

            
            if( !$self )
                return;

            $array['label'] = isset($array['label']) ? $array['label'] : '';
            
            if ((bool)$array['section']) {
                $self->start_controls_section(
                    $array['prefix'].'add_icon_image_section',
                    array(
                        'label'      => sprintf(esc_html__('%sIcon/Image Settings', 'thegov-core'), esc_html($array['label'])),
                    )
                );
            }

            $self->add_control($array['prefix'].'icon_type',
                array(
                    'label'             => esc_html__('Add Icon/Image', 'thegov-core'),
                    'type'              => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options'           => [
                        ''              => [
                            'title' => esc_html__('None', 'thegov-core'), 
                            'icon' => 'fa fa-ban',
                        ],
                        'font'          => [
                            'title' => esc_html__('Icon', 'thegov-core'),
                            'icon' => 'fa fa-smile-o',
                        ],
                        'image'         => [
                            'title' => esc_html__('Image', 'thegov-core'),
                            'icon' => 'fa fa-picture-o',
                        ]
                    ],
                    'default'           => '',
                )
            );

            $self->add_control($array['prefix'].'icon_pack',
                array(
                    'label'             => esc_html__('Icon Pack', 'thegov-core'),
                    'type'              => Controls_Manager::SELECT,
                    'options'           => [
                        'fontawesome'               => esc_html__('Fontawesome', 'thegov-core'), 
                        'flaticon'          => esc_html__('Flaticon', 'thegov-core'),
                    ],
                    'default'           => 'fontawesome',
                    'condition'     => [
                        $array['prefix'].'icon_type'  => 'font',
                    ]
                )
            );    

            $self->add_control($array['prefix'].'icon_flaticon',
                array(
                    'label'       => esc_html__( 'Icon', 'thegov-core' ),
                    'type'        => 'wgl-icon',
                    'label_block' => true,
                    'condition'     => [
                        $array['prefix'].'icon_pack'  => 'flaticon',
                        $array['prefix'].'icon_type'  => 'font',
                    ],
                    'description' => esc_html__( 'Select icon from Flaticon library.', 'thegov-core' ),
                )
            );

            $self->add_control($array['prefix'].'icon_fontawesome',
                array(
                    'label'       => esc_html__( 'Icon', 'thegov-core' ),
                    'type'        => Controls_Manager::ICON,
                    'label_block' => true,
                    'condition'     => [
                        $array['prefix'].'icon_pack'  => 'fontawesome',
                        $array['prefix'].'icon_type'  => 'font',
                    ],
                    'description' => esc_html__( 'Select icon from Fontawesome library.', 'thegov-core' ),
                )
            );

            $self->add_control($array['prefix'].'icon_render_class',
                array(
                    'label' => esc_html__( 'Icon Class', 'thegov-core' ),
                    'type' => Controls_Manager::HIDDEN,
                    'default' => 'wgl-icon-box',
                    'prefix_class' => 'elementor-widget-icon-box ',
                    'condition'     => [
                        $array['prefix'].'icon_type'  => 'font',
                    ]
                )
            );

            $self->add_control($array['prefix'].'thumbnail',
                array(
                    'label'       => esc_html__( 'Image', 'thegov-core' ),
                    'type'        => Controls_Manager::MEDIA,
                    'label_block' => true,
                    'condition'     => [
                        $array['prefix'].'icon_type'   => 'image',
                    ],
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                )
            ); 

            $self->add_control($array['prefix'].'image_render_class',
                array(
                    'label' => esc_html__( 'Image Class', 'thegov-core' ),
                    'type' => Controls_Manager::HIDDEN,
                    'default' => 'wgl-image-box',
                    'prefix_class' => 'elementor-widget-image-box ',
                    'condition'     => [
                        $array['prefix'].'icon_type'  => 'image',
                    ]
                )
            );

            $self->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => ($array['prefix'].'thumbnail'), // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                    'default' => 'full',
                    'separator' => 'none',
                    'condition'     => [
                        $array['prefix'].'icon_type'   => 'image',
                    ],

                ]
            );

            if(isset($array['output']) && !empty($array['output'])){
                foreach ($array['output'] as $key => $value) {
                    $self->add_control(
                        $key,
                        $value
                    );
                }
            }
            if ((bool)$array['section']) {
	            $self->end_controls_section();           
            }
        }
        
    }
    new Wgl_Icons();
}

/**
* Wgl Icon Build
*
*
* @class        Wgl_Icon_Builder
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/
if(!class_exists('Wgl_Icon_Builder')){
    class Wgl_Icon_Builder{

        private static $instance = null;
        public static function get_instance( ) {
            if ( null == self::$instance ) {
                self::$instance = new self( );
            }

            return self::$instance;
        }


        public function build($self, $atts, $pref ){

            $prefix = '';
            if (isset($pref) && !empty($pref)) {
                $prefix = $pref;
            }

            $icon_type = $atts[$prefix.'icon_type'];
            $icon_pack = $atts[$prefix.'icon_pack'];
            $icon_flaticon = $atts[$prefix.'icon_flaticon'];
            $icon_fontawesome = $atts[$prefix.'icon_fontawesome'];
            $thumbnail = $atts[$prefix.'thumbnail'];

            $self->add_render_attribute( $prefix.'icon', 'class', [ 'wgl-icon', 'elementor-icon' ] );
            if(isset($atts['hover_animation_icon'])){
                $self->add_render_attribute( $prefix.'icon', 'class', 'elementor-animation-' . $atts['hover_animation_icon'] );
            }
            $wrapper_class = isset($atts['wrapper_class']) ? $atts['wrapper_class'] : 'wgl-widget_wrapper';

            if($icon_type !== ''){
                if($icon_type === 'image'){
                    $wrapper_class .= ' elementor-image-box-img';
                }else{
                    $wrapper_class .= ' elementor-icon-box-icon';
                }
            } 

            $container_class = isset($atts['container_class']) ? $atts['container_class'] : 'wgl-widget_container';

            $self->add_render_attribute( $prefix.'wrapper', 'class', [ $wrapper_class ] );

            $self->add_render_attribute( $prefix.'container', 'class', [ $container_class ] );

            $output = '';
            $icon_tag = 'span';
            
            if (isset($atts['link_t']['url']) && !empty($atts['link_t']['url'])) {
                $icon_tag = 'a';
                $self->add_link_attributes($prefix.'link_t', $atts['link_t']);
            }

            $icon_attributes = $self->get_render_attribute_string( $prefix.'icon' );            
            $link_attributes = $self->get_render_attribute_string( $prefix.'link_t' );

            $wrapper = $self->get_render_attribute_string( $prefix.'wrapper' );
            $container = $self->get_render_attribute_string( $prefix.'container' );

            if($icon_type == 'font' && (!empty($icon_fontawesome) || !empty($icon_flaticon)) ||  $icon_type == 'image' && !empty($thumbnail)){

                $output .= '<div '.implode( ' ', [ $wrapper ] ).'>'; 
                    $output .= '<div '.implode( ' ', [ $container ] ).'>'; 
                    
                        if ($icon_type == 'font' && (!empty($icon_fontawesome) || !empty($icon_flaticon))) {
                            switch ($icon_pack) {
                                case 'fontawesome':
                                    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
                                    $icon_font = $icon_fontawesome;
                                    break;
                                case 'flaticon':
                                    wp_enqueue_style('flaticon', get_template_directory_uri() . '/fonts/flaticon/flaticon.css');
                                    $icon_font = $icon_flaticon;
                                    break;
                            }

                            $output .= '<'; 
                                $output .= implode( ' ', [ $icon_tag, $icon_attributes, $link_attributes ] );
                            $output .= '>'; 
                                $output .= '<i class="icon '.esc_attr($icon_font).'"></i>';
                            
                            $output .= '</'.$icon_tag.'>';     
                        }
                        if ($icon_type == 'image' && !empty($thumbnail)) {
                            
                            // var_dump($thumbnail);
                            if ( ! empty( $thumbnail['url'] ) ) {
                                $self->add_render_attribute( 'thumbnail', 'src', $thumbnail['url'] );
                                $self->add_render_attribute( 'thumbnail', 'alt', Control_Media::get_image_alt( $thumbnail ) );
                                $self->add_render_attribute( 'thumbnail', 'title', Control_Media::get_image_title( $thumbnail ) );

                                if ( isset($atts['hover_animation_image']) ) {
                                    $atts['hover_animation'] = $atts['hover_animation_image'];
                                }
                                
                                $output .= '<figure class="wgl-image-box_img">';
                                
                                $output .= '<'; 
                                    $output .= implode( ' ', [ $icon_tag,  $link_attributes ] );
                                $output .= '>'; 
                                    $output .= Group_Control_Image_Size::get_attachment_image_html( $atts, 'thumbnail', $prefix.'thumbnail' );
                                
                                $output .= '</'.$icon_tag.'>';     

                                $output .= '</figure>';
                            }

                        }

                    $output .= '</div>';
                $output .= '</div>';
            }
            return $output;

        }

        public function template_build(){

        }       
    }
}
?>