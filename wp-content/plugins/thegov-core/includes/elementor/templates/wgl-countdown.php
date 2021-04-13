<?php
namespace WglAddons\Templates;

use Elementor\Plugin;
use Elementor\Frontend;
use WglAddons\Includes\Wgl_Loop_Settings;
use WglAddons\Includes\Wgl_Elementor_Helper;
use WglAddons\Includes\Wgl_Carousel_Settings;
use WglAddons\Includes\Wgl_Icons;
use WglAddons\Templates\WglButton;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
* Wgl Elementor Portfolio Render
*
*
* @class        WglPricingTable
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/

class WglCountDown{

    private static $instance = null;
    public static function get_instance( ) {
        if ( null == self::$instance ) {
            self::$instance = new self( );
        }

        return self::$instance;
    }

    public function render( $self, $atts ){

        extract($atts);

        wp_enqueue_script('coundown', get_template_directory_uri() . '/js/jquery.countdown.min.js', array(), false, false);

        $countdown_class = '';

        // Module unique id
        $countdown_id = uniqid( "countdown_" );
        $countdown_attr = ' id='.$countdown_id;


        $countdown_class .= ' cd_'.$size;
        $countdown_class .= ' align_left';
        $countdown_class .= (bool) $show_separating ? ' show_separating': '';

        $f = !(bool)$hide_day ? 'd' : '';
        $f .= !(bool)$hide_hours ? 'H' : '';
        $f .= !(bool)$hide_minutes ? 'M' : '';
        $f .= !(bool)$hide_seconds ? 'S' : '';

        // Countdown data attribute http://keith-wood.name/countdown.html
        $data_array = array();

        $data_array['format'] = !empty($f) ? esc_attr($f) : '';

        $data_array['year'] =  esc_attr($countdown_year);
        $data_array['month'] =  esc_attr($countdown_month);
        $data_array['day'] =  esc_attr($countdown_day);
        $data_array['hours'] =  esc_attr($countdown_hours);
        $data_array['minutes'] =  esc_attr($countdown_min);

        $data_array['labels'][]  =  esc_attr( esc_html__( 'Years', 'thegov-core' ) );
        $data_array['labels'][]  =  esc_attr( esc_html__( 'Months', 'thegov-core' ) );
        $data_array['labels'][]  =  esc_attr( esc_html__( 'Weeks', 'thegov-core' ) );
        $data_array['labels'][]  =  esc_attr( esc_html__( 'Days', 'thegov-core' ) );
        $data_array['labels'][]  =  esc_attr( esc_html__( 'Hours', 'thegov-core' ) );
        $data_array['labels'][]  =  esc_attr( esc_html__( 'Minutes', 'thegov-core' ) );
        $data_array['labels'][]  =  esc_attr( esc_html__( 'Seconds', 'thegov-core' ) );
        $data_array['labels1'][] =  esc_attr( esc_html__( 'Year', 'thegov-core' ) );
        $data_array['labels1'][] =  esc_attr( esc_html__( 'Month', 'thegov-core' ) );
        $data_array['labels1'][] =  esc_attr( esc_html__( 'Week', 'thegov-core' ) );
        $data_array['labels1'][] =  esc_attr( esc_html__( 'Day', 'thegov-core' ) );
        $data_array['labels1'][] =  esc_attr( esc_html__( 'Hour', 'thegov-core' ) );
        $data_array['labels1'][] =  esc_attr( esc_html__( 'Minute', 'thegov-core' ) );
        $data_array['labels1'][] =  esc_attr( esc_html__( 'Second', 'thegov-core' ) );

        $data_attribute = json_encode($data_array, true);

        $output = '<div'.$countdown_attr.' class="wgl-countdown'.esc_attr($countdown_class).'" data-atts="'.esc_js($data_attribute).'">';
        $output .= '</div>';

        echo \Thegov_Theme_Helper::render_html($output);

    }

}