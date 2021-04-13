<?php 
if( !class_exists('Thegov_Theme_Helper') ) { return; }

use WglAddons\Templates\WglPortfolio;

get_header();

$sb = Thegov_Theme_Helper::render_sidebars('portfolio_single');
$row_class = $sb['row_class'];
$column = $sb['column'];
$container_class = $sb['container_class'];

$defaults = array(
	'posts_per_row' => '1',
	'portfolio_layout' => '',
	'portfolio_icon_pack' => '',
);

// HTML allowed for rendering
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

$single_post_type = Thegov_Theme_Helper::options_compare('portfolio_single_type_layout','mb_portfolio_post_conditional','custom');
$single_post_align = Thegov_Theme_Helper::options_compare('portfolio_single_align','mb_portfolio_post_conditional','custom');
$item = new WglPortfolio();

echo '<div class="wgl-portfolio-single_wrapper single_type-'.esc_attr($single_post_type).'">';
if ($single_post_type == '3' || $single_post_type == '4') {
	while ( have_posts() ):
		the_post();
		echo $item->wgl_portfolio_single_fw();
	endwhile;
		wp_reset_postdata();
} 

?>
<div class="wgl-container single_portfolio<?php echo apply_filters('thegov_container_class', $container_class); ?>">
	<div class="row<?php echo apply_filters('thegov_row_class', $row_class); ?>">
		<div id='main-content' class="wgl_col-<?php echo apply_filters('thegov_column_class', $column); ?>">
			<?php
				while ( have_posts() ):
				the_post();
				echo $item->wgl_portfolio_single_item($defaults, $item_class = '');
				endwhile;
				wp_reset_postdata();

				$previousPost = get_adjacent_post(false, '', true);
				$nextPost  = get_adjacent_post(false, '', false);

				if ($nextPost || $previousPost):
					?>
					<div class="thegov-post-navigation">
						<?php
						if(is_a( $previousPost, 'WP_Post' )){							
							$image_prev_url = wp_get_attachment_image_src(get_post_thumbnail_id($previousPost->ID), 'thumbnail');

							$img_prev_html = '';
							$class_image_prev = isset($image_prev_url[0]) && !empty($image_prev_url[0]) ? ' image_exist' : ' no_image';
							$img_prev_html .= "<span class='image_prev". esc_attr($class_image_prev)."'>";
							if(isset($image_prev_url[0]) && !empty($image_prev_url[0])){
								$img_prev_html .= "<img src='" . esc_url( $image_prev_url[0] ) . "' alt='".esc_attr( $previousPost->post_title) ."'/>";
							}else{
								$img_prev_html .= "<span class='no_image_post'></span>";
							}
							$img_prev_html .= "</span>";

							echo '<div class="prev-link_wrapper">';
								echo '<div class="info_prev-link_wrapper"><a href="' . esc_url(get_permalink($previousPost->ID)) . '" title="' . esc_attr($previousPost->post_title) . '">'.$img_prev_html.'<span class="prev-link-info_wrapper"><span class="prev_title">'.wp_kses( $previousPost->post_title, $allowed_html ).'</span><span class="meta-wrapper"><span class="date_post">'.esc_html(get_the_time(get_option( 'date_format' ), $previousPost->ID)).'</span></span></span></a></div>';
							echo '</div>';
						}
						if(is_a( $nextPost, 'WP_Post' )) {
							$image_next_url = wp_get_attachment_image_src(get_post_thumbnail_id($nextPost->ID), 'thumbnail');

							$img_next_html = '';
							$class_image_next = isset($image_next_url[0]) && !empty($image_next_url[0]) ? ' image_exist' : ' no_image';
							$img_next_html .= "<span class='image_next".esc_attr($class_image_next)."'>";
							if(isset($image_next_url[0]) && !empty($image_next_url[0])){
								$img_next_html .= "<img src='" . esc_url( $image_next_url[0] ) . "' alt='". esc_attr( $nextPost->post_title ) ."'/>";
							}else{
								$img_next_html .= "<span class='no_image_post'></span>";
							}
							$img_next_html .= "</span>";
							echo '<div class="next-link_wrapper">';
							echo '<div class="info_next-link_wrapper"><a href="' . esc_url(get_permalink($nextPost->ID)) . '" title="' . esc_attr( $nextPost->post_title ) . '"><span class="next-link-info_wrapper"><span class="next_title">'.wp_kses( $nextPost->post_title, $allowed_html ) .'</span><span class="meta-wrapper"><span class="date_post">'.esc_html(get_the_time(get_option( 'date_format' ), $nextPost->ID)).'</span></span></span>'.$img_next_html.'</a></div>';
							echo '</div>';
						}
						if(is_a( $previousPost, 'WP_Post' ) || is_a( $nextPost, 'WP_Post' )){
							echo '<a class="back-nav_page" href="#" onclick="location.href = document.referrer; return false;">';
								echo '<span></span>';
								echo '<span></span>';
								echo '<span></span>';
								echo '<span></span>';
							echo '</a>';
						}	
						?>
					</div>
					<?php
				endif;

			$related_switch = Thegov_Theme_Helper::get_option('portfolio_related_switch');
			if (class_exists( 'RWMB_Loader' )) {
	            $mb_related_switch = rwmb_meta('mb_portfolio_related_switch');      
	            if ($mb_related_switch == 'on') {
	                $related_switch = true;
	            }elseif ($mb_related_switch == 'off') {
	                $related_switch = false;
	            }
	        } 
			
			if ( (bool)$related_switch && class_exists('Thegov_Core') && class_exists('Elementor\Plugin')) :
				$mb_pf_cat_r = array();
				
				$mb_pf_carousel_r = Thegov_Theme_Helper::options_compare('pf_carousel_r', 'mb_portfolio_related_switch', 'on');
				$mb_pf_title_r = Thegov_Theme_Helper::options_compare('pf_title_r', 'mb_portfolio_related_switch', 'on');
				$mb_pf_column_r = Thegov_Theme_Helper::options_compare('pf_column_r', 'mb_portfolio_related_switch', 'on');
				$mb_pf_number_r = Thegov_Theme_Helper::options_compare('pf_number_r', 'mb_portfolio_related_switch', 'on');
				$mb_pf_number_r = !empty($mb_pf_number_r) ? $mb_pf_number_r : '12';

				if (class_exists( 'RWMB_Loader' )) {
					$mb_pf_cat_r   		  = get_post_meta(get_the_id(), 'mb_pf_cat_r'); // store terms’ IDs in the post meta and doesn’t set post terms.
				}

				if (!(bool)$mb_pf_carousel_r) {
					wp_enqueue_script('isotope');
				}
				
				$cats = get_the_terms( get_the_id(), 'portfolio-category' );
				$cats = $cats ? $cats : array(); 
				$cat_slugs = array();
				foreach( $cats as $cat ){
					$cat_slugs[] = 'portfolio-category:'.$cat->slug;
				}

				
				if(!empty($mb_pf_cat_r[0])){
					$cat_slugs = array();
					$list = get_terms( 'portfolio-category', array( 'include' => $mb_pf_cat_r[0]  ) );
					foreach ($list as $key => $value) { 
						$cat_slugs[] = 'portfolio-category:'.$value->slug;
					}		
				}

				$mb_pf_cat_r = $cat_slugs;

				$atts = array(
					'add_animation' 		=> null,
					'add_divider' 			=> 'yes',
					'show_filter' 			=> null,
					'arrows_center_mode' 	=> null,
					'center_info' 			=> null,
					'use_prev_next' 		=> null,
					'center_mode' 			=> null,
					'variable_width' 		=> null,
					'navigation' 			=> null,
					'slides_to_scroll' 		=> 1,
					'pag_type' 				=> 'circle', 
					'pag_offset' 			=> '',
					'custom_resp' 			=> true, 
					'resp_medium' 			=> null, 
					'pag_color'				=> null, 
					'custom_pag_color' 		=> null,
					'resp_tablets_slides' 	=> null, 
					'resp_tablets'			=> null, 
					'resp_medium_slides' 	=> null,
					'resp_mobile'			=> '600', 
					'resp_mobile_slides'	=> '1', 
					'portfolio_layout' => 'related',
					'title' => '',
					'mb_pf_carousel_r' => $mb_pf_carousel_r,
					'info_position' => 'inside_image',
					'image_anim' => 'simple',
					'single_link_title' => 'yes',
					'show_content' => '',
					'subtitle' => '',
					'view_all_link' => '',
					'show_view_all' => 'no',
					'click_area' => 'single',
					'posts_per_row' => $mb_pf_column_r,
					'item_el_class' => '', 
					'css' => '',
					'autoplay' => true,
					'autoplay_speed' => '5000',
					'multiple_items' => true,
					'use_pagination' => false,
					'gallery_mode' => false,
					'view_style' => 'standard',
					'crop_images' => 'yes',
					'show_portfolio_title' => 'true',
					'show_meta_categories' => 'true',
					'add_overlay' => 'true',
					'custom_overlay_color' => 'rgba(34,35,40,.7)',
					'items_load' => $mb_pf_column_r,
					'grid_gap' => '30px',
					'featured_render' => '1',
					'number_of_posts' => $mb_pf_number_r,
					'order_by' => "menu_order",
					'order' => "ASC",
					'post_type' => "portfolio",
					'taxonomies' => $mb_pf_cat_r,
					'portfolio_icon_type' => 'font',
					'portfolio_icon_pack' => 'flaticon',
				);
				$featured_render = new WglPortfolio(); 

				$featured_post = $featured_render->render($atts);
				if($featured_render->post_count > 0){
					echo '<div class="related_portfolio">';
						if(!empty($mb_pf_title_r)){
							echo '<div class="thegov_module_title"><h4>' . esc_html($mb_pf_title_r) . '</h4></div>';
						}
						echo $featured_post;
					echo '</div>';
				}
			endif;
			if (comments_open() || get_comments_number()) {?>
				<div class="row">
					<div class="wgl_col-12">
						<?php comments_template('', true); ?>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
			echo (isset($sb['content']) && !empty($sb['content']) ) ? $sb['content'] : '';
		?>
	</div>
</div>
</div>

<?php

get_footer();

?>