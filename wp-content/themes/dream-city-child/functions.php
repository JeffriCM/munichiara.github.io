<?php
/**
 * @package 	WordPress
 * @subpackage 	Dream City Child
 * @version		1.0.0
 * 
 * Child Theme Functions File
 * Created by CMSMasters
 * 
 */


function dream_city_child_enqueue_styles() {
    wp_enqueue_style('dream-city-child-style', get_stylesheet_uri(), array(), '1.0.0', 'screen, print');
}

add_action('wp_enqueue_scripts', 'dream_city_child_enqueue_styles', 11);
?>