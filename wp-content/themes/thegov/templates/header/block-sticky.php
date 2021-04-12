<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }


if (!class_exists('Thegov_header_sticky')) {
	class Thegov_header_sticky extends Thegov_get_header{

		public function __construct(){
			$this->header_vars();  
			$this->html_render = 'sticky';

	   		if (Thegov_Theme_Helper::options_compare('header_sticky','mb_customize_header_layout','custom') == '1') {
	   			$header_sticky_style = Thegov_Theme_Helper::get_option('header_sticky_style');
	   			$header_sticky_shadow = Thegov_Theme_Helper::get_option('header_sticky_shadow');

	   			echo "<div class='wgl-sticky-header wgl-sticky-element".($header_sticky_shadow == '1' ? ' header_sticky_shadow' : '')."'".(!empty($header_sticky_style) ? ' data-style="'.esc_attr($header_sticky_style).'"' : '').">";

	   				echo "<div class='container-wrapper'>";
	   				
	   					$this->build_header_layout('sticky');
	   				echo "</div>";

	   			echo "</div>";
	   		}
		}
	}

    new Thegov_header_sticky();
}
