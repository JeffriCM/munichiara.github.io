<?php
    $single = Thegov_SinglePost::getInstance();
    $single->set_data();

    $title = get_the_title();

	$show_likes = Thegov_Theme_Helper::get_option('single_likes');
	$show_share = Thegov_Theme_Helper::get_option('single_share');
	$show_views = Thegov_Theme_Helper::get_option('single_views');
	$single_author_info = Thegov_Theme_Helper::get_option('single_author_info');
	$single_meta = Thegov_Theme_Helper::get_option('single_meta');
	$show_tags = Thegov_Theme_Helper::get_option('single_meta_tags');

?>

<div class="blog-post blog-post-single-item format-<?php echo esc_attr($single->get_pf()); ?>">
	<div <?php post_class("single_meta"); ?>>
		<div class="item_wrapper">
			<div class="blog-post_content">
				<?php 

					$pf_type = $single->get_pf();
					$video_style = function_exists("rwmb_meta") ? rwmb_meta('post_format_video_style') : '';
					if($pf_type !== 'standard-image' && $pf_type !== 'standard'){
						if($pf_type === 'video' && $video_style === 'bg_video'){
						}else{
							$single->render_featured(false, 'full' );
						}
						
					}

					the_content();

					wp_link_pages(array('before' => '<div class="page-link"><span class="pagger_info_text">' . esc_html__('Pages', 'thegov') . ': </span>', 'after' => '</div>'));

					if (has_tag() || (bool)$show_share ||  (bool)$show_views) {
						echo '<div class="post_info single_post_info">';

						if ( (bool)$show_views ) echo '<div class="blog-post_meta-wrap">';

						if(has_tag() && !(bool) $show_tags){
							echo "<div class='tagcloud-wrapper'>";
								the_tags('<div class="tagcloud">', ' ', '</div>');
							echo "</div>";						
						}

						if ( (bool)$show_views )  echo '<div class="blog-post_info-wrap">';

							// Views in blog
							if ( (bool)$show_views ) : ?>              
								<div class="blog-post_views-wrap">
									<?php
									$single->get_post_views(get_the_ID());
									?>
								</div>
								<?php
							endif;

						
						if ( (bool)$show_views ): ?> 
	                        </div>   
	                        </div>   
	                    	<?php
						endif;

						// Share in blog
						if ( (bool)$show_share && function_exists('wgl_theme_helper') ) : ?>
							<div class='divider_post_info'></div>
							<div class="blog-post_meta_share">       
								<div class="single_info-share_social-wpapper">
						  			<?php echo wgl_theme_helper()->render_post_share('yes'); ?>
								</div>   
							</div>
						<?php
						endif; 

						echo "</div>";
					}else{
						echo "<div class='divider_post_info'></div>";
					}
				
				if ( (bool)$single_author_info ) {
					$single->render_author_info();
				} 
				?>
			</div>
		</div>
	</div>
</div>