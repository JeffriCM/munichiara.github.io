<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }


if (!class_exists('Thegov_header_side_area')) {
	class Thegov_header_side_area extends Thegov_get_header{

		public function __construct(){
			$this->header_vars();

	   		$pos = Thegov_Theme_Helper::options_compare('side_panel_position', 'mb_customize_side_panel', 'custom');

	   		$content_type = Thegov_Theme_Helper::options_compare('side_panel_content_type','mb_customize_side_panel','custom');
	   		$class = !empty($pos) ? ' side-panel_position_'.$pos : ' side-panel_position_right';

			echo '<div class="side-panel_overlay"></div>';

    		//Get options
			$side_panel_spacing = Thegov_Theme_Helper::options_compare('side_panel_spacing','mb_customize_side_panel','custom');

	        $style  = '';
	        $style .= !empty($side_panel_spacing['padding-top']) ? ' padding-top:'.(int)$side_panel_spacing['padding-top'].'px;' : '' ;
	        $style .= !empty($side_panel_spacing['padding-bottom']) ? ' padding-bottom:'.(int)$side_panel_spacing['padding-bottom'].'px;' : '' ;
	        $style .= !empty($side_panel_spacing['padding-left']) ? ' padding-left:'.(int)$side_panel_spacing['padding-left'].'px;' : '' ;
	        $style .= !empty($side_panel_spacing['padding-right']) ? ' padding-right:'.(int)$side_panel_spacing['padding-right'].'px;' : '' ;
	        $style  = !empty($style) ? ' style="'.$style.'"' : '';

			echo '<section id="side-panel" class="side-panel_widgets'.esc_attr($class).'"'.$this->side_panel_style().'>';
				echo '<a href="#" class="side-panel_close"><span class="side-panel_close_icon"></span></a>';
				echo '<div class="side-panel_sidebar"'.$style.'>';

					switch ($content_type) {
						case 'widgets':
							dynamic_sidebar( 'side_panel' );
							break;
						case 'pages':
							$this->side_panel_get_pages();
							break;
						default:
							dynamic_sidebar( 'side_panel' );
							break;
					}

				echo '</div>';
			echo '</section>';
		}

		public function side_panel_style(){
			$name_preset = $this->name_preset;
	   		$def_preset = $this->def_preset;

			$bg = Thegov_Theme_Helper::get_option('side_panel_bg');
			$bg = !empty($bg['rgba']) ? $bg['rgba'] : '';

			$color = Thegov_Theme_Helper::get_option('side_panel_text_color');
			$color = !empty($color['rgba']) ? $color['rgba'] : '';

			$width = Thegov_Theme_Helper::get_option('side_panel_width');
			$width = !empty($width['width']) ? $width['width'] : '';

			$align = Thegov_Theme_Helper::options_compare('side_panel_text_alignment', 'mb_customize_side_panel', 'custom');
			$style = '';

			if (class_exists( 'RWMB_Loader' ) && $this->id !== 0) {
				$side_panel_switch = rwmb_meta('mb_customize_side_panel');
				if($side_panel_switch === 'custom'){
					$bg = rwmb_meta("mb_side_panel_bg");
					$color = rwmb_meta("mb_side_panel_text_color");
					$width = rwmb_meta("mb_side_panel_width");
				}
			}

			if (!empty($bg)) {
	            $style .= !empty($bg) ? 'background-color: '.esc_attr($bg).';' : '';
	        }

			if (!empty($color)) {
	            $style .= !empty($color) ? 'color: '.esc_attr($color).';' : '';
	        }

	        if (!empty($width)) {
	            $style .= 'width: '.esc_attr((int) $width ).'px;';
	        }

	        $style .= !empty($align) ? 'text-align: '.esc_attr($align).';' : 'text-align:center;';

			$style = !empty($style) ? ' style="'.$style.'"' : '';
			return $style;
		}

		public function side_panel_get_pages(){

			$page_select = Thegov_Theme_Helper::options_compare('side_panel_page_select','mb_customize_side_panel','custom');

			if (!empty($page_select)) {
				$page_select = intval($page_select);
				$page_select = thegov_dynamic_styles()->multi_language_support($page_select, 'side-panel');

                if ( did_action( 'elementor/loaded' ) ) {
                    echo \Elementor\Plugin::$instance->frontend->get_builder_content( $page_select );
                }
			}

		}
	}

    new Thegov_header_side_area();
}
