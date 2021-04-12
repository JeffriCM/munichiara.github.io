<?php
global $wgl_events_atts;

// Default settings for events item
$trim = true;

extract($wgl_events_atts);

$image_size = 'thegov-740-830';

if($events_columns === '12'){
    $image_size = 'full';
}


global $wgl_query_vars;
if(!empty($wgl_query_vars)){
    $query = $wgl_query_vars;
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

$events_styles = '';

$events_attr = !empty($events_styles) ? ' style="'.esc_attr($events_styles).'"' : '';

$heading_attr = isset($heading_margin_bottom) && $heading_margin_bottom != '' ? ' style="margin-bottom: '.(int) $heading_margin_bottom.'px"' : '';

while ($query->have_posts()) : $query->the_post();          
 
    $p_cats = wp_get_post_terms(get_the_id(), 'event-categories');
    $p_cats_class = '';
    for ($i=0; $i<count( $p_cats ); $i++) {
        $p_cat_term = $p_cats[$i];
        $p_cats_class .= ' '.$p_cat_term->slug;
    }
    $p_cats_class;

    echo '<div class="wgl_col-'.esc_attr($events_columns).' item'.esc_attr($p_cats_class).'">';

    $single = Thegov_Event::getInstance();
    $single->set_data();

    $title = get_the_title();

    $events_item_classes = ' format-'.$single->get_pf();
    $events_item_classes .= (bool)$hide_media ? ' hide_media' : '';
    $events_item_classes .= is_sticky() ? ' sticky-post' : '';

    $single->set_data_image_hero(true, $image_size,$aq_image = true);
    $has_media = $single->render_bg_image;

    if((bool)$hide_media){ 
        $has_media = false;
    }
    
    $events_item_classes .= !(bool) $has_media ? ' format-no_featured' : '';

    $meta_to_show = array(
        'comments' => !(bool)$meta_comments,
        'author' => !(bool)$meta_author,
        'date' => !(bool)$meta_date,
    );    
    $meta_to_show_cats = array(
        'category' => !(bool)$meta_categories,
    );    

    $meta_to_show_location = array(
        'location' => !(bool)$meta_location,
    );

    ?>

    <div class="events-post <?php echo esc_attr($events_item_classes); ?>"<?php echo Thegov_Theme_Helper::render_html($events_attr);?>>
        <div class="events-post-hero_wrapper">
       
            <?php 
                // Media events post

            $link_feature = true;
            $single->hero_render_bg($link_feature, $image_size, $aq_image = true, false, !(bool)$hide_media);            
                            
            ?>

            <div class="events-post-hero-content_wrapper">
                <?php
                    //Post Meta render cats
                    if ( !(bool)$hide_postmeta && !empty($meta_to_show_cats) ) {
                        $single->render_post_meta($meta_to_show_cats);
                    }  
                ?>
                <div class="events-post-hero_content">
                <?php         
                    //Post Meta render comments,author
                    if ( !(bool)$hide_postmeta ) {
                        $single->render_post_meta($meta_to_show);
                    }

                    // Events Title
                    if ( !(bool)$hide_events_title && !empty($title) ) :
                        echo sprintf('<%1$s class="events-post_title"%2$s><a href="%3$s">%4$s</a></%1$s>', esc_html($heading_tag), $heading_attr, esc_url(get_permalink()), wp_kses( $title, $allowed_html ) );
                    endif;

                    // Content Events
                    if ( !(bool)$hide_content ) $single->render_excerpt($content_letter_count, $trim, !(bool)$read_more_hide, $read_more_text);
                    
                    //Post Meta render comments,author
                    if ( !(bool)$hide_postmeta ) {
                        $single->render_post_meta($meta_to_show_location);
                    }

                    ?>

                    <div class='events-post-hero_meta-desc'>  
                        <?php
                            echo "<div class='divider_post_info'></div>";
                            // Read more link
                            if ( !(bool)$read_more_hide ) :
                                ?>
                                <a href="<?php echo esc_url(get_permalink()); ?>" class="button-read-more standard_post"><?php echo esc_html($read_more_text); ?></a> 
                           <?php
                            endif;
                        ?>
                    </div>
                </div>                
            </div>
        </div>
    </div>
    <?php

    echo '</div>';

endwhile;
wp_reset_postdata();
