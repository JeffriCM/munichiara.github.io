<?php

use WglAddons\Templates\WglTeam;

$sb = Thegov_Theme_Helper::render_sidebars();
$row_class = $sb['row_class'];
$column = $sb['column'];
$container_class = $sb['container_class'];

$defaults = array(
    'title' => '',
    'posts_per_line' => '2',
    'grid_gap' => '',
    'info_align' => 'center',
    'single_link_wrapper' => false,
    'single_link_heading' => true,
    'hide_title' => false,
    'hide_department' => false,
    'hide_soc_icons' => false,
    'grayscale_anim' => false,
    'info_anim' => false,
);

extract($defaults);
$team_image_dims = array('width' => '1000', 'height' => '960');

get_header ();
?>

<div class="wgl-container<?php echo apply_filters('thegov_container_class', $container_class); ?>">
	<div class="row<?php echo esc_attr($row_class); ?>">
		<div id='main-content' class="wgl_col-<?php echo apply_filters('thegov_column_class', $column); ?>">
			<?php
			while ( have_posts() ):
				the_post();
			?>
				<div class="row single_team_page">
					<div class="wgl_col-12">
						<?php
						    $item = new WglTeam();
    						echo $item->render_wgl_team_item(true, $defaults, $team_image_dims, false);
						?>
					</div>
					<div class="wgl_col-12">
						<?php the_content(esc_html__( 'Read more!', 'thegov-core' )); ?>
					</div>
				</div>
			<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
		<?php echo (isset($sb['content']) && !empty($sb['content']) ) ? $sb['content'] : ''; ?>
	</div>
</div>

<?php
get_footer();
?>