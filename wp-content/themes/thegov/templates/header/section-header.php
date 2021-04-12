<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

use WglAddons\Templates\WglButton;


if (!class_exists('Thegov_get_header')) {
	class Thegov_get_header{

		protected $html_render = 'bottom';
		protected $id;
		protected $def_preset;
		protected $name_preset;
		protected $side_area_enabled;

		private static $instance = null;
		public static function get_instance( ) {
			if ( null == self::$instance ) {
				self::$instance = new self( );
			}

			return self::$instance;
		}

	    public function __construct() {

	    	$this->init();
	    }

	    public function header_vars(){
	    	$this->id = !is_archive() ? get_queried_object_id() : 0;
	    	// Redux options header
	    	$this->name_preset = Thegov_Theme_Helper::get_option('header_def_js_preset');
	    	$get_def_name = get_option( 'thegov_set_preset' );
	    	if( !$this->in_array_r($this->name_preset, get_option( 'thegov_set_preset' ))){
	    		$this->name_preset = 'default';
	    	}
	    	else{
		    	if(isset($get_def_name['default']) && $this->name_preset ){
		    		if(array_key_exists($this->name_preset, $get_def_name['default']) && !array_key_exists($this->name_preset, $get_def_name)){
		    			$this->def_preset = true;
		    		}else{
		    			$this->def_preset = false;
		    		}
		    	}
		    	else{
		    		$this->def_preset = false;
		    	}
	    	}

	    	// RWMB opions header
	    	if (class_exists( 'RWMB_Loader' ) && $this->id !== 0) {
	    		$customize_header = rwmb_meta('mb_customize_header');
	    		if (!empty($customize_header) && $customize_header != 'default') {
	    			if( !$this->in_array_r(rwmb_meta('mb_customize_header'), get_option( 'thegov_set_preset' ))){
	    			}else{
	    				$get_def_name = get_option( 'thegov_set_preset' );
	    				$this->name_preset = rwmb_meta('mb_customize_header');
	    				if(isset($get_def_name['default']) && $this->name_preset ){
		    				if(array_key_exists($this->name_preset, $get_def_name['default']) && !array_key_exists($this->name_preset, $get_def_name)){
		    					$this->def_preset = true;
		    				}else{
	    						$this->def_preset = false;
	    					}
	    				}
	    				else{
	    					$this->def_preset = false;
	    				}
	    			}
	    		}
	    	}

	    }

		public function init(){
			// Don't render header if in metabox set to hide it.
			if (class_exists( 'RWMB_Loader' )) {
	            if (rwmb_meta('mb_customize_header_layout') == 'hide') return;
	        }

	        //hide if 404 page
	        $page_not_found = Thegov_Theme_Helper::get_option('404_show_header');
			if(is_404() && !(bool) $page_not_found) return;

			$this->header_vars();
	        /**
			* Generate html header rendered
			*
			*
			* @since 1.0
			* @access public
			*/

	        $this->header_render_html();
	   	}

	    /**
		* Generate header class
		*
		*
		* @since 1.0
		* @access public
		*/
	   	public function header_class(){
	   		$header_shadow = Thegov_Theme_Helper::get_option( 'header_shadow',$this->name_preset, $this->def_preset );
	   		$header_on_bg = Thegov_Theme_Helper::get_option( 'header_on_bg',$this->name_preset, $this->def_preset );
			$header_on_bg = 'posts' === get_option( 'show_on_front' ) && (is_home() || is_front_page()) ? false : $header_on_bg;
	        // Build Header Class
	        $header_class = '';
	        if ($header_on_bg == 1) {
	            $header_class .= ' header_overlap';
	        }
	        if ($header_shadow == '1') {
	            $header_class .= ' header_shadow';
	        }
	        return $header_class;

	   	}

	    /**
		* Generate header editor
		*
		*
		* @since 1.0
		* @access public
		*/

		public function header_bar_editor($location = null,$position = null){
			if(!$position)
				return;

			/*
			 * Define Theme options and field configurations.
			*/

			${'header_'.$position.'_editor'} = Thegov_Theme_Helper::get_option($location.'_header_bar_'.$position.'_editor', $this->name_preset, $this->def_preset);
	        $html_render = ${'header_'.$position.'_editor'};
	    	// Header Bar HTML Editor render
	        $html = "";
	        if (!empty($html_render)) {
	            $html .= "<div class='".esc_attr($location)."_header ".esc_attr($position)."_editor header_render_editor header_render'>";
	                $html .= "<div class='wrapper'>";
	                		$html .= do_shortcode( $html_render );
	                $html .= "</div>";
	            $html .= "</div>";
	        }

	        return $html;
		}

		/**
		* Generate header delimiter
		*
		*
		* @since 1.0
		* @access public
		*/

		public function header_bar_delimiter($k = null){
			if(!$k)
				return;

			$header_sticky_builder = Thegov_Theme_Helper::get_option('sticky_header');

	   		if(!empty($header_sticky_builder) && $this->html_render == 'sticky'){

	   			echo '<div class="delimiter" style="margin-left: 48px; margin-right: 48px; width: 1px; background-color: rgba(229,229,229,1); height: 50px;"></div>';
	   		}else{
				/*
				 * Define Theme options and field configurations.
				*/

				$get_number = (int) filter_var($k, FILTER_SANITIZE_NUMBER_INT);
				$height = Thegov_Theme_Helper::get_option( 'bottom_header_delimiter'.$get_number.'_height', $this->name_preset, $this->def_preset );
				$width = Thegov_Theme_Helper::get_option( 'bottom_header_delimiter'.$get_number.'_width', $this->name_preset, $this->def_preset );

				$bg_color = Thegov_Theme_Helper::get_option( 'bottom_header_delimiter'.$get_number.'_bg', $this->name_preset, $this->def_preset );

				$margin = Thegov_Theme_Helper::get_option( 'bottom_header_delimiter'.$get_number.'_margin', $this->name_preset, $this->def_preset );

		        $margin_left = !empty($margin['margin-left']) ? (int)$margin['margin-left'] : '';
		        $margin_right = !empty($margin['margin-right']) ? (int)$margin['margin-right'] : '';

		       	$custom_sticky = '';
		        if ( $this->html_render === 'sticky' ) {
		        	$custom_sticky = Thegov_Theme_Helper::get_option('bottom_header_delimiter'.$get_number.'_sticky_custom', $this->name_preset, $this->def_preset);
		        	if( !empty($custom_sticky) ){
		        		$bg_color = Thegov_Theme_Helper::get_option('bottom_header_delimiter'.$get_number.'_sticky_color', $this->name_preset, $this->def_preset);
		        		$height  = Thegov_Theme_Helper::get_option('bottom_header_delimiter'.$get_number.'_sticky_height', $this->name_preset, $this->def_preset);
		        	}
		        }

		    	// Header Bar Delimiter render
		        $style = "";
		        if (is_array($height)) {
		            $style .= 'height: '.esc_attr((int) $height['height'] ).'px;';
		        }

		        if (is_array($width)) {
		            $style .= 'width: '.esc_attr((int) $width['width'] ).'px;';
		        }

		       	if (!empty($bg_color['rgba'])) {
		            $style .= 'background-color: '.esc_attr($bg_color['rgba']).';';
		        }

		        if(!empty($margin_left)){
		        	$style .= 'margin-left:'.esc_attr((int) $margin_left).'px;';
		        }

		        if(!empty($margin_right)){
		        	$style .= 'margin-right:'.esc_attr((int) $margin_right).'px;';
		        }

		        echo '<div class="delimiter"'.(!empty($style) ? ' style="'.$style.'"' : '').'></div>';
		    }
		}

		/**
		* Generate header button
		*
		*
		* @since 1.0
		* @access public
		*/

		public function header_bar_button($k = null){
			if(!$k)
				return;

			/*
			 * Define Theme options and field configurations.
			*/

			$get_number = (int) filter_var($k, FILTER_SANITIZE_NUMBER_INT);
			$button_text = Thegov_Theme_Helper::get_option('bottom_header_button'.$get_number.'_title', $this->name_preset, $this->def_preset);

			$link = Thegov_Theme_Helper::get_option('bottom_header_button'.$get_number.'_link', $this->name_preset, $this->def_preset);

			$target = Thegov_Theme_Helper::get_option('bottom_header_button'.$get_number.'_target', $this->name_preset, $this->def_preset);

			$size = Thegov_Theme_Helper::get_option('bottom_header_button'.$get_number.'_size', $this->name_preset, $this->def_preset);

			$options_btn = $this->html_render === 'sticky' ? '_sticky' : '';

			$customize = Thegov_Theme_Helper::get_option('bottom_header_button'.$get_number.'_custom'.$options_btn, $this->name_preset, $this->def_preset);

			$customize = empty($customize) ? 'def' : 'color';

			$bg_color = Thegov_Theme_Helper::get_option('bottom_header_button'.$get_number.'_bg'.$options_btn, $this->name_preset, $this->def_preset);

			$bg_color = isset($bg_color['rgba']) ? $bg_color['rgba'] : '';

			$text_color = Thegov_Theme_Helper::get_option('bottom_header_button'.$get_number.'_color_txt'.$options_btn, $this->name_preset, $this->def_preset);

			$text_color = isset($text_color['rgba']) ? $text_color['rgba'] : '';

			$border_color = Thegov_Theme_Helper::get_option('bottom_header_button'.$get_number.'_border'.$options_btn, $this->name_preset, $this->def_preset);
			$border_color = isset($border_color['rgba']) ? $border_color['rgba'] : '';

			$bg_color_hover = Thegov_Theme_Helper::get_option('bottom_header_button'.$get_number.'_hover_bg'.$options_btn, $this->name_preset, $this->def_preset);
			$bg_color_hover = isset($bg_color_hover['rgba']) ? $bg_color_hover['rgba'] : '';

			$text_color_hover = Thegov_Theme_Helper::get_option('bottom_header_button'.$get_number.'_hover_color_txt'.$options_btn, $this->name_preset, $this->def_preset);
			$text_color_hover = isset($text_color_hover['rgba']) ? $text_color_hover['rgba'] : '';

			$border_color_hover = Thegov_Theme_Helper::get_option('bottom_header_button'.$get_number.'_hover_border'.$options_btn, $this->name_preset, $this->def_preset);
			$border_color_hover = isset($border_color_hover['rgba']) ? $border_color_hover['rgba'] : '';

			$border_radius = Thegov_Theme_Helper::get_option('bottom_header_button'.$get_number.'_radius', $this->name_preset, $this->def_preset);
			switch ($size) {
				case 's':
					$size = 'sm';
					break;
				case 'm':
					$size = 'md';
					break;
				case 'l':
					$size = 'lg';
					break;
				case 'xl':
					$size = 'xl';
					break;
				default:
					$size = 'md';
					break;
			}

			$button_css_id =  uniqid( "thegov_button_" );

			$settings = array(
		        'text' => $button_text,
		        'link' => array(
		        	'url' => $link,
		        	'is_external' => $target,
		        	'nofollow' => '',
		        ),
		        'size' => $size,
		        'border_radius' => $border_radius,
		        'button_css_id' => $button_css_id,
    		);


	        // Start Custom CSS
	        $styles = '';
	        ob_start();

				if ($customize != 'def') {
					if ($customize == 'color') {
						echo "#$button_css_id {
								  color: ".(!empty($text_color) ? esc_attr($text_color) : 'transparent').";
							  }";
						echo "#$button_css_id:hover {
								  color: ".(!empty($text_color_hover) ? esc_attr($text_color_hover) : 'transparent').";
							  }";
						$border_color = !empty($border_color) ? esc_attr($border_color) : 'transparent';
						echo "#$button_css_id {
								  border-color: $border_color;
								  background-color: $bg_color;
							  }";
						echo "#$button_css_id:hover {
								  border-color: ".(!empty($border_color_hover) ? esc_attr($border_color_hover) : 'transparent').";
								  background-color: $bg_color_hover;
							  }";
					}
				}

	        $styles .= ob_get_clean();

	        // Register css
	        if (!empty($styles)) {
	        	Thegov_Theme_Helper::enqueue_css($styles);
	        }

    		unset($this->render_attributes);

    		echo '<div class="header_button">';
    			echo '<div class="wrapper">';

		            $this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

		            if ( ! empty( $settings['link']['url'] ) ) {
		                $this->add_render_attribute( 'button', 'href', $settings['link']['url'] );
		                $this->add_render_attribute( 'button', 'class', 'elementor-button-link' );

		                if ( $settings['link']['is_external'] ) {
		                    $this->add_render_attribute( 'button', 'target', '_blank' );
		                }

		                if ( $settings['link']['nofollow'] ) {
		                    $this->add_render_attribute( 'button', 'rel', 'nofollow' );
		                }
		            }

		            $this->add_render_attribute( 'button', 'class', 'wgl-button elementor-button' );
		            $this->add_render_attribute( 'button', 'role', 'button' );

		            $this->add_render_attribute( 'button', 'id', $button_css_id );


		            if ( ! empty( $settings['size'] ) ) {
		                $this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
		            }

		            if ( isset($settings['hover_animation']) ) {
		                $this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		            }

		         	if(isset($settings['border_radius'])){
		            	$this->add_render_attribute( 'button', 'style',  'border-radius: '.( (int) esc_attr($settings['border_radius']) !== 0  ? (int) esc_attr($settings['border_radius']).'px' : '0px').';' );
		            }

		            ?>
		            <div <?php echo Thegov_Theme_Helper::render_html($this->get_render_attribute_string( 'wrapper' )); ?>>
		                <a <?php echo Thegov_Theme_Helper::render_html($this->get_render_attribute_string( 'button' )); ?>>
		                    <?php $this->render_text($settings); ?>
		                </a>
		            </div>
		            <?php

				echo '</div>';
			echo '</div>';
		}

        public function render_text($settings) {
            $settings_icon_align = isset($settings['icon_align']) ? $settings['icon_align'] : '';

            $this->add_render_attribute( [
                'content-wrapper' => [
                    'class' => [
                        'elementor-button-content-wrapper',
                        'elementor-align-icon-' . $settings_icon_align,
                    ]
                ],
                'wrapper' => [
                    'class' => 'elementor-button-icon',
                ],
                'text' => [
                    'class' => 'elementor-button-text',
                ],
            ] );

            ?>
            <span <?php echo Thegov_Theme_Helper::render_html($this->get_render_attribute_string( 'content-wrapper' )); ?>>
                <span <?php echo Thegov_Theme_Helper::render_html($this->get_render_attribute_string( 'text' )); ?>><?php echo Thegov_Theme_Helper::render_html($settings['text']); ?></span>
            </span>
            <?php
        }


		/**
			 * Add render attribute.
			 *
			 * Used to add attributes to a specific HTML element.
			 *
			 * The HTML tag is represented by the element parameter, then you need to
			 * define the attribute key and the attribute key. The final result will be:
			 * `<element attribute_key="attribute_value">`.
			 *
			 * Example usage:
			 *
			 * `$this->add_render_attribute( 'wrapper', 'class', 'custom-widget-wrapper-class' );`
			 * `$this->add_render_attribute( 'widget', 'id', 'custom-widget-id' );`
			 * `$this->add_render_attribute( 'button', [ 'class' => 'custom-button-class', 'id' => 'custom-button-id' ] );`
			 *
			 * @since 1.0.0
			 * @access public
			 *
			 * @param array|string $element   The HTML element.
			 * @param array|string $key       Optional. Attribute key. Default is null.
			 * @param array|string $value     Optional. Attribute value. Default is null.
			 * @param bool         $overwrite Optional. Whether to overwrite existing
			 *                                attribute. Default is false, not to overwrite.
			 *
			 * @return Element_Base Current instance of the element.
			 */
		public function add_render_attribute( $element, $key = null, $value = null, $overwrite = false ) {
			if ( is_array( $element ) ) {
				foreach ( $element as $element_key => $attributes ) {
					$this->add_render_attribute( $element_key, $attributes, null, $overwrite );
				}

				return $this;
			}

			if ( is_array( $key ) ) {
				foreach ( $key as $attribute_key => $attributes ) {
					$this->add_render_attribute( $element, $attribute_key, $attributes, $overwrite );
				}

				return $this;
			}

			if ( empty( $this->render_attributes[ $element ][ $key ] ) ) {
				$this->render_attributes[ $element ][ $key ] = [];
			}

			settype( $value, 'array' );

			if ( $overwrite ) {
				$this->render_attributes[ $element ][ $key ] = $value;
			} else {
				$this->render_attributes[ $element ][ $key ] = array_merge( $this->render_attributes[ $element ][ $key ], $value );
			}

			return $this;
		}

		public function get_render_attribute_string( $element ) {
			if ( empty( $this->render_attributes[ $element ] ) ) {
				return '';
			}

			return Thegov_Theme_Helper::render_html_attributes( $this->render_attributes[ $element ] );
		}

	    /**
		* Generate header spacer
		*
		*
		* @since 1.0
		* @access public
		*/

		public function header_bar_spacer($location = null, $key = null){
			if(!$key)
				return;

			/*
			 * Define Theme options and field configurations.
			*/

			$get_number = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
			$spacer = Thegov_Theme_Helper::get_option($location.'_header_spacer'.$get_number, $this->name_preset, $this->def_preset);
	    	// Header Bar Spacer render
	        $html = "";
	        if (is_array($spacer)) {
	            $html .= "<div class='header_spacing spacer_".$get_number."' style='width:".esc_attr( (int) $spacer['width'] )."px;'>";
	            $html .= "</div>";
	        }

	        return $html;
		}

		public function header_bar_spacer_height($location = null, $key = null){
			if(!$key)
				return;

			/*
			 * Define Theme options and field configurations.
			*/

			$get_number = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
			$spacer = Thegov_Theme_Helper::get_option($location.'_header_spacer'.$get_number, $this->name_preset, $this->def_preset);
	    	// Header Bar Spacer render
	        $html = "";
	        if (is_array($spacer)) {
	            $html .= "<div class='header_spacing spacer_".$get_number."' style='display:block;height:".esc_attr( (int) $spacer['width'] )."px;'>";
	            $html .= "</div>";
	        }

	        return $html;
		}

		/**
		* Generate header builder layout
		*
		*
		* @since 1.0
		* @access public
		*/
	   	public function build_header_layout($section = 'bottom'){

	   		$header_sticky_builder = Thegov_Theme_Helper::get_option('sticky_header');

	   		if(empty($header_sticky_builder) && $this->html_render == 'sticky'){
	   			$section = 'bottom';
	   		}

	   		$this->name_preset = $section == 'bottom' ? $this->name_preset : null;
	   		$header_layout = Thegov_Theme_Helper::get_option($section.'_header_layout', $this->name_preset, $this->def_preset);
	   		$lavalamp_active = Thegov_Theme_Helper::get_option('lavalamp_active', $this->name_preset, $this->def_preset);

			// Get item from recycle bin
			$j =0;
			$header_layout_top = $header_layout_middle = $header_layout_bottom = array();

			// Build Row Item
			$counter = 1;
			if($section == 'bottom'){
				$header_layout = array_slice($header_layout, 1);
				$count = count($header_layout);
				$half = 3;
				for($i = 0 ;$i<3;$i++){
					switch ($i) {
						case 0:
							$header_layout_top = array_slice($header_layout, $j, $half);
							break;
						case 1:
							$header_layout_middle = array_slice($header_layout, $j, $half);
							break;
						case 2:
							$header_layout_bottom = array_slice($header_layout, $j, $half);
							break;
					}

					$j = $j+$half;
				}

				// wgl Header Builder Row
				$counter = 3;
			}

			/**
			* Generate sticky builder(default)
			*/
			$inc_sticky = 0;
			$sticky_present_element = false;
			$sticky_last_row = '';
			$sticky_key_last_row = array();

			for ($i=1; $i <= $counter; $i++) {
		    	if($section == 'bottom'){
 	 				switch ($i) {
 	 					case 1:
 	 						$sticky_loc = '_top';
 	 						break;
 	 					case 2:
 	 						$sticky_loc = '_middle';
 	 						break;
 	 					case 3:
 	 						$sticky_loc = '_bottom';
 	 						break;
 	 				}
		    		$sticky_header_layout = ${"header_layout" . $sticky_loc};

    				//Disabled Sticky Options
		    		$disabled_sticky = false;
		    		foreach ($sticky_header_layout as $s => $d) {
		    			if(isset($sticky_header_layout[$s]['disable_row']) && $sticky_header_layout[$s]['disable_row'] == 'true'){
		    				$disabled_sticky = true;
		    				continue;
		    			}
		    		}
		    		if(!$disabled_sticky){
			    		foreach ($sticky_header_layout as $key => $v) {
			    			if(isset($sticky_header_layout[$key]['disable_row'])){
				    			unset($sticky_header_layout[$key]['disable_row']);
				    		}
			    			if (count($sticky_header_layout[$key]) == 1 && empty($sticky_header_layout[$key]['placebo']) || count($sticky_header_layout[$key]) > 1) {
			    				$sticky_present_element = true;
			    				$sticky_key_last_row[] = $key;
			    			}
			    		}
		    		}

		    	}else{
		    		$sticky_present_element = true;
		    	}

		    	if (!empty($sticky_header_layout)) {
			    	if($sticky_present_element && $this->html_render == 'sticky'){
						$inc_sticky++;
						$sticky_present_element = false;
			    	}
			    }
		    }

		    if(is_array($sticky_key_last_row)){
				$last_element = end($sticky_key_last_row);
				if($last_element){
				    switch ($last_element) {
				     	case array_key_exists($last_element, $header_layout_top):
				     		$sticky_last_row = '_top';
				     		break;
				     	case array_key_exists($last_element, $header_layout_middle):
				     		$sticky_last_row = '_middle';
				     		break;
				     	case array_key_exists($last_element, $header_layout_bottom):
				     		$sticky_last_row = '_bottom';
				     		break;
				    }
				}
		    }
		    /**
			* End Generate sticky builder(default)
			*/

		    $location = '';
		   	$has_element = false;

 	 		$counter = $inc_sticky > 1  ? 1 : $counter;

		    for ($i=1; $i <= $counter; $i++) {
 	 			if($section == 'bottom'){
 	 				switch ($i) {
 	 					case 1: $location = '_top'; break;
 	 					case 2: $location = '_middle'; break;
 	 					case 3: $location = '_bottom'; break;
 	 				}

		    		if($inc_sticky > 1){
		    			$location = $sticky_last_row;
		    		}

		    		$header_layout = ${"header_layout" . $location};

		    		//Disabled Row Options
		    		$disabled_row = false;
		    		foreach ($header_layout as $s => $d) {
		    			if(isset($header_layout[$s]['disable_row']) && $header_layout[$s]['disable_row'] == 'true'){
		    				$disabled_row = true;
		    				continue;
		    			}
		    		}

		    		if (!$disabled_row) {
			    		foreach ($header_layout as $key => $v) {
			    			if(isset($header_layout[$key]['disable_row'])){
			    				unset($header_layout[$key]['disable_row']);
			    			}
			    			if (count($header_layout[$key]) == 1 && empty($header_layout[$key]['placebo']) || count($header_layout[$key]) > 1) {
			    				$has_element = true;
			    			}
			    		}
		    		}

		    	} else {
		    		$has_element = true;
		    	}

			    if (!empty($header_layout)) {
			    	if($has_element){
			    		switch ($section) {
			    			case 'mobile_content':
				    			foreach ($header_layout as $part => $value) {
				    				if (!empty($header_layout[$part]) && $part != 'items' ) {

				    					if (count($header_layout[$part]) == 1 && empty($header_layout[$part]['placebo']) || count($header_layout[$part]) > 1) {
				    						foreach ($header_layout[$part] as $key => $value) {
				    							if ($key != 'placebo' ) {
				    								switch ($key) {
				    									case 'item_search':
				    									echo "<div class='header_search search_mobile_menu'>";
															echo "<div class='header_search-field'>";
																get_search_form();
															echo "</div>";
														echo "</div>";

				    									break;

				    									case 'logo':
				    									$logo = self::get_logo($this->html_render, true);
				    									echo !empty($logo) ? $logo : '';
				    									break;
				    									case 'menu':
				    									$menu = '';
				    									if (class_exists( 'RWMB_Loader' ) && $this->id !== 0) {
				    										if (rwmb_meta('mb_customize_header_layout') == 'custom') {
				    											$menu = rwmb_meta('mb_menu_header');
				    										}
				    									}
				    									if (has_nav_menu( 'main_menu' )) {
				    										echo "<nav class='primary-nav'>";
				    										thegov_main_menu ($menu);
				    										echo "</nav>";
				    									}
				    									break;
				    									case stripos($key,'html') !== false:
				    									$this_header_bar_editor = $this->header_bar_editor('mobile',$key);
				    									echo !empty($this_header_bar_editor) ? $this->header_bar_editor('mobile',$key)  : '';
				    									break;
				    									case 'wpml':
				    									if (class_exists('SitePress')) {
				    										echo "<div class='sitepress_container'>";
				    										do_action('wpml_add_language_selector');
				    										echo "</div>";
				    									}
				    									break;
				    									case stripos($key,'spacer') !== false:
				    									$this_header_bar_spacer = $this->header_bar_spacer_height('mobile',$key);
				    									echo !empty($this_header_bar_spacer) ? $this->header_bar_spacer_height('mobile',$key)  : '';
				    									break;
				    								}
				    							}
				    						}
				    					}
				    				}
				    			}
			    				break;
			    			default:
			    				echo '<div class="wgl-header-row wgl-header-row-section'.esc_attr($location).'"'.$this->row_style_color($location, $section).'>';
						    		echo '<div class="'.esc_attr($this->row_width_class($location, $section)).'">';
							    		echo '<div class="wgl-header-row_wrapper"'.$this->row_style_height($location, $section).'>';
							    		foreach ($header_layout as $part => $value) {
							    			if (!empty($header_layout[$part]) && $part != 'items' ) {

							    				$area_name = '';
							    				switch ($part) {
							    					case stripos($part,'center') !== false: $area_name = 'center'; break;
								    				case stripos($part,'left') !== false: $area_name = 'left'; break;
								    				case stripos($part,'right') !== false: $area_name = 'right'; break;
							    				}
												$column_class  = $this->column_class($location, $section, $area_name);

							    				$class_area = 'position_'.$area_name.$location;

								    			echo "<div class='".esc_attr(sanitize_html_class($class_area))." header_side".esc_attr($column_class)."'>";

								    			if (count($header_layout[$part]) == 1 && empty($header_layout[$part]['placebo']) || count($header_layout[$part]) > 1) {
								    				echo "<div class='header_area_container'>";
								    				foreach ($header_layout[$part] as $key => $value) {
								    					if ($key != 'placebo' && $key != 'pos_column') {
								    						switch ($key) {
								    							case 'item_search':
								    								$this->search($this->html_render,$location, $section);
								    								break;
								    							case 'cart':
								    								if(class_exists( 'WooCommerce' ))
								    								$this->cart($location, $section);
								    								break;
								    							case 'login':
								    								if(class_exists( 'WooCommerce' ))
								    								$this->login_in($location, $section);
								    								break;
								    							case 'wishlist':
								    								if(class_exists( 'YITH_WCWL' ))
								    								$this->wishlist($location, $section);
								    								break;
								    							case 'side_panel':
								    								$this->side_panel_enabled = true;
								    								$this->get_side_panel($location, $section);
								    								break;
								    							case 'logo':
								    							$logo = self::get_logo($this->html_render);
								    							echo !empty($logo) ? $logo : '';
								    								break;
								    							case 'menu':
								    							$menu = '';
								    							if (class_exists( 'RWMB_Loader' ) && $this->id !== 0) {
								    								if (rwmb_meta('mb_customize_header_layout') == 'custom') {
			                											$menu = rwmb_meta('mb_menu_header');
			            											}
								    							}
								    							if (has_nav_menu( 'main_menu' )) {
								    								echo "<nav class='primary-nav".($lavalamp_active == '1' ? ' menu_line_enable' : '')."' ".$this->row_style_height($location, $section).">";
								    								thegov_main_menu ($menu);
								    								echo "</nav>";
								    								echo '<div class="mobile-hamburger-toggle"><div class="hamburger-box"><div class="hamburger-inner"></div></div></div>';
								    							}
								    								break;
								    							case stripos($key,'html') !== false:
								    							$this_header_bar_editor = $this->header_bar_editor($section,$key);
								    							echo !empty($this_header_bar_editor) ? $this->header_bar_editor($section,$key)  : '';
								    								break;
								    							case 'wpml':
									    							if (class_exists('SitePress')) {
									    								echo "<div class='sitepress_container' ".$this->row_style_height($location, $section).">";
									    								do_action('wpml_add_language_selector');
									    								echo "</div>";
									    							}
								    								break;
								    							case stripos($key,'delimiter') !== false:
								    								$this->header_bar_delimiter($key);
								    								break;
								    							case stripos($key,'button') !== false:
								    								$this->header_bar_button($key);
								    								break;
								    							case stripos($key,'spacer') !== false:
								    							$this_header_bar_spacer = $this->header_bar_spacer($section,$key);
								    							echo !empty($this_header_bar_spacer) ? $this->header_bar_spacer($section,$key)  : '';
								    								break;
								    						}
								    					}
								    				}
								    				echo "</div>";
								    			}
								    			echo "</div>";
							    			}
							    		}
							    		echo '</div>';
						    		echo '</div>';
				    			echo '</div>';
			    			break;
			    		}

			    		$has_element = false;
			    	}
			    }
		    }
	   		/*echo "</div>";*/
	   	}

	   	private function row_width_class($s = '_middle', $section){
			/**
			* Loop Header Row Style Color
			*
			*
			* @since 1.0
			* @access private
			*/
			$class = '';


			switch ($section) {
				case 'bottom':
					$width_container = Thegov_Theme_Helper::get_option('header'.$s.'_full_width',$this->name_preset, $this->def_preset);
					if ($width_container == '1') {
			   			$class = "fullwidth-wrapper";
			   		} else {
			   			$class = 'wgl-container';
			   		}
					break;

				case 'sticky':
					$width_container = Thegov_Theme_Helper::get_option('header_custom_sticky_full_width');
					if ($width_container == '1') {
			   			$class = "fullwidth-wrapper";
			   		} else {
			   			$class = 'wgl-container';
			   		}
					break;

				default:
					$class = 'wgl-container';
	        		break;

			}

			return $class;
	   	}

	   	private function row_style_color($s = '_middle', $section) {
	   		$style = '';

			switch ($this->html_render) {
				case 'bottom':
					$header_background = Thegov_Theme_Helper::get_option('header'.$s.'_background', $this->name_preset, $this->def_preset);
					$header_background_image = Thegov_Theme_Helper::get_option('header'.$s.'_background_image', $this->name_preset, $this->def_preset);
					$header_background_image = isset($header_background_image['url']) ? $header_background_image['url'] : '';

					$header_color = Thegov_Theme_Helper::get_option('header'.$s.'_color', $this->name_preset, $this->def_preset);
					$header_bottom_border = Thegov_Theme_Helper::get_option('header'.$s.'_bottom_border', $this->name_preset, $this->def_preset);
					$header_border_height = Thegov_Theme_Helper::get_option('header'.$s.'_border_height', $this->name_preset, $this->def_preset);
					$header_border_height = $header_border_height['height'];
					$header_bottom_border_color = Thegov_Theme_Helper::get_option('header'.$s.'_bottom_border_color', $this->name_preset, $this->def_preset);

					$style = '';
					if (!empty($header_background['rgba'])) {
						$style .= !empty($header_background['rgba']) ? 'background-color: '.esc_attr($header_background['rgba']).';' : '';
					}

					if(!empty($header_background_image)){
						$style .= 'background-size:cover;background-repeat:no-repeat; background-image:url('.esc_attr($header_background_image).');';
					}

					if(!empty($header_bottom_border)){
						$style .= !empty($header_border_height) ? 'border-bottom-width: '.(int) (esc_attr($header_border_height)).'px;' : '';
						if (!empty($header_bottom_border_color['rgba'])) {
							$style .= 'border-bottom-color: '.esc_attr($header_bottom_border_color['rgba']).';';
						}

						$style .= 'border-bottom-style: solid;';
					}
					if (!empty($header_color['rgba'])) {
						$style .= !empty($header_color['rgba']) ? 'color: '.esc_attr($header_color['rgba']).';' : '';
					}

					$customize_width = Thegov_Theme_Helper::get_option('header'.$s.'_max_width_custom', $this->name_preset, $this->def_preset);

					if ($customize_width == '1') {
						$max_width = Thegov_Theme_Helper::get_option( 'header'.$s.'_max_width', $this->name_preset, $this->def_preset );
						$max_width = $max_width['width'];

						$style .= 'max-width: '.esc_attr((int) $max_width ).'px; margin-left: auto; margin-right: auto;';
					}

					break;

				case 'sticky':

		   			$header_sticky_background = Thegov_Theme_Helper::get_option('header_sticky_background');
		   			$header_sticky_color = Thegov_Theme_Helper::get_option('header_sticky_color');
		   			$header_sticky_border = Thegov_Theme_Helper::get_option('header_sticky_border');
		   			$header_sticky_border_height = Thegov_Theme_Helper::get_option('header_sticky_border_height');
		   			$header_sticky_border_color = Thegov_Theme_Helper::get_option('header_sticky_border_color');

		   			$style .= !empty($header_sticky_background['rgba']) ? 'background-color: '.(esc_attr($header_sticky_background['rgba'])).';' : '';
		   			$style .= !empty($header_sticky_color) ? 'color: '.(esc_attr($header_sticky_color)).';' : '';

		   			if($header_sticky_border == '1'){
		   				$style .= !empty($header_sticky_border_height['height']) ? 'border-style:solid;border-width: '.(int)(esc_attr($header_sticky_border_height['height'])).'px;' : '';
		   				$style .= !empty($header_sticky_border_color['rgba']) ? 'border-color: '.(esc_attr($header_sticky_border_color['rgba'])).';' : '';
		   			}

		   			if($section === 'bottom'){
						$customize_width = Thegov_Theme_Helper::get_option('header'.$s.'_max_width_custom', $this->name_preset, $this->def_preset);

						if ($customize_width == '1') {
							$max_width = Thegov_Theme_Helper::get_option( 'header'.$s.'_max_width', $this->name_preset, $this->def_preset );
							$max_width = $max_width['width'];

							$style .= 'max-width: '.esc_attr((int) $max_width ).'px; margin-left: auto; margin-right: auto;';
						}
		   			}else{
						$customize_width = Thegov_Theme_Helper::get_option('header_custom_sticky_max_width');

						if ($customize_width == '1') {
							$max_width = Thegov_Theme_Helper::get_option( 'header_sticky_max_width' );
							$max_width = $max_width['width'];

							$style .= 'max-width: '.esc_attr((int) $max_width ).'px; margin-left: auto; margin-right: auto;';
						}
		   			}
				break;
			}

			$style = !empty($style) ? ' style="'.$style.'"' : '';

			return $style;
	   	}

	   	private function row_style_height($s = '_middle', $section, $width = false){
			/**
			* Loop Row Style Height
			*
			*
			* @since 1.0
			* @access private
			*/

			$header_sticky_height = Thegov_Theme_Helper::get_option('header_sticky_height');
	   		$header_mobile_height = Thegov_Theme_Helper::get_option('header_mobile_height');

	   		$header_height = Thegov_Theme_Helper::get_option('header'.$s.'_height', $this->name_preset, $this->def_preset);
			$header_height = $header_height['height'] ?? null;

			$style = '';

	        switch ($this->html_render) {
	        	case 'sticky':
		        	if (!empty($header_sticky_height["height"])) {
			        	$style = 'height:'.(int)$header_sticky_height["height"].'px;';
			        	if(!empty($width)){
			        		$style .= 'width:'.(int)$header_sticky_height["height"].'px;';
			        	}
			        	$style = ' style="'.$style.'"';
			        }
	        		break;

	        	case 'mobile':
		        	if (!empty($header_mobile_height["height"])) {
		        		$style = 'height:'.(int)$header_mobile_height["height"].'px;';
			        	if(!empty($width)){
			        		$style .= 'width:'.(int)$header_mobile_height["height"].'px;';
			        	}
			        	$style = ' style="'.$style.'"';

		        	}
	        		break;

	        	default:
	        		if(!empty($header_height)){
						$style = 'height:'.(int) (esc_attr($header_height)).'px;';

						if(!empty($width)){
							$style .= 'width:'.(int) (esc_attr($header_height)).'px;';
						}
						$style = ' style="'.$style.'"';
	        		}

	        		break;

	        }

			return $style;
	   	}

	   	private function side_panel_style_icon($s = '_middle', $section){

	   		$sticky_icon_switcher = Thegov_Theme_Helper::get_option('bottom_header_side_panel_sticky_custom', $this->name_preset, $this->def_preset);

	   		$value = '';
	   		if($sticky_icon_switcher === '1'){
	   			$value = $this->html_render === 'sticky' ? 	'_sticky' : '';
	   		}

			$icon_background = Thegov_Theme_Helper::get_option('bottom_header_side_panel'.$value.'_background', $this->name_preset, $this->def_preset);
			$icon_color = Thegov_Theme_Helper::get_option('bottom_header_side_panel'.$value.'_color', $this->name_preset, $this->def_preset);

			$style = '';


			$style .= !empty($icon_background['rgba']) ? 'background-color: '.esc_attr($icon_background['rgba']).';' : '';
			$style .= !empty($icon_color['rgba']) ? 'color: '.esc_attr($icon_color['rgba']).';' : '';

			$style = !empty($style) ? ' style="'.$style.'"' : '';
			return $style;


	   	}

	   	private function column_class($s = '_middle', $section, $area){
		   	/**
			* Loop column class
			*
			*
			* @since 1.0
			* @access private
			*/
			$dispay = Thegov_Theme_Helper::get_option('header_column'.$s.'_'.$area.'_display', $this->name_preset, $this->def_preset);
			$v_align = Thegov_Theme_Helper::get_option('header_column'.$s.'_'.$area.'_vert', $this->name_preset, $this->def_preset);
			$h_align = Thegov_Theme_Helper::get_option('header_column'.$s.'_'.$area.'_horz', $this->name_preset, $this->def_preset);

	        $column_class  = '';
	        $column_class .= !empty($dispay) ? " display_".$dispay : "";
	        $column_class .= !empty($v_align) ? " v_align_".$v_align : "";
	        $column_class .= !empty($h_align) ? " h_align_".$h_align : "";

			return $column_class;
	   	}

	   	/**
		* Generate header mobile menu
		*
		*
		* @since 1.0
		* @access public
		*/
	   	public function build_header_mobile_menu($preset = null, $def_preset = null){
	   		$preset = !$preset ? $this->name_preset : $preset;
	   		$def_preset = !$def_preset ? $this->def_preset : $def_preset;

	   		$header_queris = Thegov_Theme_Helper::get_option('header_mobile_queris' ,$preset, $def_preset);
	   		$sub_menu_position = Thegov_Theme_Helper::get_option('mobile_position');
	   		$mobile_header_custom =  Thegov_Theme_Helper::get_option('mobile_header');

	   		echo "<div class='mobile_nav_wrapper' data-mobile-width='".$header_queris."'>";

	   			echo "<div class='container-wrapper'>";

		   			echo "<div class='wgl-menu_overlay'></div>";

		   			echo "<div class='wgl-menu_outer".( !empty($sub_menu_position) ? ' sub-menu-position_'.esc_attr($sub_menu_position) : '')."' id='wgl-perfect-container'>";

			   			echo '<div class="wgl-menu-outer_header">';

				   			echo '<div class="mobile-hamburger-close">';
				   				echo '<div class="mobile-hamburger-toggle">';
					   				echo '<div class="hamburger-box">';
					   					echo '<div class="hamburger-inner"></div>';
					   				echo '</div>';
				   				echo '</div>';
				   			echo '</div>';
				   		echo '</div>';
						echo "<div class='wgl-menu-outer_content'>";
							if(!empty($mobile_header_custom)){
					   			$this->build_header_layout( 'mobile_content' );
					   		}else{
					   			if (has_nav_menu( 'main_menu' )) {
						   			echo "<nav class='primary-nav'>";
							   			$logo = self::get_logo($this->html_render, true);
										echo !empty($logo) ? $logo : '';
							   			$menu = '';
							   			if (class_exists( 'RWMB_Loader' ) && $this->id !== 0) {
							   				if (rwmb_meta('mb_customize_header_layout') == 'custom') {
							   					$menu = rwmb_meta('mb_menu_header');
							   				}
							   			}
							   			thegov_main_menu ($menu);
							   		echo "</nav>";
							   	}
					   		}
				   		echo "</div>";
		   			echo "</div>";
		   		echo "</div>";
	   		echo "</div>";
	   	}

	    public function header_render_html(){
	    	$mobile_header_custom =  Thegov_Theme_Helper::get_option('mobile_header');
	        echo "<header class='wgl-theme-header".esc_attr($this->header_class())."'>";

	            // header output
	            echo "<div class='wgl-site-header".(!empty($mobile_header_custom) ? ' mobile_header_custom' : "")."'>";
	                echo "<div class='container-wrapper'>";
	                $this->build_header_layout();
	                echo "</div>";

	                if(empty($mobile_header_custom)){
	                	$this->build_header_mobile_menu();
	                }

	            echo "</div>";

	            // sticky header output
	            get_template_part('templates/header/block', 'sticky');

	            // mobile output
	            get_template_part('templates/header/block', 'mobile');

	        echo "</header>";

	        if(!empty($this->side_panel_enabled)){
	        	// side panel output
	            get_template_part('templates/header/block', 'side_area');
	        }
	    }

	   	/**
		* Get header Logotype
		*
		*
		* @since 1.0
		* @access public
		*/
        public static function get_logo($location, $menu = false){
        	$id = !is_archive() ? get_queried_object_id() : 0;
			$img_alt = '';
            // Get Default Logotype
            $header_logo_src = Thegov_Theme_Helper::get_option('header_logo');
            $header_logo_id = !empty($header_logo_src) ? $header_logo_src['id'] : '';
            $header_logo_src = !empty($header_logo_src) ? $header_logo_src['url'] : '';
			// logo default image alt
			$def_img_alt = get_post_meta($header_logo_id, '_wp_attachment_image_alt', true);

            // Get Sticky Logotype
            $logo_sticky_src =  Thegov_Theme_Helper::get_option('logo_sticky');
            $logo_sticky_id = !empty($logo_sticky_src) ? $logo_sticky_src['id'] : '';
			$logo_sticky_src =  !empty($logo_sticky_src) ? $logo_sticky_src['url'] : '';
			// logo sticky image alt
			$sticky_img_alt = get_post_meta($logo_sticky_id, '_wp_attachment_image_alt', true);

            // Get Mobile Logotype

            $menu = !empty($menu) ? '_menu' : '';

            $logo_mobile_src =  Thegov_Theme_Helper::get_option('logo_mobile'.$menu);
            $logo_mobile_id = !empty($logo_mobile_src) ? $logo_mobile_src['id'] : '';
            $logo_mobile_src =  !empty($logo_mobile_src) ? $logo_mobile_src['url'] : '';
			// logo mobile image alt
			$mobile_img_alt = get_post_meta($logo_mobile_id, '_wp_attachment_image_alt', true);


            if (class_exists( 'RWMB_Loader' ) && $id !== 0) {
                if (rwmb_meta('mb_customize_logo') == 'custom') {

                    // Get Default RWMB Logotype
                    $mb_header_logo_src = rwmb_meta("mb_header_logo");
                    if (!empty($mb_header_logo_src)) {
                        $header_logo_src = array_values($mb_header_logo_src);
                        $header_logo_src = $header_logo_src[0]['full_url'];
                    }

                    // Get Sticky RWMB Logotype
                    $mb_logo_sticky_src = rwmb_meta('mb_logo_sticky');
                    if (!empty($mb_logo_sticky_src)) {
                        $logo_sticky_src = array_values($mb_logo_sticky_src);
                        $logo_sticky_src = $logo_sticky_src[0]['full_url'];
                    }

                    // Get Mobile RWMB Logotype
                    $mb_logo_mobile_src = rwmb_meta('mb_logo_mobile'.$menu);
                    if (!empty($mb_logo_mobile_src)) {
                        $logo_mobile_src = array_values($mb_logo_mobile_src);
                        $logo_mobile_src = $logo_mobile_src[0]['full_url'];
                    }
                }
            }

            $logo_height_custom = Thegov_Theme_Helper::get_option('logo_height_custom');
            $logo_height = Thegov_Theme_Helper::get_option('logo_height');
            $logo_height = $logo_height['height'];


            $sticky_logo_height_custom = Thegov_Theme_Helper::get_option('sticky_logo_height_custom');
            $sticky_logo_height = Thegov_Theme_Helper::get_option('sticky_logo_height');
            $sticky_logo_height = $sticky_logo_height['height'];

            $mobile_logo_height_custom = Thegov_Theme_Helper::get_option('mobile_logo'.$menu.'_height_custom');
            $mobile_logo_height = Thegov_Theme_Helper::get_option('mobile_logo'.$menu.'_height');
            $mobile_logo_height = $mobile_logo_height['height'];

            if (class_exists( 'RWMB_Loader' ) && $id !== 0) {
                if (rwmb_meta('mb_customize_logo') == 'custom') {
                    if (rwmb_meta('mb_logo_height_custom') == '1') {
                        $logo_height_custom = rwmb_meta('mb_logo_height_custom');
                        $logo_height = rwmb_meta('mb_logo_height');
					}
					if (rwmb_meta('mb_sticky_logo_height_custom') == '1') {
                        $sticky_logo_height_custom = rwmb_meta('mb_sticky_logo_height_custom');
                        $sticky_logo_height = rwmb_meta('mb_sticky_logo_height');
                    }
                    if (rwmb_meta('mb_mobile_logo_height_custom') == '1') {
                        $mobile_logo_height_custom = rwmb_meta('mb_mobile_logo'.$menu.'_height_custom');
                        $mobile_logo_height = rwmb_meta('mb_mobile_logo'.$menu.'_height');
                    }
                }
            }

            $logo_height_css = $mobile_height_style = $sticky_height_style = '';

            if (!empty($logo_height) && $logo_height_custom == '1') {
                $logo_height_css .= 'height:'.(esc_attr((int) $logo_height)).'px;';
            }
            $logo_height_style = !empty($logo_height_css) ? ' style="'.$logo_height_css.'"' : '';

            switch (true) {
				case !empty($sticky_logo_height) && $sticky_logo_height_custom == '1' && $location == 'sticky':
					$sticky_height_style .= 'height:'.(esc_attr((int) $sticky_logo_height)).'px;';
					break;

            	case !empty($mobile_logo_height) && $mobile_logo_height_custom == '1' && $location == 'mobile':
					$mobile_height_style .= 'height:'.(esc_attr((int) $mobile_logo_height)).'px;';
            		break;

            	default:
					if(!empty($logo_height) && $logo_height_custom == '1'){
						$sticky_height_style = $mobile_height_style = $logo_height_css;
					}
	        		break;
            }

            // Set Sticky Height Logotype
            $sticky_height_style = !empty($sticky_height_style) ? ' style="'.$sticky_height_style.'"' : '';

          	// Set Mobile Height Logotype
            $mobile_height_style = !empty($mobile_height_style) ? ' style="'.$mobile_height_style.'"' : '';
            $class = (!empty($logo_sticky_src) ? " logo-sticky_enable" : '').(!empty($logo_mobile_src) ? " logo-mobile_enable" : '');

            ?><div class='wgl-logotype-container<?php echo esc_attr($class);?>'>
            <a href='<?php echo esc_url(home_url('/')) ?>'>
			<?php
			switch (true) {
				case $location == 'bottom':
					if (!empty($header_logo_src)) {
						?>
						<img class="default_logo" src="<?php echo esc_url($header_logo_src); ?>" alt="<?php echo esc_attr($def_img_alt); ?>" <?php echo Thegov_Theme_Helper::render_html($logo_height_style);?>>
					<?php
					} else {
						?>
						<h1 class="logo-name">
						<?php echo get_bloginfo( 'name' ); ?>
						</h1>
						<?php
					}
					break;

				case !empty($logo_sticky_src) && $location == 'sticky':
					?>
					<img class="logo-sticky" src="<?php echo esc_url($logo_sticky_src);?>" alt="<?php echo esc_attr($sticky_img_alt); ?>" <?php echo Thegov_Theme_Helper::render_html($sticky_height_style);?>>
					<?php
					break;

				case !empty($logo_mobile_src) && $location == 'mobile':
					?>
					<img class="logo-mobile" src="<?php echo esc_url($logo_mobile_src);?>" alt="<?php echo esc_attr($mobile_img_alt); ?>" <?php echo Thegov_Theme_Helper::render_html($mobile_height_style);?>>
					<?php
					break;

				default:
					if (!empty($header_logo_src)) {
						?>
						<img class="default_logo" src="<?php echo esc_url($header_logo_src); ?>" alt="<?php echo esc_attr($def_img_alt); ?>" <?php echo Thegov_Theme_Helper::render_html($logo_height_style);?>>
					<?php
					} else {
						?>
						<h1 class="logo-name">
						<?php echo get_bloginfo( 'name' ); ?>
						</h1>
						<?php
					}
					break;
			}

            ?>
            </a>
            </div>
            <?php
        }

	   	/**
		* Get Header Search
		*
		*
		* @since 1.0
		* @access public
		*/
	    public function search($html_render = '',$location, $section){
	    	$description = esc_html__('Type To Search', 'thegov');
			$search_style = Thegov_Theme_Helper::get_option('search_style');
			$search_style =  !empty($search_style) ? $search_style : 'standard';

	        $render_serch = true;
	        if ($search_style === 'alt') {
	        	if ($this->html_render != 'sticky') {
	        		$render_serch = true;
	        	} else {
	        		$render_serch = false;
	        	}
	        }

	        $search_class = ' search_'.Thegov_Theme_Helper::get_option('search_style');

			$options_btn = $this->html_render === 'sticky' ? '_sticky' : '';

			$customize = Thegov_Theme_Helper::get_option('bottom_header_item_search_custom'.$options_btn, $this->name_preset, $this->def_preset);
			$customize = empty($customize) ? 'def' : 'color';

			$text_color = Thegov_Theme_Helper::get_option('bottom_header_item_search_color_txt'.$options_btn, $this->name_preset, $this->def_preset);
			$text_color = isset($text_color['rgba']) ? $text_color['rgba'] : '';

			$text_color_hover = Thegov_Theme_Helper::get_option('bottom_header_item_search_hover_color_txt'.$options_btn, $this->name_preset, $this->def_preset);
			$text_color_hover = isset($text_color_hover['rgba']) ? $text_color_hover['rgba'] : '';


			$search_css_id =  uniqid( "thegov_search_" );

			$settings = array(
		        'search_css_id' => $search_css_id,
    		);


	        // Start Custom CSS
	        $styles = '';
	        if($this->html_render !== 'mobile'){
		        ob_start();

					if ($customize != 'def') {
						if ($customize == 'color') {
							echo "#$search_css_id:hover {
									  color: ".(!empty($text_color_hover) ? esc_attr($text_color_hover) : 'transparent')."  !important;
								  }";
						}
					}

		        $styles .= ob_get_clean();
	    	}
	        // Register css
	        if (!empty($styles)) {
	        	Thegov_Theme_Helper::enqueue_css($styles);
	        }

    		unset($this->render_attributes);

    		$this->add_render_attribute( 'search', 'class', ['wgl-search elementor-search header_search-button-wrapper'] );
    		$this->add_render_attribute( 'search', 'role', 'button' );

    		if($this->html_render !== 'mobile'){
	    		if ($customize != 'def') {
					if ($customize == 'color') {
	    				$this->add_render_attribute( 'search', 'style', [ 'color: '.(!empty($text_color) ? esc_attr($text_color) : 'transparent').';' ] );
					}
				}
    		}

    		$this->add_render_attribute( 'search', 'id', $search_css_id );

	        $output = '<div class="header_search'.esc_attr($search_class).'"'.( $this->row_style_height($location, $section) ).'>';

				$output .= '<div '.(Thegov_Theme_Helper::render_html($this->get_render_attribute_string( 'search' ))).'>';
					$output .= '<div class="header_search-button"></div>';
				$output .= '</div>';

				if((bool) $render_serch){
					$output .= '<div class="header_search-field">';
						if ($search_style === 'alt') {
							$output .= '<div class="header_search-wrap">';
								$output .= '<div class="thegov_module_double_headings aleft">';
								$output .= '<h3 class="header_search-heading_description heading_title">'.apply_filters('thegov_desc_search', $description) .'</h3>';
								$output .= '</div>';
							$output .= '</div>';
							$output .= '<div class="header_search-close"></div>';
						}
						$output .= get_search_form(false);
					$output .= '</div>';
				}

	        $output .= '</div>';
	        echo Thegov_Theme_Helper::render_html( $output );
	    }

	    /**
		* Get Side Panel Icon
		*
		*
		* @since 1.0
		* @access public
		*/
	    public function get_side_panel($location, $section){
	        echo "<div class='side_panel'".$this->row_style_height($location, $section).">";
	        	echo "<div class='side_panel_inner'".$this->side_panel_style_icon($location, $section).">";
		        	echo "<a href='#' class='side_panel-toggle'>";
		        		echo "<div class='side_panel-toggle-inner'>";
			        		echo "<span></span>";
			        		echo "<span></span>";
			        		echo "<span></span>";
			        		echo "<span></span>";
			        		echo "<span></span>";
			        		echo "<span></span>";
			        		echo "<span></span>";
			        		echo "<span></span>";
			        		echo "<span></span>";
		        		echo "</div>";
		        	echo '</a>';
	        	echo "</div>";
	        echo "</div>";
	    }

	    /**
		* Get Header Login
		*
		*
		* @since 1.0
		* @access public
		*/
	    public function login_in($location, $section){
	    	$output = '';
	    	$link = get_permalink( get_option('woocommerce_myaccount_page_id') );
			$query_args = array(
				'action' => urlencode('signup_form'),
			);
			$url = add_query_arg( $query_args, $link );

			$link_logout = wp_logout_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
	        echo "<div class='login-in woocommerce'".$this->row_style_height($location, $section).">";

	        	echo "<span class='login-in_wrapper'>";
	        	if ( is_user_logged_in() ){

	        		echo "<a class='login-in_link-logout' href='".esc_url( $link_logout )."'>".esc_html__('Logout', 'thegov')."</a>";
	        	}else{
	        		echo "<a class='login-in_link' href='".esc_url_raw( $url )."'>".esc_html__('Login', 'thegov')."</a>";
	        	}

	        	echo "</span>";

	        	echo "<div class='login-modal wgl_modal-window'>";
	        		echo "<div class='overlay'></div>";
		        	echo "<div class='modal-dialog modal_window-login'>";
		        		echo "<div class='modal_header'>";
		        		echo "</div>";
		        		echo "<div class='modal_content'>";
		        			wc_get_template( 'myaccount/form-login.php' );
		        		echo "</div>";
		        	echo "</div>";
	        	echo "</div>";
	        echo "</div>";
	    }

	    /**
		* Get Header Wishlist
		*
		*
		* @since 1.0
		* @access public
		*/
	    public function wishlist($location, $section){
	    	$output = '';
	        echo "<div class='wishlist-mini-cart woocommerce'".$this->row_style_height($location, $section).">".self::icon_wishlist()."</div>";
	    }

		public static function icon_wishlist() {
			if(class_exists('YITH_WCWL')){
				ob_start();
				$link = get_permalink( get_option('yith_wcwl_wishlist_page_id') );
				$wishlist_count = YITH_WCWL()->count_products();
				?>
					<a class="woo_icon-wishlist" href='<?php echo esc_url( $link ); ?>'>
						<span class="header_wishlist-button">
						</span>
						<span class="woo_wishlist-count">
							<?php
							if(!empty($wishlist_count)){
							?>
								<span>
									<?php
										echo esc_html($wishlist_count);
									?>
								</span>
							<?php
							}

							?>

						</span>
					</a>
				<?php
				return ob_get_clean();
			}

		}

	    /**
		* Get Header Cart
		*
		*
		* @since 1.0
		* @access public
		*/
	    public function cart($location, $section){
	    	$output = '';
	        echo "<div class='mini-cart woocommerce'".( $this->row_style_height($location, $section) ).">".$this->icon_cart().self::woo_cart()."</div>";
	    }

		public function icon_cart() {
			ob_start();
			$link = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : WC()->cart->get_cart_url();

			$options_btn = $this->html_render === 'sticky' ? '_sticky' : '';

			$customize = Thegov_Theme_Helper::get_option('bottom_header_cart_custom'.$options_btn, $this->name_preset, $this->def_preset);
			$customize = empty($customize) ? 'def' : 'color';

			$text_color = Thegov_Theme_Helper::get_option('bottom_header_cart_color_txt'.$options_btn, $this->name_preset, $this->def_preset);
			$text_color = isset($text_color['rgba']) ? $text_color['rgba'] : '';

			$text_color_hover = Thegov_Theme_Helper::get_option('bottom_header_cart_hover_color_txt'.$options_btn, $this->name_preset, $this->def_preset);
			$text_color_hover = isset($text_color_hover['rgba']) ? $text_color_hover['rgba'] : '';

			$cart_css_id =  uniqid( "thegov_woo_" );

			$settings = array(
		        'cart_css_id' => $cart_css_id,
    		);

	        // Start Custom CSS
	        $styles = '';
	        if($this->html_render !== 'mobile'){
		        ob_start();

					if ($customize != 'def') {
						if ($customize == 'color') {
							echo "#$cart_css_id:hover {
									  color: ".(!empty($text_color_hover) ? esc_attr($text_color_hover) : 'transparent')."  !important;
								  }";
						}
					}

		        $styles .= ob_get_clean();
	    	}
	        // Register css
	        if (!empty($styles)) {
	        	Thegov_Theme_Helper::enqueue_css($styles);
	        }

    		unset($this->render_attributes);

    		$this->add_render_attribute( 'cart', 'class', ['wgl-cart woo_icon elementor-cart'] );
    		$this->add_render_attribute( 'cart', 'role', 'button' );
    		$this->add_render_attribute( 'cart', 'title', esc_attr__( 'Click to open Shopping Cart', 'thegov' ) );

    		if($this->html_render !== 'mobile'){
	    		if ($customize != 'def') {
					if ($customize == 'color') {
	    				$this->add_render_attribute( 'cart', 'style', [ 'color: '.(!empty($text_color) ? esc_attr($text_color) : 'transparent').';' ] );
					}
				}
    		}

    		$this->add_render_attribute( 'cart', 'id', $cart_css_id );

			?>
				<a <?php echo Thegov_Theme_Helper::render_html( $this->get_render_attribute_string( 'cart' ) ) ;?> >
					<span class='woo_mini-count flaticon-shopcart-icon'><?php echo ((WC()->cart->cart_contents_count > 0) ?  '<span>' . esc_html( WC()->cart->cart_contents_count ) .'</span>' : '') ?></span>
				</a>
			<?php
			return ob_get_clean();
		}

		public static function woo_cart(){
			ob_start();
				echo '<div class="wgl-woo_mini_cart">';
					woocommerce_mini_cart();
				echo '</div>';
			return ob_get_clean();
		}


	    public function in_array_r($needle, $haystack, $strict = false) {
	    	if(is_array($haystack)){
			    foreach ($haystack as $item) {
			        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
			            return true;
			        }
			    }
	    	}

		    return false;
		}

	}

    new Thegov_get_header();
}
