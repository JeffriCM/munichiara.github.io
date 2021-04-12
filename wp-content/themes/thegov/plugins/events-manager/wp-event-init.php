<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
/**
* Thegov WP Event
*
*
* @class        Thegov_Event
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/

if (!class_exists('Thegov_Event')) {
	class Thegov_Event{

		/**
	     * @var Thegov_Event
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
	    private $show_location_meta = true;

	    /**
	     * @return Thegov_Event
	     */
	    public static function getInstance () {
	        if (null === static::$instance)
	        {
	            static::$instance = new static();
	        }
	        return static::$instance;
	    }

	    private function __construct () {
	    	$this->post_id = get_the_ID();   
			//add_filter('em_event_output_single', array( $this, 'output_single' ));
	    }

	    public function set_post_meta ( $args = false ) {
	    	if ( !(bool)$args || empty($args) ) {
	    		$this->show_date_meta = true;
    			$this->show_author_meta = true;
    			$this->show_comments_meta = true;
    			$this->show_category_meta = true;
    			$this->show_location_meta = true;
	    	} else {
	    		$this->show_date_meta = isset($args['date']) ? $args['date'] : "";
    			$this->show_author_meta = isset($args['author']) ? $args['author'] : "";
    			$this->show_comments_meta = isset($args['comments']) ? $args['comments'] : "";
    			$this->show_category_meta = isset($args['category']) ? $args['category'] : "";    			
    			$this->show_location_meta = isset($args['location']) ? $args['location'] : "";    			
	    	}	
	    }  

	    public function set_data_image_hero ($link_feature = false, $image_size = 'full', $aq_image = false ) {
	    	
	    	$media = false;
	 		$this->render_bg_image = true;
	 		$media = $this->featured_image($link_feature, $image_size, $aq_image);
	 		
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

	 		
	 		$media = $this->featured_image($link_feature, $image_size, $aq_image);
	 		
	 		$class_media_part = '';

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

	   	public function hero_render_bg ( $link_feature = false, $image_size = 'full',$aq_image = false, $data_animation = null, $show_media = true ) {
	    	$media = '';
			
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

	 		$media = $default_media;
	    	
	    	
			echo  '<div class="events-post-hero_thumb">';
				
				if ($link_feature) echo '<a href="'.esc_url(get_permalink()).'" class="events-post_feature-link">';
					
		    	if((bool) $media && (bool) $show_media){
					
					echo '<div class="events-post_bg_media" style="background-image:url('.esc_url($media).')"'.(!empty($data_animation) ? $data_animation : "").'></div>';		 					
	  			}
	  				
	  			if ($link_feature) echo '</a>';			
	  			
  			echo '</div>';			
	    }

	    public function single_render_bg ( $link_feature = false, $image_size = 'full',$aq_image = false, $data_animation = null, $show_media = true ) {
	    	$media = '';

			$featured_image = Thegov_Theme_Helper::options_compare('featured_image_type', 'mb_featured_image_conditional', 'custom');	

			if ($featured_image == 'replace') {
				$featured_image_replace = Thegov_Theme_Helper::options_compare('featured_image_replace', 'mb_featured_image_conditional', 'custom');	
			}


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


	    	if ($link_feature) echo '<a href="'.esc_url(get_permalink()).'" class="blog-post_feature-link">';

	    	if((bool) $media && (bool) $show_media){
	    		if($featured_image != 'off'){
	    			echo '<div class="blog-post_bg_media" style="background-image:url('.esc_url($media).')"'.(!empty($data_animation) ? $data_animation : "").'></div>';	
	    		}	    			
	    	}

	    	if ($link_feature) echo '</a>';		
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

	    public function render_post_meta ($args = false) {
	    	$this->set_post_meta($args);
	    	if( $this->show_author_meta || $this->show_comments_meta || $this->show_date_meta ){
		    	
		    	?>
		    	<div class="meta-wrapper"> 		
					<?php if($this->show_author_meta) : ?>
						<span class="author_post">
						<?php echo get_avatar( get_the_author_meta( 'ID' ) );?>
						<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html(get_the_author_meta('display_name')); ?></a></span>
					<?php endif; ?> 

					<?php if($this->show_date_meta) : 
						
						global $EM_Event;
						if(function_exists('em_get_event')):
							global $post;
							$event = em_get_event( $post );	   
							$date_format = get_option('dbem_date_format') ? get_option('dbem_date_format') : get_option('date_format');		 		
						?>
						<span class="date_post"><?php echo esc_html($event->start()->i18n($date_format)); ?></span>
						
					<?php 
							endif; 
						endif; 
					?>		


					
					<?php if($this->show_comments_meta) :
						$comments_num = '' . get_comments_number($this->post_id) . '';
					?>
						<span class="comments_post"><a href="<?php echo esc_url(get_comments_link()); ?>"><?php echo esc_html(get_comments_number($this->post_id)); ?></a></span>
					<?php endif; ?>	

				</div>
				<?php	
			
			}				 
			
			if( $this->show_category_meta || $this->show_location_meta ){
				
				if($this->show_category_meta) : ?>
					<?php $this->render_post_cats();?>
				<?php endif;				

				if($this->show_location_meta) : ?>
					<?php $this->render_post_location();?>
				<?php endif;
			
			}
	    }	    

	    public function render_post_location () {
	    	global $EM_Event;
	    	if(function_exists('em_get_event')){
				global $post;
				$event = em_get_event( $post );	 
		    	if( get_option('dbem_locations_enabled') ): 
		    		
		    		$content = $event->output("#_LOCATION");
		    		
		    		if(!empty($content)){
				    	?>
				    	<div class='events-post_location'>
				    		<span><?php echo Thegov_Theme_Helper::render_html($content); ?></span>  
				    	</div>	
				    	<?php	    			
		    		}

		    	endif; 	    		
	    	}

	    }	    

	    public function render_post_cats () {
            $post_cats = wp_get_post_terms(get_the_id(), 'event-categories');
            if(!empty($post_cats)){
	            $post_cats_str = '';
	            $post_cats_class = '';
	            $post_cats_links = '<div class="events-post_cats">';
	            $post_cats_links .= '<span class="events-post_meta-categories">';
	            for ($i=0; $i<count( $post_cats ); $i++) {
	                $post_cat_term = $post_cats[$i];
	                $post_cat_name = $post_cat_term->name;
	                $post_cats_str .= ' '.$post_cat_name;
	                $post_cats_class .= ' '.$post_cat_term->slug;
	                $post_cats_link = get_category_link( $post_cat_term->term_id );
	                $post_cats_links .= '<span>';
	                $post_cats_links .= '<a href='.esc_html($post_cats_link).' class="event-category">'.esc_html($post_cat_name).'</a>';
	                $post_cats_links .= '</span>';
	            }
	            $post_cats_links .= '</span>';
	            $post_cats_links .= '</div>';
	            echo Thegov_Theme_Helper::render_html($post_cats_links);            	
            }
	    }	    

	    public function get_excerpt(){
	    	ob_start();
			if (has_excerpt()) {
				the_excerpt();
			} 
			return ob_get_clean();
	    }
	    
	    public function render_excerpt ($symbol_count = false, $shortcode = false, $read_more = false, $read_more_text = false) {


			if(!(bool)$symbol_count) {
				$symbol_count = '400';
			}

	    	global $EM_Event;
	    	if(function_exists('em_get_event')){
	    		if ( $shortcode) {
			    	$event = em_get_event( get_the_id() );
			    	$content = $event->output("#_EVENTEXCERPT");
					
					if(!empty($content)){
					    $content = preg_replace( '~\[[^\]]+\]~', '', $content);
						$post_content_stripe_tags = strip_tags($content);
						$content = Thegov_Theme_Helper::modifier_character($post_content_stripe_tags, $symbol_count, "...");

			    		echo '<div class="events-post_text">'.$content.'</div>';    			
			    	}	 
		    	
		    	}   		
	    	}

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


	}
}

?>
