<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Thegov
 * @since 1.0
 * @version 1.0
 */

use WglAddons\Templates\WglEvents; 
 
get_header();
the_post();

$sb = Thegov_Theme_Helper::render_sidebars('events_single');

$single_type = Thegov_Theme_Helper::get_option('events_single_type_layout'); 
if(empty($single_type)){
	$single_type = 2;
}

if (class_exists( 'RWMB_Loader' )) {
	$mb_type = rwmb_meta('mb_post_layout_conditional');
	if(!empty($mb_type) && $mb_type != 'default' ){
		$single_type = rwmb_meta('mb_single_type_layout');
	}
}

// Allowed HTML render
$allowed_html = array(
    'a' => array(
        'href' => true,
        'title' => true,
    ),
    'br' => array(),
    'b' => array(),
    'em' => array(),
    'strong' => array()
); 

$args = array(
	'status' 	=> '1',
	'scope' 	=> 'future', // Only upcoming events.
	'orderby' 	=> 'event_start_date',
	'blog' 		=> 'all', // Only applicable for MultiSite.
);

$column = $sb['column'];
$row_class = $sb['row_class'];
$container_class = $sb['container_class'];
$layout = $sb['layout'];

$row_class .= ' single_type-'.$single_type;

if($single_type === '3'){
	echo '<div class="post_featured_bg">';
		get_template_part('templates/events/single/post', $single_type.'_image');
	echo '</div>';
}
?>

<div class="wgl-container<?php echo apply_filters('thegov_container_class', $container_class); ?>">
        <div class="row<?php echo apply_filters('thegov_row_class', $row_class); ?>">
			<div id='main-content' class="wgl_col-<?php echo apply_filters('thegov_column_class', $column); ?>">
				<?php
					get_template_part('templates/events/single/post', $single_type);
					
					$EM_Events 		= EM_Events::get($args, $count = false);
					$found_ids 		= array();
					foreach( $EM_Events as $EM_Event ) {
						$found_ids[] = $EM_Event->post_id;
					}
					$current_post	= get_the_ID();
					$current_key 	= array_search($current_post, $found_ids);
					$next_key 		= $current_key + 1;
					$next_post 		= $found_ids[$next_key];
					$prev_key 		= $current_key - 1;
					$prev_post 		= ($prev_key > -1) ? $found_ids[$prev_key] : '';
					
					if ($current_key != 0 || $current_key != (count( (array) $found_ids) -1)):
						?>
						<div class="thegov-post-navigation">
							<?php
							if($current_key != 0){							
								$image_prev_url = wp_get_attachment_image_src(get_post_thumbnail_id($prev_post), 'thumbnail');
				
								$img_prev_html = '';
								$class_image_prev = isset($image_prev_url[0]) && !empty($image_prev_url[0]) ? ' image_exist' : ' no_image';
								$img_prev_html .= "<span class='image_prev". esc_attr($class_image_prev)."'>";
								if(isset($image_prev_url[0]) && !empty($image_prev_url[0])){
									$img_prev_html .= "<img src='" . esc_url( $image_prev_url[0] ) . "' alt='".esc_attr(get_the_title($prev_post)) ."'/>";
								}else{
									$img_prev_html .= "<span class='no_image_post'></span>";
								}
								$img_prev_html .= "</span>";
				
								echo '<div class="prev-link_wrapper">';
									echo '<div class="info_prev-link_wrapper"><a href="' . esc_url(get_permalink($prev_post)) . '" title="' . esc_attr(get_the_title($prev_post)) . '">'.$img_prev_html.'<span class="prev-link-info_wrapper"><span class="prev_title">'.wp_kses( get_the_title($prev_post), $allowed_html ).'</span><span class="meta-wrapper"><span class="date_post">'.esc_html(get_the_time(get_option( 'date_format' ), $prev_post)).'</span></span></span></a></div>';
								echo '</div>';
							}
							
							if($current_key != (count( (array) $found_ids) -1)) {
								$image_next_url = wp_get_attachment_image_src(get_post_thumbnail_id($next_post), 'thumbnail');
				
								$img_next_html = '';
								$class_image_next = isset($image_next_url[0]) && !empty($image_next_url[0]) ? ' image_exist' : ' no_image';
								$img_next_html .= "<span class='image_next".esc_attr($class_image_next)."'>";
								if(isset($image_next_url[0]) && !empty($image_next_url[0])){
									$img_next_html .= "<img src='" . esc_url( $image_next_url[0] ) . "' alt='". esc_attr(get_the_title($next_post)) ."'/>";
								}else{
									$img_next_html .= "<span class='no_image_post'></span>";
								}
								$img_next_html .= "</span>";
								echo '<div class="next-link_wrapper">';
								echo '<div class="info_next-link_wrapper"><a href="' . esc_url(get_permalink($next_post)) . '" title="' . esc_attr(get_the_title($next_post)) . '"><span class="next-link-info_wrapper"><span class="next_title">'.wp_kses(get_the_title($next_post), $allowed_html ) .'</span><span class="meta-wrapper"><span class="date_post">'.esc_html(get_the_time(get_option( 'date_format' ), $next_post)).'</span></span></span>'.$img_next_html.'</a></div>';
								echo '</div>';
							}
				
							echo '<a class="back-nav_page" href="#" onclick="location.href = document.referrer; return false;">';
								echo '<span></span>';
								echo '<span></span>';
								echo '<span></span>';
								echo '<span></span>';
							echo '</a>';	
							?>
						</div>
						<?php
					endif;

					$show_post_related = Thegov_Theme_Helper::get_option('single_events_related_posts');


					if (class_exists( 'RWMB_Loader' )) {
						$mb_events_show_r = rwmb_meta('mb_events_show_r');
						if(!empty($mb_events_show_r) && $mb_events_show_r != 'default' ){
							$show_post_related = $mb_events_show_r === 'off' ? null : $mb_events_show_r;
						}
					}
		
					if ( (bool)$show_post_related && class_exists('Thegov_Core') && class_exists('\Elementor\Plugin')) : ?>
						<?php
	 
						$mb_events_carousel_r = $mb_events_column_r = $mb_events_number_r = $mb_events_title_r ='';

						$mb_events_carousel_r 	  = Thegov_Theme_Helper::options_compare('events_carousel_r', 'mb_events_show_r', 'custom');
						$mb_events_title_r 	  	  = Thegov_Theme_Helper::options_compare('events_title_r', 'mb_events_show_r', 'custom');
						
						$mb_events_column_r 	  	  = Thegov_Theme_Helper::options_compare('events_column_r', 'mb_events_show_r', 'custom');
						$mb_events_number_r 	      = Thegov_Theme_Helper::options_compare('events_number_r', 'mb_events_show_r', 'custom');
			    
						?>

						<div class='single related_posts'>
						<?php

							$mb_events_cat_r = Thegov_Theme_Helper::get_option('events_cat_r');

							if (class_exists( 'RWMB_Loader' )) {
								$related_cats = rwmb_meta('mb_events_show_r');      
								
								if($related_cats === 'custom'){
									$mb_events_cat_r[0] = get_post_meta(get_the_id(), 'mb_events_cat_r');	
								}
							}

							$cats = get_the_terms( get_the_id(), 'event-categories' );
							$cats = $cats ? $cats : array(); 
							$cat_slugs = array();
							foreach( $cats as $cat ){
								$cat_slugs[] = 'event-categories:'.$cat->slug;
							}
							
							if(!empty($mb_events_cat_r[0])){
								$cat_slugs = array();
								$list = get_terms( 'event-categories', array( 'include' => $mb_events_cat_r[0]  ) );
								foreach ($list as $key => $value) { 
									$cat_slugs[] = 'event-categories:'.$value->slug;
								}		
							}

							$mb_events_cat_r = $cat_slugs;

							echo '<div class="thegov_module_title"><h4>'.(!empty($mb_events_title_r) ? esc_html($mb_events_title_r) : esc_html__('Recent Events', 'thegov')) .' </h4></div>';
 
							$atts = array();
							$atts['events_navigation'] 		= 'none';
							$atts['use_navigation']  		= null;
							$atts['events_layout']     		= !empty($mb_events_carousel_r) ? 'carousel' : 'grid';
							$atts['hide_share']      		= true;
							$atts['hide_content']    		= true;
							$atts['hide_likes']      		= true;
							$atts['meta_author']      		= true;
							$atts['meta_comments']      	= true;
							$atts['read_more_hide'] 		= false;
							$atts['read_more_text'] 		= esc_html__('Read More', 'thegov');
							$atts['heading_tag'] 			= 'h4';
							$atts['content_letter_count'] 	= 130;
							$atts['crop_square_img'] 		= 1;
							$atts['items_load'] 			= 4;
							$atts['name_load_more'] 		= esc_html__('Load More','thegov');
							$atts['events_columns'] 			= !empty($mb_events_column_r) ? $mb_events_column_r : (($layout == "none") ? "4" : "6"); 
							$atts['autoplay'] 				= null; 
							$atts['autoplay_speed'] 		= 3000; 
							$atts['use_pagination'] 		= null; 
							$atts['pag_type'] 				= 'circle'; 
							$atts['pag_offset'] 			= ''; 
							$atts['custom_resp'] 			= true; 
							$atts['resp_medium'] 			= null; 
							$atts['pag_color'] 				= null; 
							$atts['custom_pag_color'] 		= null; 
							$atts['resp_tablets_slides'] 	= null; 
							$atts['resp_tablets'] 			= null; 
							$atts['resp_medium_slides'] 	= null; 
							$atts['resp_mobile'] 			= '767'; 
							$atts['resp_mobile_slides'] 	= '1'; 
							$atts['number_of_posts'] 		= (int) $mb_events_number_r; 
							$atts['taxonomies'] 	    	= $mb_events_cat_r; 
							$atts['order_by'] 	    		= 'rand'; 
							$atts['show_filter'] 	    	= null; 

							$related_items = new WglEvents();
	        				echo Thegov_Theme_Helper::render_html($related_items->render($atts));
							?>
						</div>
						<?php
					endif;
					if (comments_open() || get_comments_number()) {?>
						<div class="row">
							<div class="wgl_col-12">
								<?php comments_template(); ?>
							</div>
						</div>
					<?php } ?>
			</div>	
			<?php
				echo (isset($sb['content']) && !empty($sb['content']) ) ? $sb['content'] : '';
			?>
		</div>

</div>

<?php
	get_footer();
?>