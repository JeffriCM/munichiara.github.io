<?php
if(!class_exists('Thegov_Theme_Helper')){
    return;
}

use WglAddons\Templates\WglPortfolio;

get_header();


$tax_obj = get_queried_object();
$term_id = $tax_obj->term_id ?? '';

$taxonomies = [];

if ($term_id) {
    $taxonomies[] = $tax_obj->taxonomy . ': ' . $tax_obj->slug;
    $tax_description = $tax_obj->description;
}

$defaults = array(
    'add_animation' => null,
    'navigation' => 'pagination',
    'nav_align' => 'center',
    'click_area' => 'single',
    'posts_per_row' => Thegov_Theme_Helper::get_option('portfolio_list_columns'),
    'show_portfolio_title' => Thegov_Theme_Helper::get_option('portfolio_list_show_title'),
    'show_content' => Thegov_Theme_Helper::get_option('portfolio_list_show_content'),
    'show_meta_categories' => Thegov_Theme_Helper::get_option('portfolio_list_show_cat'),
    'show_filter' => false,
    'crop_images' => 'yes',
    'items_load' => '4',
    'grid_gap' => '30px',
    'add_overlay' => 'true',
    'portfolio_layout' => 'masonry',
    'custom_overlay_color' => 'rgba(34,35,40,.7)',
    'number_of_posts' => '12',
    'order_by' => 'menu_order',
    'order' => 'ASC',
    'post_type' => 'portfolio',
    'taxonomies' => $taxonomies,
    'info_position' => 'inside_image',
    'image_anim' => 'simple',
    'single_link_title' => 'yes',
    'gallery_mode' => false,
    'portfolio_icon_type' => 'font',
    'portfolio_icon_pack' => 'flaticon',
);
extract($defaults);

$sb = Thegov_Theme_Helper::render_sidebars('portfolio_list');
$row_class = $sb['row_class'];
$column = $sb['column'];
$container_class = $sb['container_class'];


?>
    <div class="wgl-container<?php echo apply_filters('thegov_container_class', $container_class); ?>">
        <div class="row<?php echo apply_filters('thegov_row_class', $row_class); ?>">
            <div id='main-content' class="wgl_col-<?php echo apply_filters('thegov_column_class', $column); ?>">
                <?php
                if (! empty($term_id)) {
                    echo '<div class="portfolio_archive-cat">',
                        '<h4 class="archive__tax_title">',
                            get_the_archive_title(),
                        '</h4>',
                        (isset($tax_description) && !empty($tax_description) ? '<div class="archive__tax_description">' . esc_html($tax_description) . '</div>' : ''),
                    '</div>';
                }
                $portfolio_render = new WglPortfolio();
                echo $portfolio_render->render($defaults);
                ?>
            </div>
            <?php
                echo (isset($sb['content']) && !empty($sb['content']) ) ? $sb['content'] : '';
            ?>
        </div>
    </div>

<?php get_footer(); ?>
