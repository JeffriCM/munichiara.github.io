<?php
    $single = Thegov_Event::getInstance();
    $single->set_data();

    $title = get_the_title();

	$show_likes = Thegov_Theme_Helper::get_option('single_events_likes');
	$show_share = Thegov_Theme_Helper::get_option('single_events_share');
	$show_views = Thegov_Theme_Helper::get_option('single_events_views');
	$single_meta = Thegov_Theme_Helper::get_option('single_events_meta');
	$show_tags = Thegov_Theme_Helper::get_option('single_events_meta_tags');

?>

<div class="blog-post blog-post-single-item format-<?php echo esc_attr($single->get_pf()); ?>">
	<div <?php post_class("single_meta"); ?>>
		<div class="item_wrapper">
			<div class="blog-post_content">

				<?php 
								
					the_content();
					if ( did_action( 'elementor/loaded' ) ) {	
						echo \Elementor\Plugin::$instance->frontend->get_builder_content( get_the_ID() );
					}
					
					if (has_term( '', 'event-tags') || (bool)$show_share ||  (bool)$show_views) {
						echo '<div class="post_info single_post_info">';

						if ( (bool)$show_views ) echo '<div class="blog-post_meta-wrap">';
 
						if(has_term( '', 'event-tags') && !(bool) $show_tags){
							echo "<div class='tagcloud-wrapper'>";
								the_terms( '', 'event-tags', '<div class="tagcloud">','', '</div>');
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
				
				?>
			</div>
		</div>
	</div>
</div>