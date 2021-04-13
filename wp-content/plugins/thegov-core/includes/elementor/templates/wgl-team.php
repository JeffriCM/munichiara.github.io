<?php
namespace WglAddons\Templates;

use Elementor\Plugin;
use Elementor\Frontend;
use WglAddons\Includes\Wgl_Loop_Settings;
use WglAddons\Includes\Wgl_Elementor_Helper;
use WglAddons\Includes\Wgl_Carousel_Settings;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
* Wgl Elementor Team Render
*
*
* @class        WglTeam
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/

class WglTeam{

    private static $instance = null;
    public static function get_instance( ) {
        if ( null == self::$instance ) {
            self::$instance = new self( );
        }

        return self::$instance;
    }

    public function render($atts, $content = null){
        $theme_color = esc_attr(\Thegov_Theme_Helper::get_option('theme-custom-color'));
        $theme_color_secondary = esc_attr(\Thegov_Theme_Helper::get_option('theme-secondary-color'));
        $header_font = esc_attr(\Thegov_Theme_Helper::get_option('header-font')['color']);

        $carousel_options = array();
        extract($atts);

        if ((bool)$use_carousel) {
            // carousel options array
            $carousel_options = array(
                'slide_to_show' => $posts_per_line,
                'autoplay' => $autoplay,
                'autoplay_speed' => $autoplay_speed,
                'use_pagination' => $use_pagination,
                'pag_type' => $pag_type,
                'pag_offset' => $pag_offset,
                'custom_pag_color' => $custom_pag_color,
                'pag_color' => $pag_color,
                'use_prev_next' => $use_prev_next,

                'prev_next_position' => $prev_next_position,
                'custom_prev_next_color' => $custom_prev_next_color,
                'prev_next_color' => $prev_next_color,
                'prev_next_color_hover' => $prev_next_color_hover,
                'prev_next_bg_idle' => $prev_next_bg_idle,
                'prev_next_bg_hover' => $prev_next_bg_hover,

                'custom_resp' => $custom_resp,
                'resp_medium' => $resp_medium,
                'resp_medium_slides' => $resp_medium_slides,
                'resp_tablets' => $resp_tablets,
                'resp_tablets_slides' => $resp_tablets_slides,
                'resp_mobile' => $resp_mobile,
                'resp_mobile_slides' => $resp_mobile_slides,
                'infinite' => $infinite,
                'slides_to_scroll' => $slides_to_scroll,
                'center_mode'   => $center_mode,
            );

            wp_enqueue_script('slick', get_template_directory_uri() . '/js/slick.min.js', array(), false, false);
        }

        $team_classes = '';


        $team_classes .= 'team-col_'.$posts_per_line;
        $team_classes .= ' a'.$info_align;

        ob_start();
            $this->render_wgl_team($atts);
        $team_items = ob_get_clean();
        ob_start();
        ?>

        <div class="wgl_module_team <?php echo esc_attr($team_classes); ?>">
            <div class="team-items_wrap">
                <?php
                switch ((bool)$use_carousel) {
                    case true:
                        echo Wgl_Carousel_Settings::init($carousel_options, $team_items, false);
                    break;
                    default: echo \Thegov_Theme_Helper::render_html($team_items); break;
                }
                ?>
            </div>
        </div>

        <?php
        $render = ob_get_clean();
        return $render;
    }

    public function render_wgl_team($atts){

        extract($atts);

        $counter = 0;
        $compile = $item_classes = '';

        // Dimensions for team images
        switch ($posts_per_line) {
            default:
            case '1':
            case '2': $team_image_dims = array('width' => '860', 'height' => '1000'); break; // ratio = 0.7733
            case '3': $team_image_dims = array('width' => '720', 'height' => '780');  break;
            case '4':
            case '5':
            case '6': $team_image_dims = array('width' => '540', 'height' => '600');  break;
        }

        list($query_args) = Wgl_Loop_Settings::buildQuery($atts);
        $query_args['post_type'] = 'team';
        $wgl_posts = new \WP_Query($query_args);
        if ($wgl_posts->have_posts()):
            while ($wgl_posts->have_posts()):
                $wgl_posts -> the_post();
                $counter++;
                $compile .= $this->render_wgl_team_item( false, $atts, $team_image_dims, $counter );
            endwhile;
            wp_reset_postdata();
        endif;

        echo $compile;
    }

    public function render_wgl_team_item ( $single_member = false, $item_atts, $team_image_dims, $counter ) {

        extract($item_atts);
        $compile = $team_cats = $team_info = $team_icons = $featured_image = $team_title = $team_wrapper = $featured_image_single = $item_classes = "";
        $wgl_pid = get_the_ID();
        $post = get_post( $wgl_pid );
        $link_to = get_permalink($wgl_pid);
        $department = get_post_meta($wgl_pid, "department", true);
        $info_array = get_post_meta($wgl_pid, "info_items", true);
        $social_array = get_post_meta($wgl_pid, "soc_icon", true);
        $info_bg_id = get_post_meta($wgl_pid, "mb_info_bg", true);
        $info_bg_url = wp_get_attachment_url($info_bg_id, 'full');
        $wp_get_attachment_url = wp_get_attachment_url(get_post_thumbnail_id($wgl_pid), 'single-post-thumbnail');

        // Team info
        if (isset($info_array) && !empty($info_array)) {
            for ( $i=0; $i<count( $info_array ); $i++ ){
                $info = $info_array[$i];
                $info_name = !empty($info['name']) ? $info['name'] : '';
                $info_description = !empty($info['description']) ? $info['description'] : '';
                $info_link = !empty($info['link']) ? $info['link'] : '';

                if ((bool)$single_member && (!empty($info_name) || !empty($info_description))) {
                    $team_info .= '<div class="team-info_item">';
                        $team_info .= !empty($info_name) ? '<h5>'.esc_html($info_name).'</h5>' : '';
                        $team_info .= !empty($info_link) ? '<a href="'.esc_url($info_link).'">' : '';
                            $team_info .= '<span>'.esc_html($info_description).'</span>';
                        $team_info .= !empty($info_link) ? '</a>' : '';
                    $team_info .= '</div>';
                }
            }
        }

        // Team social icons
        if ( isset($social_array) ) {
            for ( $i=0; $i<count( $social_array ); $i++ ) {
                $icon = $social_array[$i];
                $icon_name = !empty($icon['select']) ? $icon['select'] : '';
                $icon_link = !empty($icon['link']) ? $icon['link'] : '#';
                if ( !empty($icon['select']) ) {
                    $team_icons .= '<a href="'.$icon_link.'" class="team-icon '.$icon_name.'"></a>';
                }
            }
        }

        if(!empty($team_icons)){
            $team_icons_wrap  = '<div class="team-info_icons">';
                if (!(bool)$single_member) {
                    $team_icons_wrap .= '<span class="team-icon fa fa-share-alt"></span>';
                }
                $team_icons_wrap .= $team_icons;
            $team_icons_wrap .= '</div>';
        }
        // Team image
        if (!empty($wp_get_attachment_url)) {
            $wgl_featured_image_url = ($posts_per_line == '1') ? $wp_get_attachment_url : aq_resize($wp_get_attachment_url, $team_image_dims['width'], $team_image_dims['height'], true, true, true);

            $img_alt = get_post_meta(get_post_thumbnail_id($wgl_pid), '_wp_attachment_image_alt', true);
            $featured_image .= ((bool)$single_link_wrapper && !(bool)$single_member) ? '<a href="'.esc_url($link_to).'">' : '';
                $featured_image .= '<img src="'.esc_url($wgl_featured_image_url).'" alt="'.(!empty($img_alt) ? $img_alt : '').'" />';
            $featured_image .= ((bool)$single_link_wrapper && !(bool)$single_member) ? '</a>' : '';
        }

        // Team single link heading
        if (!(bool)$hide_title) {
            $team_title .= '<h2 class="team-title">';
                $team_title .= ((bool)$single_link_heading && !(bool)$single_member) ? '<a href="'.esc_url($link_to).'">' : '';
                    $team_title .= get_the_title();
                $team_title .= ((bool)$single_link_heading && !(bool)$single_member) ? '</a>' : '';
            $team_title .= '</h2>';
        }

        // Render team excerpt
        if ((bool)$single_member) {
            $letter_count = 300;
        }
        $excerpt = !empty( $post->post_excerpt ) ? $post->post_excerpt : $post->post_content;
        $excerpt = preg_replace( '~\[[^\]]+\]~', '', $excerpt);
        // $excerpt_stripe_tags = strip_tags($excerpt);
        $excerpt = \Thegov_Theme_Helper::modifier_character($excerpt, $letter_count, "");

        // Item classes
        $item_classes .= (bool)$grayscale_anim ? ' grayscale_image' : '';
        $item_classes .= (bool)$info_anim ? ' hide_info' : '';

        $item_footer_image = wp_get_attachment_image_src($info_bg_id, 'full')[0] ?? '';
        // Render team list & team single
        if (!(bool)$single_member) {

            $compile .= '<div class="team-item'.(!empty($item_classes) ? $item_classes : '').'">';
                $compile .= '<div class="team-item_wrap">';
                    $compile .= '<div class="team-item_content">';
                        $compile .= '<div class="team-image">';
                            $compile .= $featured_image;
                        $compile .= '</div>';
                    $compile .= '</div>';
                    if (!(bool)$hide_title || !(bool)$hide_department || !(bool)$hide_soc_icons || !(bool)$hide_content) {
                        $compile .= '<div class="team-item_info">';
                            $compile .= !(bool)$hide_soc_icons ? $team_icons_wrap : '';
                            $compile .= '<div class="team-item_titles">';

                                $compile .= $team_title;

                                if((!empty($department) && !(bool)$hide_department)){
                                    $compile .= '<div class="team-meta_info">';
                                }

                                $compile .= (!empty($department) && !(bool)$hide_department) ? '<div class="team-department">'.esc_html($department).'</div>' : '';

                                if((!empty($department) && !(bool)$hide_department)){
                                    $compile .= '</div>';
                                }

                                if (!empty($item_footer_image)){
                                    $compile .= '<img src="'.esc_url($item_footer_image).'" alt="'.esc_html('signature','thegov-core').'">';
                                }

                            $compile .= '</div>';
                            $compile .= !(bool)$hide_content ? '<div class="team-item_excerpt">'.$excerpt.'</div>' : '';
                        $compile .= '</div>';
                    }
                $compile .= '</div>';
            $compile .= '</div>';
        } else {
            $compile .= '<div class="team-single_wrapper">';
                $compile .= '<div class="team-image_wrap">';
                    $compile .= '<div class="team-image">';
                        $compile .= $featured_image;
                    $compile .= '</div>';
                $compile .= '</div>';
                $compile .= '<div class="team-info_wrapper" '.(!empty($info_bg_url) ? 'style="background-image:url('.$info_bg_url.')"' : '').'>';
                    $compile .= $team_title;
                    $compile .= !empty($department) ? '<div class="team-info_item team-department">'.'<span>'.esc_html($department).'</span></div>' : '';
                    $compile .= !empty($excerpt) ? '<div class="team-info_excerpt">'.$excerpt.'</div>' : '';
                    $compile .= !empty($team_info) ? $team_info : '';
                    $compile .= $team_icons_wrap;
                $compile .= '</div>';
            $compile .= '</div>';

        }

        return $compile;
    }

}