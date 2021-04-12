<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
/**
* Thegov Theme Helper
*
*
* @class        Thegov_Theme_Helper
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/

if (!class_exists('Thegov_Single_Post')) {

	class Thegov_SinglePost{
		/**
	     * @var SinglePost
	     */
	    private static $instance;

	    /**
	     * @var \WP_Post
	     */
	    private $post_id;
	    private $post_format;
	    private $show_date_meta = true;
	    private $show_author_meta = true;
	    private $show_comments_meta = true;
	    private $show_category_meta = true;

	    /**
	     * @return SinglePost
	     */
	    public static function getInstance () {
	        if (null === static::$instance)
	        {
	            static::$instance = new static();
	        }
	        return static::$instance;
		}

		/**
		 * Provider match masks.
		 *
		 * Holds a list of supported providers with their URL structure in a regex format.
		 *
		 * @var array Provider URL structure regex.
		 */
		private static $provider_match_masks = [
			'youtube' => '/^.*(?:youtu\.be\/|youtube(?:-nocookie)?\.com\/(?:(?:watch)?\?(?:.*&)?vi?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/',
			'vimeo' => '/^.*vimeo\.com\/(?:[a-z]*\/)*([‌​0-9]{6,11})[?]?.*/',
			'dailymotion' => '/^.*dailymotion.com\/(?:video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/',
		];

		/**
		 * Embed patterns.
		 *
		 * Holds a list of supported providers with their embed patters.
		 *
		 * @var array Embed patters.
		 */
		private static $embed_patterns = [
			'youtube' => 'https://www.youtube{NO_COOKIE}.com/embed/{VIDEO_ID}?feature=oembed',
			'vimeo' => 'https://player.vimeo.com/video/{VIDEO_ID}#t={TIME}',
			'dailymotion' => 'https://dailymotion.com/embed/video/{VIDEO_ID}',
		];

	    private function __construct () {
	    	$this->post_id = get_the_ID();
	    }

	    public function set_post_meta ( $args = false ) {
	    	if ( !(bool)$args || empty($args) ) {
	    		$this->show_date_meta = true;
    			$this->show_author_meta = true;
    			$this->show_comments_meta = true;
    			$this->show_category_meta = true;
	    	} else {
	    		$this->show_date_meta = isset($args['date']) ? $args['date'] : "";
    			$this->show_author_meta = isset($args['author']) ? $args['author'] : "";
    			$this->show_comments_meta = isset($args['comments']) ? $args['comments'] : "";
    			$this->show_category_meta = isset($args['category']) ? $args['category'] : "";
	    	}
	    }

	    public function set_data_image ($link_feature = false, $image_size = 'full', $aq_image = false ) {
	    	$this->meta_info_render = false;

	    	$media = false;
			if (class_exists( 'RWMB_Loader' )) {
		    	switch($this->post_format) {
		            case 'gallery' :
		            	$this->meta_info_render = true;
		                $media = $this->featured_gallery($link_feature, $image_size);
		                break;
		            case 'video' :
		            	$this->meta_info_render = true;
		                $media = $this->featured_video($image_size, $aq_image);
		                break;
		            case 'quote' :
		                $this->meta_info_render = false;
		                break;
		            case 'link' :
		                $this->meta_info_render = false;
		                break;
		            case 'audio' :
		            	$this->meta_info_render = false;
		            	break;
		            default :
		            	$this->meta_info_render = true;
		                $media = $this->featured_image($link_feature, $image_size, $aq_image);
		                break;

	 			}

	 		} else {
	 			$this->meta_info_render = true;
	 			$media = $this->featured_image($link_feature, $image_size, $aq_image);
	 		}

	 		if(empty($media)){
	 			$this->meta_info_render = false;
	 		}
	 		$this->post_format = get_post_format();
	    }

	    public function set_data_image_hero ($link_feature = false, $image_size = 'full', $aq_image = false ) {
	    	$this->render_bg_image = false;

	    	$media = false;
			if (class_exists( 'RWMB_Loader' )) {
		    	switch($this->post_format) {
		            case 'gallery' :
		            	$this->render_bg_image = true;
		                $media = $this->featured_image($link_feature, $image_size);
		                break;
		            case 'video' :
		            	$this->render_bg_image = true;
		                $media = $this->featured_image($link_feature, $image_size, $aq_image);
		                break;
		            case 'quote' :
		                $this->render_bg_image = false;
		                break;
		            case 'link' :
		                $this->render_bg_image = false;
		                break;
		            case 'audio' :
		            	$this->render_bg_image = false;
		            	break;
		            default :
		            	$this->render_bg_image = true;
		                $media = $this->featured_image($link_feature, $image_size, $aq_image);
		                break;

	 			}

	 		} else {
	 			$this->render_bg_image = true;
	 			$media = $this->featured_image($link_feature, $image_size, $aq_image);
	 		}

	 		if(empty($media)){
	 			$this->render_bg_image = false;
	 		}
	 		$this->post_format = get_post_format();
	    }

	    public function set_data ($link_feature = false) {
	   	   	$this->post_id = get_the_ID();
	    	$this->post_format = get_post_format();
	    }

	    public function get_pf () {
	    	if(!$this->post_format) {
	    		$featured_image_url = wp_get_attachment_url( get_post_thumbnail_id( $this->post_id ) );
	    		if (has_post_thumbnail() && !empty($featured_image_url)) {
	    			return 'standard-image';
	    		} else {
	    			return 'standard';
	    		}
	    	}

	    	return $this->post_format;
	    }

	    public function render_featured ( $link_feature = false, $image_size = 'full', $aq_image = false, $meta_args = false, $meta_to_show = false ) {
	    	$output = '';
			if (class_exists( 'RWMB_Loader' )) {
		    	switch($this->post_format) {
		            case 'gallery' :
		                $media = $this->featured_gallery($link_feature, $image_size);
		                break;
		            case 'video' :
		            	$media = $this->featured_video($image_size, $aq_image);
		                break;
		            case 'quote' :
		                $media = $this->featured_quote();
		                break;
		            case 'link' :
		                $media = $this->featured_link();
		                break;
		            case 'audio' :
		            	$media = $this->featured_audio();
		            	break;
		            default :
		                $media = $this->featured_image($link_feature, $image_size, $aq_image);
		                break;
	 			}

	 		} else {
	 			$media = $this->featured_image($link_feature, $image_size, $aq_image);
	 		}

	 		$class_media_part = '';

	 		if (class_exists( 'RWMB_Loader' )) {
		 		if($this->post_format == 'video'){
		 			$video_style = rwmb_meta('post_format_video_style');
		 			if($video_style == 'bg_video'){
		 				$class_media_part .= ' video_parallax';
		 			}
		 			if(has_post_thumbnail() && !is_single()){
		 				$class_media_part .= ' video_image';
		 			}
		 		}
	 		}

	 		if (!empty($media)){
	 			echo '<div class="blog-post_media">';
	 			echo '<div class="blog-post_media_part'.esc_attr($class_media_part).'">' . $media;
	 			echo '</div>';
	 			if((bool) $meta_args){
		 			echo '<div class="blog-post_meta_info">';
		 				$this->render_post_meta( $meta_to_show );
		 			echo '</div>';
		 		}
	 			echo '</div>';
	 		}else{
		 		if((bool) $meta_args){
		 			echo '<div class="blog-post_meta_info">';
		 				$this->render_post_meta( $meta_to_show );
		 			echo '</div>';
		 		}
	 		}

	    }

	    public function videobox(){
	    	if($this->post_format === 'video'){
	    		$video_style = rwmb_meta('post_format_video_style');
	    		$video_link = get_post_meta($this->post_id, 'post_format_video_url');

	    		if($video_style != 'bg_video'){
					$uniqrel = uniqid();
		    		echo '<div class="wgl-video_popup button_align-center">';
						if(isset($video_link[0])){
							$lightbox_url = self::get_embed_url($video_link[0], [], []);

							$lightbox_options = [
								'type' => 'video',
								'url' => $lightbox_url,
								'modalOptions' => [
									'id' => 'elementor-lightbox-' . uniqid(),
								],
							];

							unset($this->render_attributes);

							$this->add_render_attribute( 'video-lightbox', [
								'class' => 'videobox_wrapper_link videobox',
								'data-elementor-open-lightbox' => 'yes',
								'data-elementor-lightbox' =>  wp_json_encode( $lightbox_options ),
							] );

			    			echo '<div ' , $this->get_render_attribute_string( 'video-lightbox' ) , '>';
			    		}
			    		echo '<div class="videobox_content"><span class="videobox_link" style="color: #ffffff;"><span class="videobox_icon" style="border-color: transparent transparent transparent #ffffff"></span></span></div>';
						if(isset($video_link[0])){
							echo '</div>';
						}
		    		echo '</div>';
	    		}


	    	}
	    }

	    public function videobox_hero(){
	    	$output = '';


	    	$video_style = rwmb_meta('post_format_video_style');
	    	$video_link = get_post_meta($this->post_id, 'post_format_video_url');

	    	$uniqrel = uniqid();

	    	$output .= '<div class="wgl-video_popup">';

	    	$output .= '<div class="videobox_content">';


	    	if(isset($video_link[0])){
				$lightbox_url = self::get_embed_url($video_link[0], [], []);

				$lightbox_options = [
					'type' => 'video',
					'url' => $lightbox_url,
					'modalOptions' => [
						'id' => 'elementor-lightbox-' . uniqid(),
					],
				];

				unset($this->render_attributes);

				$this->add_render_attribute( 'video-lightbox', [
					'class' => 'videobox_link videobox',
					'data-elementor-open-lightbox' => 'yes',
					'data-elementor-lightbox' =>  wp_json_encode( $lightbox_options ),
				] );

	    		$output .= '<div class="videobox_link_wrapper">';
	    		$output .= '<div ' . $this->get_render_attribute_string( 'video-lightbox' ) . '>';
	    		$output .= '<svg class="videobox_icon" width="31%" height="31%" fill="#ffffff" viewBox="0 0 10 10"><polygon points="1,0 1,10 8.5,5"/></svg>';
	    		$output .= '</div>';
	    		$output .= '</div>';
	    	}

	    	$output .= '</div>';
	    	$output .= '</div>';

	       	return $output;
	    }

	    public function render_bg ( $link_feature = false, $image_size = 'full',$aq_image = false, $data_animation = null, $show_media = true ) {
	    	$media = '';
			$video_style = function_exists("rwmb_meta") ? rwmb_meta('post_format_video_style') : '';

			$featured_image = Thegov_Theme_Helper::options_compare('featured_image_type', 'mb_featured_image_conditional', 'custom');
			if ($featured_image == 'replace') {
				$featured_image_replace = Thegov_Theme_Helper::options_compare('featured_image_replace', 'mb_featured_image_conditional', 'custom');
			}

	    	switch($this->post_format) {
	    		default :
	    		if(has_post_thumbnail()) {

					if (!empty($featured_image_replace) && is_single()) {
						if (rwmb_meta('mb_featured_image_conditional') == 'custom') {
							$image_id = array_values($featured_image_replace);
							$image_id = $image_id[0]['ID'];
						} else{
							$image_id = $featured_image_replace['id'];
						}
					} else{
						$image_id = get_post_thumbnail_id();
					}

	    			$image_data = wp_get_attachment_metadata($image_id);
	    			$image_meta = isset($image_data['image_meta']) ? $image_data['image_meta'] : array();
	    			$upload_dir = wp_upload_dir();
	    			$width = '1170';
	    			$height = '725';
	    			$image_url = wp_get_attachment_image_src( $image_id, $image_size, false );
	    			$temp_url = $image_url[0];
	    			if((bool) $aq_image){
	    				$arr = $this->image_size_render_bg($image_size);
	    				extract($arr);

	    				if(function_exists('aq_resize')){
	    					$image_url[0] = aq_resize($image_url[0], $width, $height, true, true, true);
	    				}
	    			}
	    			$image_url[0] = !empty($image_url[0]) ? $image_url[0] : $temp_url;
	    			$media .= $image_url[0];
	    		}
	    		break;
	    	}

	    	if( $this->post_format === 'gallery' && !is_single()){
	    		$media = $this->featured_gallery($link_feature, $image_size);
	    	}

	    	if($video_style == 'bg_video' && $this->post_format === 'video'){
	    		$media = $this->featured_video($link_feature, $image_size);
	    	}

	    	if ($link_feature) echo '<a href="'.esc_url(get_permalink()).'" class="blog-post_feature-link">';

	    	if((bool) $media && (bool) $show_media){
	    		if($this->post_format === 'video' && $video_style == 'bg_video'){
	    			echo Thegov_Theme_Helper::render_html($media);
	    		}else if($featured_image != 'off'){
	    			echo '<div class="blog-post_bg_media" style="background-image:url('.esc_url($media).')"'.(!empty($data_animation) ? $data_animation : "").'></div>';
	    		}
	    	}

	    	if ($link_feature) echo '</a>';

	    }

	   	public function featured_bg ( $link_feature = false, $image_size = 'full',$aq_image = false, $data_animation = null, $show_media = true ) {
	    	$media = '';
			$video_style = function_exists("rwmb_meta") ? rwmb_meta('post_format_video_style') : '';

			$featured_image = Thegov_Theme_Helper::options_compare('featured_image_type', 'mb_featured_image_conditional', 'custom');
			if ($featured_image == 'replace') {
				$featured_image_replace = Thegov_Theme_Helper::options_compare('featured_image_replace', 'mb_featured_image_conditional', 'custom');
			}

			$default_media = '';

			if(has_post_thumbnail()) {

				$image_id = get_post_thumbnail_id();

				$image_data = wp_get_attachment_metadata($image_id);
				$image_meta = isset($image_data['image_meta']) ? $image_data['image_meta'] : array();
				$upload_dir = wp_upload_dir();
				$width = '1170';
				$height = '725';
				$image_url = wp_get_attachment_image_src( $image_id, $image_size, false );
				$temp_url = $image_url[0];
				if((bool) $aq_image){
					$arr = $this->image_size_render_bg($image_size);
					extract($arr);

					if(function_exists('aq_resize')){
						$image_url[0] = aq_resize($image_url[0], $width, $height, true, true, true);
					}
				}
				$image_url[0] = !empty($image_url[0]) ? $image_url[0] : $temp_url;
				$default_media .= $image_url[0];
			}

			if (class_exists( 'RWMB_Loader' )) {
		    	switch($this->post_format) {
		            default :
		            	$media = $default_media;
		                break;
	 			}

	 		} else {
	 			$media = $default_media;
	 		}


			echo  '<div class="blog-post-hero_thumb">';

				if ($link_feature) echo '<a href="'.esc_url(get_permalink()).'" class="blog-post_feature-link">';

		    	if((bool) $media && (bool) $show_media){

					echo '<div class="blog-post_bg_media" style="background-image:url('.esc_url($media).')"'.(!empty($data_animation) ? $data_animation : "").'></div>';
	  			}

	  			if ($link_feature) echo '</a>';

  			echo '</div>';


	    }

	    public function render_featured_media ( $show_media = true ) {
	    	$media = '';

			$featured_image = Thegov_Theme_Helper::options_compare('featured_image_type', 'mb_featured_image_conditional', 'custom');

			if (class_exists( 'RWMB_Loader' )) {
		    	switch($this->post_format) {
		            case 'quote' :
		                $media = $this->featured_quote();
		                break;
		            case 'link' :
		                $media = $this->featured_link();
		                break;
		            case 'audio' :
		                $media = $this->featured_audio();
		                break;
		            case 'video' :
		                $media = $this->videobox_hero();
		                break;
	 			}

	 		}

	    	if((bool) $media && (bool) $show_media){
	    		if($featured_image != 'off'){
					if (class_exists( 'RWMB_Loader' )) {
				    	switch($this->post_format) {
				            case 'audio' :
				            case 'quote' :
				            case 'link' :
				            case 'video' :
				            	echo '<div class="blog-post-featured_media">';
				            		echo Thegov_Theme_Helper::render_html($media);
				            	echo '</div>';
				            	break;

			 			}
			 		}
	    		}
	    	}
	    }


	    public function featured_image ( $link_feature, $image_size, $aq_image = false ) {
			$output = $featured_image_replace = '';

			$featured_image = Thegov_Theme_Helper::options_compare('featured_image_type', 'mb_featured_image_conditional', 'custom');
			if ($featured_image == 'replace') {
				$featured_image_replace = Thegov_Theme_Helper::options_compare('featured_image_replace', 'mb_featured_image_conditional', 'custom');
			}

			if ($featured_image != 'off' || !is_single()) {
				if(has_post_thumbnail() || !empty($featured_image_replace)) {

					if (!empty($featured_image_replace) && is_single()) {
						if (rwmb_meta('mb_featured_image_conditional') == 'custom') {
							$image_id = array_values($featured_image_replace);
							$image_id = $image_id[0]['ID'];
						} else{
							$image_id = $featured_image_replace['id'];
						}
					} else{
						$image_id = get_post_thumbnail_id();
					}

					$image_data = wp_get_attachment_metadata($image_id);
					$image_meta = isset($image_data['image_meta']) ? $image_data['image_meta'] : array();
					$upload_dir = wp_upload_dir();
					$width = '1170';
					$height = '725';
					$image_url = wp_get_attachment_image_src( $image_id, $image_size, false );
					$temp_url = $image_url[0];

					if((bool) $aq_image){
						$arr = $this->image_size_render($image_size);
						extract($arr);

						if(function_exists('aq_resize')){
							$image_url[0] = aq_resize($image_url[0], $width, $height, true, true, true);
						}
					}
					$image_url[0] = !empty($image_url[0]) ? $image_url[0] : $temp_url;

					$image_meta['title'] = isset($image_meta['title']) ? $image_meta['title'] : "";

					if($image_url[0]){
						if ($link_feature) $output .= '<a href="'.esc_url(get_permalink()).'" class="blog-post_feature-link">';

						$output .= "<img src='" . esc_url( $image_url[0] ) . "' alt='" . esc_attr($image_meta['title']) . "' />";

						if ($link_feature) $output .= '</a>';

						$this->post_format = 'standard-image';
					}
				}
			}

	    	return $output;
	    }

	    public function featured_video ($image_size, $aq_image) {
	    	$output = '';
	    	$video_style = rwmb_meta('post_format_video_style');
	    	if(is_single() && $video_style != 'bg_video'){
	    		$output .= rwmb_meta('post_format_video_url', 'type=oembed');
	    	}else{

	    		$video_link = get_post_meta($this->post_id, 'post_format_video_url');
	    		$video_start = get_post_meta($this->post_id, 'start_video');
	    		$video_end = get_post_meta($this->post_id, 'end_video');

	    		if($video_style == 'bg_video'){
	    			wp_enqueue_script('jarallax', get_template_directory_uri() . '/js/jarallax.min.js', array(), false, false);
	    			wp_enqueue_script('jarallax-video', get_template_directory_uri() . '/js/jarallax-video.min.js', array(), false, false);
	    			$class = 'parallax-video';
	    			$attr = '';
	    			if(isset($video_link[0])){
			    		$attr   = ' data-video="' . esc_url($video_link[0]) . '"';
			    	}
			    	if(isset($video_start[0])){
			    		$attr  .= ' data-start="' . esc_attr((int)$video_start[0]) . '"';
			    	}
			    	if(isset($video_end[0])){
			    		$attr  .= ' data-end="' . esc_attr((int)$video_end[0]) . '"';
			    	}

			    	$output .= '<div class="'.esc_attr($class).'"'.$attr.'>';
			    	if(!is_single()){
				    	$output .= '<a href="'.esc_url(get_permalink()).'" class="blog-post_feature-link">';
				    	$output .= '</a>';
			    	}
	    			$output .= '</div>';
	    		}else{
			    	if(has_post_thumbnail()) {
			    		$image_id = get_post_thumbnail_id();
			    		$image_data = wp_get_attachment_metadata($image_id);
			    		$image_meta = isset($image_data['image_meta']) ? $image_data['image_meta'] : array();
			    		$upload_dir = wp_upload_dir();
			    		$width = '1170';
						$height = '725';
						$image_url = wp_get_attachment_image_src( $image_id, $image_size, false );

			    		if((bool) $aq_image){
					    	$arr = $this->image_size_render($image_size);
							extract($arr);
					    	if(function_exists('aq_resize')){
					    		$image_url[0] = aq_resize($image_url[0], $width, $height, true, true, true);
					    	}
			    		}

			    		if(!(bool) $image_url[0]){
			    			return;
			    		}

			    		$uniqrel = uniqid();

			    		$output .= '<div class="wgl-video_popup button_align-center animation_circles with_image always-run-animation">';

			    		$output .= '<div class="videobox_content">';
				    		$title = isset($image_meta['title']) ? $image_meta['title'] : '';
				    		$output .= '<div class="videobox_background">';
				    			$output .= "<img src='" . esc_url( $image_url[0] ) . "' alt='" . esc_attr($title) . "' />";
				    		$output .= '</div>';

			    		if(isset($video_link[0])){
							$lightbox_url = self::get_embed_url($video_link[0], [], []);

							$lightbox_options = [
								'type' => 'video',
								'url' => $lightbox_url,
								'modalOptions' => [
									'id' => 'elementor-lightbox-' . uniqid(),
								],
							];

							unset($this->render_attributes);

							$this->add_render_attribute( 'video-lightbox', [
								'class' => 'videobox_link videobox',
								'data-elementor-open-lightbox' => 'yes',
								'data-elementor-lightbox' =>  wp_json_encode( $lightbox_options ),
							] );
			    			$output .= '<div class="videobox_link_wrapper">';
			    			$output .= '<div ' . $this->get_render_attribute_string( 'video-lightbox' ) . '>';
			    			$output .= '<svg class="videobox_icon" width="31%" height="31%" fill="#ffffff" viewBox="0 0 10 10"><polygon points="1,0 1,10 8.5,5"/></svg><div class="videobox_animation circle_1" style="border-color: #ffffff;"></div><div class="videobox_animation circle_2" style="border-color: #ffffff;"></div><div class="videobox_animation circle_3" style="border-color: #ffffff;"></div>';
							$output .= '</div>';
							$output .= '</div>';
						}

							$output .= '</div>';
						$output .= '</div>';
			    	}
			    	else{
			    		$output .= rwmb_meta('post_format_video_url', 'type=oembed');
			    	}
	    		}

	    	}

	    	return $output;
	    }

	    public function featured_gallery ($link_feature, $image_size) {
	    	$output = '';
	    	$gallery_data = rwmb_meta('post_format_gallery');

	    	// If data are empty out of the function
	    	if (empty($gallery_data)) return;

	    	$arr = $this->image_size_render($image_size);
			extract($arr);
			$class = 'blog-post_media-slider_slick wgl-carousel_slick fade_slick';
			$class_module = ' wgl-carousel';
			ob_start();
			?>
            <div class="slider-wrapper theme-default<?php echo esc_attr($class_module);?>">
                <div class="<?php echo esc_attr($class);?>">
				<?php
		    	foreach ($gallery_data as $image) {
		    		echo '<div class="item_slick">';
		    			echo '<span>';
		    				$image_src = aq_resize( $image["full_url"], $width, $height, true, true, true);
		    				$img = !empty($image_src) ? $image_src : $image["full_url"];
		    				$alt = isset($image["alt"]) ? $image["alt"] : '';
							echo "<img src='" . esc_url($img) . "' alt='" . esc_attr($alt) . "' />";
						echo '</span>';
					echo '</div>';
					wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.min.js', array(), false, false);
	            }
	            ?>
	            </div>
	        </div>
		    <?php
		    $output = ob_get_clean();

	    	return $output;
	    }

	    public function featured_quote () {
	    	$quote_author = rwmb_meta('post_format_qoute_name');
	    	$quote_author_pos = rwmb_meta('post_format_qoute_position');
            $quote_author_image = rwmb_meta('post_format_qoute_avatar');

            if (!empty($quote_author_image)) {
                $quote_author_image = array_values($quote_author_image);
                $quote_author_image = $quote_author_image[0];
                $quote_author_image = $quote_author_image['url'];
            }else{
                $quote_author_image = '';
            }

            $quote_text = rwmb_meta('post_format_qoute_text');

            // render Quote text
            ob_start();
            if (strlen($quote_text)) :
           	?>
           		<h4 class="blog-post_quote-text"><?php echo esc_html($quote_text);?></h4>
			<?php
           	endif;
           	$output = ob_get_clean();

           	// render Author Image
           	ob_start();
           	if (!empty($quote_author_image)) {
           		?>
           		<img src="<?php echo esc_url($quote_author_image);?>"  class="blog-post_quote-image" alt="<?php echo esc_attr($quote_author);?>">
           		<?php
           	}
           	$autor_avatar = ob_get_clean(); // Get Author image

           	ob_start();
           	// Render basic quote container
           	if (strlen($quote_author)) :
           	?>
           		<div class="blog-post_quote-author">
           			<?php
           			echo Thegov_Theme_Helper::render_html($autor_avatar);
           			echo esc_html($quote_author);
           			if(!empty($quote_author_pos)){
           				echo ', <span class="blog-post_quote-author-pos">' .esc_html($quote_author_pos) . '</span>';
           			}

           			?>
           		</div>
			<?php
           	endif;

           	$output .= ob_get_clean(); // Get Quote HTML

           	return $output;
	    }

	    public function featured_link () {
	    	$link = rwmb_meta('post_format_link_url');
            $link_text = rwmb_meta('post_format_link_text');

            ob_start();
	    	?>
			<h4 class="blog-post_link">
				<?php
				if ( !empty($link) ) {
					echo '<a class="link_post" href="' . esc_url($link) . '">';
				}else{
					echo '<span class="link_post">';
				}

				if ( !empty($link_text) ) {
					echo esc_attr($link_text);
				} else {
					echo esc_attr($link);
				}

				if ( !empty($link) ) {
					echo '</a>';
				}else{
					echo '</span>';
				}

				?>
			</h4>
            <?php
            $output = ob_get_clean();

            return $output;
	    }

	    public function image_size_render($image_size){
	    	$arr = array();
	    	switch ($image_size) {
	    		case 'thegov-840-620':
	    		$arr['width'] = '840';
	    		$arr['height'] = '620';
	    		break;
	    		case 'thegov-740-560':
	    		$arr['width'] = '740';
	    		$arr['height'] = '560';
	    		break;
	    		case 'thegov-700-550':
	    		$arr['width'] = '700';
	    		$arr['height'] = '550';
	    		break;
	    		case 'thegov-440-440':
	    		$arr['width'] = '440';
	    		$arr['height'] = '440';
	    		break;
	    		case 'thegov-420-300':
	    		$arr['width'] = '420';
	    		$arr['height'] = '300';
	    		break;
	    		case 'thegov-180-180':
	    		$arr['width'] = '180';
	    		$arr['height'] = '180';
	    		break;
	    		default:
	    		$arr['width'] = '1170';
	    		$arr['height'] = '725';
	    		break;
	    	}
	    	return $arr;
	    }

	    public function image_size_render_bg($image_size){
	    	$arr = array();
	    	switch ($image_size) {
	    		case 'thegov-840-620':
	    		$arr['width'] = '840';
	    		$arr['height'] = '620';
	    		break;
	    		case 'thegov-740-560':
	    		$arr['width'] = '740';
	    		$arr['height'] = '560';
	    		break;
	    		case 'thegov-700-550':
	    		$arr['width'] = '700';
	    		$arr['height'] = '550';
	    		break;
	    		case 'thegov-740-830':
	    		$arr['width'] = '740';
	    		$arr['height'] = '830';
	    		break;
	    		default:
	    		$arr['width'] = '1170';
	    		$arr['height'] = '725';
	    		break;
	    	}
	    	return $arr;
	    }

	    public function featured_audio () {
	    	$output = '';
            $audio_meta = get_post_meta($this->post_id, 'post_format_audio_url');

            if ( !empty($audio_meta) ) {
            	$audio_embed = rwmb_meta('post_format_audio_url', 'type=oembed');
                $output = $audio_embed;
            }

            return $output;
	    }

	    public function render_post_meta ($args = false) {
	    	$this->set_post_meta($args);
	    	if($this->show_author_meta || $this->show_comments_meta || $this->show_date_meta || $this->show_category_meta){
		    	?>
		    	<div class="meta-wrapper">
					<?php if($this->show_author_meta) : ?>
						<span class="author_post">
						<?php echo get_avatar( get_the_author_meta( 'ID' ) );?>
						<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html(get_the_author_meta('display_name')); ?></a></span>
					<?php endif; ?>

					<?php if($this->show_date_meta) : ?>
						<span class="date_post"><?php echo esc_html(get_the_time(get_option( 'date_format' ))); ?></span>
					<?php endif; ?>

					<?php if($this->show_category_meta) : ?>
						<?php $this->render_post_cats();?>
					<?php endif; ?>

					<?php if($this->show_comments_meta) :
						$comments_num = '' . get_comments_number($this->post_id) . '';
					?>
						<span class="comments_post"><a href="<?php echo esc_url(get_comments_link()); ?>"><?php echo esc_html(get_comments_number($this->post_id)); ?></a></span>
					<?php endif; ?>

				</div>
				<?php
			}
	    }

	    public function render_post_cats () {
	    	?>
			<?php
			echo '<span class="blog-post_meta-categories">';
			$categories = $post_category_compile = '';
			if (get_the_category()) $categories = get_the_category();
			if ($categories) {
				$post_categ = '';
				foreach ($categories as $category) {
					$color = get_term_meta( $category->term_id, '_category_color', true );
					echo '<span'.(!empty($color) ? ' style="color:#'.esc_attr($color).';"' : "" ).'><a href="'. esc_url(get_category_link($category->term_id)).'">' . $category->cat_name . '</a></span>';
				}
			}
			echo '</span>';
	    }

	    public function get_excerpt(){
	    	ob_start();
			if (has_excerpt()) {
				the_excerpt();
			}
			return ob_get_clean();
	    }

	    public function render_excerpt ($symbol_count = false, $shortcode = false, $read_more = false, $read_more_text = false) {

	    	ob_start();
			if (has_excerpt()) {
				the_excerpt();
			} else {
				the_content();
			}
			$post_content = ob_get_clean();

			if ( in_array( $this->post_format, array('audio', 'quote', 'link') ) && !(bool)$symbol_count ) {
				$symbol_count = '185';
			} elseif(!(bool)$symbol_count) {
				$symbol_count = '400';
			}

			if ((bool)Thegov_Theme_Helper::get_option('blog_post_listing_content') || $shortcode) {
				$post_content = preg_replace( '~\[[^\]]+\]~', '', $post_content);
				$post_content_stripe_tags = strip_tags($post_content);
				$output = Thegov_Theme_Helper::modifier_character($post_content_stripe_tags, $symbol_count, "...");
			} else {
				$output = $post_content;
			}

			echo '<div class="blog-post_text"><p>'.$output.'</p></div>';
	    }

	    public function get_post_views($postID){
		    $count_key = 'post_views_count';
		    $count = get_post_meta($postID, $count_key, true);

			echo '<div class="wgl-views">';
		    if($count==''){
		        delete_post_meta($postID, $count_key);
		        add_post_meta($postID, $count_key, '0');
		        echo '<span class="counts">'. esc_html(0). '</span>';
		    	echo '<span class="counts_text">'. esc_html__(' View', 'thegov'). '</span>';
		    }else{
				echo '<span class="counts">'. esc_html($count). '</span>';
		    	echo '<span class="counts_text">'. esc_html__(' Views', 'thegov'). '</span>';
		    }
			echo '</div>';

		}

		public function set_post_views($postID) {
			if( !current_user_can('administrator') ) {
		        $user_ip = function_exists('wgl_get_ip') ? wgl_get_ip() : '0.0.0.0';
		        $key = $user_ip . 'x' . $postID;
		        $value = array($user_ip, $postID);
		        $visited = get_transient($key);

		        //check to see if the Post ID/IP ($key) address is currently stored as a transient
		        if ( false === ( $visited ) ) {

		            //store the unique key, Post ID & IP address for 12 hours if it does not exist
		           set_transient( $key, $value, 60*60*12 );

		            $count_key = 'post_views_count';
		            $count = get_post_meta($postID, $count_key, true);
		            if($count==''){
		                $count = 0;
		                delete_post_meta($postID, $count_key);
		                add_post_meta($postID, $count_key, '0');
		            }else{
		                $count++;
		                update_post_meta($postID, $count_key, $count);
		            }
		        }
		    }
		}

	    public function render_author_info () {
	    	$user_email = get_the_author_meta('user_email');
			$user_avatar = get_avatar( $user_email, 120 );
			$user_first = get_the_author_meta('first_name');
			$user_last = get_the_author_meta('last_name');
			$user_description = get_the_author_meta('description');
			$user_instagram = get_the_author_meta('instagram');
			$user_facebook = get_the_author_meta('facebook');
			$user_linkedin = get_the_author_meta('linkedin');
			$user_twitter = get_the_author_meta('twitter');

			$avatar_html = !empty($user_avatar) ? '<div class="author-info_avatar">'.$user_avatar.'</div>' : '';
			$name_html = !empty($user_first) || !empty($user_last) ? '<h5 class="author-info_name">'.'<span class="author-excerpt_name">'.esc_html__('About', 'thegov').'</span>'.$user_first.' '.$user_last.'</h5>' : '';

			$description = !empty($user_description) ? '<div class="author-info_description">'.$user_description.'</div>' : '';

			$social_array = array(
				'twitter' => get_the_author_meta('twitter'),
				'facebook' => get_the_author_meta('facebook'),
				'linkedin' => get_the_author_meta('linkedin'),
				'instagram' => get_the_author_meta('instagram'),
			);
			$social_html = '';
			foreach ($social_array as $key => $value) {
				$social_html .= !empty($value) ? '<a href="'.esc_url($value).'" class="author-info_social-link fa fa-'.esc_attr($key).'"></a>' : '';
			}
			$social_html = !empty($social_html) ? '<div class="author-info_social-wrapper">'.'<span class="title_soc_share">'. $social_html.'</span></div>' : '';

			if ( $name_html && $description ) {
				echo '<div class="author-info_wrapper clearfix">'.$avatar_html.'<div class="author-info_content">'.$name_html.$description.$social_html.'</div></div>';
			}

	    }

		/**
		 * Get embed URL.
		 *
		 * Retrieve the embed URL for a given video.
		 *
		 * @param string $video_url        Video URL.
		 * @param array  $embed_url_params Optional. Embed parameters. Default is an
		 *                                 empty array.
		 * @param array  $options          Optional. Embed options. Default is an
		 *                                 empty array.
		 */
		public static function get_embed_url($video_url, array $embed_url_params = [], array $options = [])
		{
			$video_properties = self::get_video_properties($video_url);

			if (!$video_properties) {
				return null;
			}

			$embed_pattern = self::$embed_patterns[$video_properties['provider']];

			$replacements = [
				'{VIDEO_ID}' => $video_properties['video_id'],
			];

			if ('youtube' === $video_properties['provider']) {
				$replacements['{NO_COOKIE}'] = !empty($options['privacy']) ? '-nocookie' : '';
			} elseif ('vimeo' === $video_properties['provider']) {
				$time_text = '';

				if (!empty($options['start'])) {
					$time_text = date('H\hi\ms\s', $options['start']); // PHPCS:Ignore WordPress.DateTime.RestrictedFunctions.date_date
				}

				$replacements['{TIME}'] = $time_text;
			}

			$embed_pattern = str_replace(array_keys($replacements), $replacements, $embed_pattern);
			return add_query_arg($embed_url_params, $embed_pattern);
		}

		/**
		 * Get video properties.
		 *
		 * Retrieve the video properties for a given video URL.
		 *
		 * @param string $video_url Video URL.
		 *
		 * @return null|array The video properties, or null.
		 */
		public static function get_video_properties($video_url)
		{
			foreach (self::$provider_match_masks as $provider => $match_mask) {
				preg_match($match_mask, $video_url, $matches);

				if ($matches) {
					return [
						'provider' => $provider,
						'video_id' => $matches[1],
					];
				}
			}

			return null;
		}

		public function add_render_attribute($element, $key = null, $value = null, $overwrite = false)
		{
			if (is_array($element)) {
				foreach ($element as $element_key => $attributes) {
					$this->add_render_attribute($element_key, $attributes, null, $overwrite);
				}

				return $this;
			}

			if (is_array($key)) {
				foreach ($key as $attribute_key => $attributes) {
					$this->add_render_attribute($element, $attribute_key, $attributes, $overwrite);
				}

				return $this;
			}

			if (empty($this->render_attributes[$element][$key])) {
				$this->render_attributes[$element][$key] = [];
			}

			settype($value, 'array');

			if ($overwrite) {
				$this->render_attributes[$element][$key] = $value;
			} else {
				$this->render_attributes[$element][$key] = array_merge($this->render_attributes[$element][$key], $value);
			}

			return $this;
		}

		public function get_render_attribute_string($element)
		{
			if (empty($this->render_attributes[$element])) {
				return '';
			}

			return ' ' . \Thegov_Theme_Helper::render_html_attributes($this->render_attributes[$element]);
		}
	}

}