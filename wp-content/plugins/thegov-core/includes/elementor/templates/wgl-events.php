<?php
namespace WglAddons\Templates;

use Elementor\Plugin;
use Elementor\Frontend;
use WglAddons\Includes\Wgl_Loop_Settings;
use WglAddons\Includes\Wgl_Elementor_Helper;
use WglAddons\Includes\Wgl_Carousel_Settings;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
* Wgl Elementor Events Render
*
*
* @class        WglEvents
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/

class WglEvents{

    private static $instance = null;
    public static function get_instance( ) {
        if ( null == self::$instance ) {
            self::$instance = new self( );
        }

        return self::$instance;
    }

    public function filter_where( $where = '' ) {
        return $where . ' AND '. $this->args;
    } 

    public function add_table( $join, $wp_query ){
        global $wpdb;
        $events_table = EM_EVENTS_TABLE;
        $join .= " JOIN {$events_table} on {$wpdb->posts}.ID = {$wpdb->prefix}em_events.post_id ";
        return $join;
    }

    public function cache_query($args = array()){
        
        $args['update_post_term_cache'] = false; // don't retrieve post terms
        $args['update_post_meta_cache'] = false; // don't retrieve post meta
        $k = http_build_query( $args );
        $custom_query = wp_cache_get( $k, 'thegov_theme' );
        if ( false ===  ($custom_query) ) {

            if(isset($args['where'])){
                $this->args = $args['where'];
                add_filter( 'posts_join', array( $this, 'add_table' ), 10, 2  );
                add_filter( 'posts_where', array( $this, 'filter_where' ) );                
            }
            
            $custom_query = new \WP_Query( $args );
            if ( ! is_wp_error( $custom_query ) && $custom_query->have_posts() ) {
                wp_cache_set( $k, $custom_query, 'thegov_theme' );
            }

            if(isset($args['where'])){
                remove_filter( 'posts_join', array( $this, 'add_table' ) );
                remove_filter( 'posts_where', array( $this, 'filter_where' ) );
            }

        }
        
        return $custom_query;       
    }

    public function getCategories($params, $query, $events_layout){
        $data_category = isset($params['tax_query']) ? $params['tax_query'] : array();
        $include = array();
        $exclude = array();
        if (!is_tax()) {
            if (!empty($data_category) && isset($data_category[0]) && $data_category[0]['operator'] === 'IN') {
                foreach ($data_category[0]['terms'] as $key => $value) {
                    $idObj = get_term_by('slug', $value, 'event-categories'); 
                    $id_list[] = $idObj->term_id;
                }
                $include = implode(",", $id_list);
            } elseif (!empty($data_category) && isset($data_category[0]) && $data_category[0]['operator'] === 'NOT IN') {
                foreach ($data_category[0]['terms'] as $key => $value) {
                    $idObj = get_term_by('slug', $value, 'event-categoriesy'); 
                    $id_list[] = $idObj->term_id;
                }
                $exclude = implode(",", $id_list);
            }    
        }

        $cats = get_terms(array(
                'taxonomy' => 'event-categories',
                'include' => $include,
                'exclude' => $exclude,
                'hide_empty' => true
            ));
        $out = '<a href="#" data-filter=".item" class="active">'.esc_html__('All','thegov-core').'</a>';
        foreach ($cats as $cat) {
            if($cat->count > 0){
                $out .= '<a href="'.get_term_link($cat->term_id, 'event-categories').'" data-filter=".'.$cat->slug.'">';
                $out .= $cat->name;
                $out .= '</a>';
            }   
        }
        return $out;
    }

    public function render( $atts ){
        extract($atts);

        list($query_args) = Wgl_Loop_Settings::buildQuery($atts);
        
        // Add Page to Query
        $query_args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $query_args['post_type'] = 'event';

        //Add Optimized SQL 
        if ( $events_navigation == 'none' ) {
            $query_args['no_found_rows'] = true;
        }

        $query = $this->cache_query($query_args);

        // Render Items events
        $wgl_def_atts = array(
            'query' => $query,
            // General
            'events_layout' => '',
            'events_title' => '',
            'events_subtitle' => '',
            // Content
            'events_columns' => '',
            'hide_media' => '',
            'hide_content' => '',
            'hide_events_title' => '',
            'hide_postmeta' => '',
            'meta_author' => '',
            'meta_location' => '',
            'meta_comments' => '',
            'meta_categories' => '',
            'meta_date' => '',
            'read_more_hide' => $read_more_hide,
            'read_more_text' => '',
            'content_letter_count' => '',
            'heading_tag' => '',
            'items_load'  => $items_load,
            'name_load_more' => $name_load_more
        );

        global $wgl_events_atts;
        $wgl_events_atts = array_merge($wgl_def_atts, array_intersect_key($atts, $wgl_def_atts));
        ob_start();

        get_template_part('templates/events/events', 'list');

        $events_items = ob_get_clean();

        // Render row class
        $row_class = '';

        wp_enqueue_script( 'imagesloaded' ); 
        if ($events_layout == 'masonry') {
            // Call Wordpress Isotope
            wp_enqueue_script( 'isotope', WGL_ELEMENTOR_ADDONS_URL . 'assets/js/isotope.pkgd.min.js' );
            $row_class .= 'events_masonry';
        } 

        // Allowed HTML render
        $allowed_html = array(
            'a' => array(
                'href' => true,
                'title' => true,
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array()
        ); 

        // Options for carousel
        if ($events_layout == 'carousel') {
            switch ($events_columns){
                case '6':  $item_grid = 2; break;
                case '3':  $item_grid = 4; break;
                case '4':  $item_grid = 3; break;
                case '12': $item_grid = 1; break;
                default:   $item_grid = 6; break;
            }

            $carousel_options_arr = array(
                'slide_to_show' => $item_grid,
                'autoplay' => $autoplay,
                'autoplay_speed' => $autoplay_speed,
                'use_pagination' => $use_pagination,
                'use_navigation' => $use_navigation,
                'pag_type' => $pag_type,
                'pag_offset' => $pag_offset,
                'custom_pag_color' => $custom_pag_color,
                'pag_color' => $pag_color,
                'custom_resp' => $custom_resp,
                'resp_medium' => $resp_medium,
                'resp_medium_slides' => $resp_medium_slides,
                'resp_tablets' => $resp_tablets,
                'resp_tablets_slides' => $resp_tablets_slides,
                'resp_mobile' => $resp_mobile,
                'resp_mobile_slides' => $resp_mobile_slides,
                'adaptive_height'   => true
            );

            if ((bool)$use_navigation) {
                $carousel_options_arr['use_prev_next'] = 'true';
            }

            wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.min.js', array(), false, false);

            $events_items = Wgl_Carousel_Settings::init($carousel_options_arr, $events_items, false);

            $row_class = 'events_carousel';
            if((bool) $show_filter){
                $row_class .= ' events_carousel_title-arrow';
            }
        }

        // Row class for grid and massonry
        if ( in_array($events_layout, array('grid', 'masonry')) ) {

            switch ( $events_columns ) {
                case '12': $row_class .= ' events_columns-1'; break;
                case '6':  $row_class .= ' events_columns-2'; break;
                case '4':  $row_class .= ' events_columns-3'; break;
                case '3':  $row_class .= ' events_columns-4'; break;
            } 

            if((bool) $show_filter){
                $row_class .= ' masonry';
            }else{
                $row_class .= ' '.$events_layout;
            }
        }
        $row_class .= " events-style-grid";

        $row_class .= (bool) $show_filter && $events_layout != 'carousel' ? ' isotope' : '';

        // Render wraper
        if ($query->have_posts()): ?>
            <section class="wgl_cpt_section">
                <div class="wgl-events">
                    <?php                 
                    if(!empty($events_title) || !empty($events_subtitle)){
                        echo '<div class="wgl_module_title item_title">';
                        if (!empty($events_title)) echo '<h3 class="thegov_module_title events_title">'.wp_kses( $events_title, $allowed_html ).'</h3>';
                        if (!empty($events_subtitle)) echo '<p class="events_subtitle">'.wp_kses( $events_subtitle, $allowed_html ).'</p>';
                        echo '</div>';           
                    }

                    if ( (bool) $show_filter) {         
                        echo '<div class="wgl-filter_wrapper">';
                            $filter_class = $events_layout != 'carousel' ? 'isotope-filter' : 'carousel-filter';
                            $filter_class .= ' filter-'.$filter_align;
                            echo '<div class="wgl-filter events__filter '.esc_attr($filter_class).'">';
                            echo $this->getCategories($query_args, $query, $events_layout);
                            echo '</div>'; 
                            if ($events_layout == 'carousel' && (bool) $use_navigation) {
                                echo '<div class="carousel_arrows"><span class="left_slick_arrow"><span></span></span><span class="right_slick_arrow"><span></span></span></div>';       
                            } 

                        echo '</div>'; 
                     
                    } 
                    echo '<div class="wgl-events_wrapper">';
                        echo '<div class="container-grid row '. esc_attr($row_class) .'">';
                            echo \Thegov_Theme_Helper::render_html($events_items);
                        echo '</div>';
                    echo '</div>';
                    ?>
                </div>
        <?php

        if ( $events_navigation == 'pagination' ) {
            echo \Thegov_Theme_Helper::pagination('10', $query, $events_navigation_align);
        }

        if ( $events_navigation == 'load_more' ) {
            $wgl_events_atts['post_count'] = $query->post_count;
            $wgl_events_atts['query_args'] = $query_args;
            $wgl_events_atts['atts'] = $atts;
            $class  = 'events_load_more';
            echo \Thegov_Theme_Helper::load_more($wgl_events_atts, $name_load_more, $class);
        }
            echo '</section>';
        endif;

        // Clear global var
        unset($wgl_events_atts);    
    }

}