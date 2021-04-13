<?php
/*
* @package     WBC_Importer - Extension for Importing demo content
* @author      Webcreations907
* @version     1.0
*/

// function for adding menu and rev slider to demo content
if ( !function_exists( 'wbc_extended_example' ) ) {
     function wbc_extended_example( $demo_active_import , $demo_directory_path ) {

        reset( $demo_active_import );
        $current_key = key( $demo_active_import );

        /************************************************************************
        * Import slider(s) for the current demo being imported
        *************************************************************************/
        if ( class_exists( 'RevSlider' ) ) {
          $wbc_sliders_array = array(
              'demo' => array(
                '1' => 'home-1.zip',
                '2' => 'home-2.zip',
              ) //Set slider zip name 
           );
           if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_sliders_array ) ) {
              $wbc_slider_import = $wbc_sliders_array[$demo_active_import[$current_key]['directory']];
              if (is_array ( $wbc_slider_import )) {
                $sliders = array();
                foreach ( $wbc_slider_import as $key => $value ) {
                  if ( file_exists( $demo_directory_path.$value ) ) {
                    $slider[$key] = new RevSlider();
                    $slider[$key]->importSliderFromPost( true, true, $demo_directory_path.$value );
                  }
                }
              } else {
                if ( file_exists( $demo_directory_path.$wbc_slider_import ) ) {
                   $slider = new RevSlider();
                   $slider->importSliderFromPost( true, true, $demo_directory_path.$wbc_slider_import );
                }
              }
           }
        }
        /************************************************************************
        * Setting Menus
        *************************************************************************/
        // If it's demo1 - demo6
        $wbc_menu_array = array(
           'demo' => 'main' 
        );

        if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_menu_array ) ) {
           $top_menu = get_term_by( 'name', $wbc_menu_array[$demo_active_import[$current_key]['directory']], 'nav_menu' );
           if ( isset( $top_menu->term_id ) ) {
              set_theme_mod( 'nav_menu_locations', array(
                    'main_menu' => $top_menu->term_id,
                 )
              );
           }
        }
        /************************************************************************
        * Set HomePage
        *************************************************************************/
        // array of demos/homepages to check/select from
        $wbc_home_pages = array(
           'demo' => 'Home 1',
        );
        if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_home_pages ) ) {
           $page = get_page_by_title( $wbc_home_pages[$demo_active_import[$current_key]['directory']] );
           if ( isset( $page->ID ) ) {
              update_option( 'page_on_front', $page->ID );
              update_option( 'show_on_front', 'page' );
           }
        }

        $cpt_support = get_option( 'elementor_cpt_support' );
    
        if( ! $cpt_support ) {
            $cpt_support = [ 'page', 'post', 'portfolio', 'team', 'footer', 'side_panel', 'event', 'location' ];
            update_option( 'elementor_cpt_support', $cpt_support );
        }
        
        else if( ! in_array( 'portfolio', $cpt_support ) ) {
            $cpt_support[] = 'portfolio'; 
            update_option( 'elementor_cpt_support', $cpt_support ); 
        }  
        else if( ! in_array( 'team', $cpt_support ) ) {
            $cpt_support[] = 'team'; 
            update_option( 'elementor_cpt_support', $cpt_support ); 
        }  
        else if( ! in_array( 'footer', $cpt_support ) ) {
            $cpt_support[] = 'footer'; 
            update_option( 'elementor_cpt_support', $cpt_support ); 
        }  
        else if( ! in_array( 'side_panel', $cpt_support ) ) {
            $cpt_support[] = 'side_panel'; 
            update_option( 'elementor_cpt_support', $cpt_support ); 
        }        
        else if( ! in_array( 'event', $cpt_support ) ) {
            $cpt_support[] = 'event'; 
            update_option( 'elementor_cpt_support', $cpt_support ); 
        }        
        else if( ! in_array( 'location', $cpt_support ) ) {
            $cpt_support[] = 'location'; 
            update_option( 'elementor_cpt_support', $cpt_support ); 
        }
    
        //Add Wgl Default Container Width 
        update_option( 'elementor_container_width', 1170 );

        //all the options Events Manager
        $dbem_options = array(
          //Event Formatting
          'dbem_date_format' => 'F j, Y',
          'dbem_event_list_item_format_header' => '<div class="wgl-events-list">',
          'dbem_event_list_item_format' => '<div class="wgl-events__list">
            <div class="wgl-events__wrapper{has_image} featured_image{/has_image}">
            <div class="wgl-events__date">
            <span class="day">#_{d}</span>
            <span class="month">#_{F}</span>
            </div>
            <div class="wgl-events__content">
            #_EVENTCATEGORIES
            <h3 class="event-title">#_EVENTLINK</h3>
            {has_location}<div class="event-location"><i class="event-icon fa fa-map-marker"></i>#_LOCATIONNAME, #_LOCATIONTOWN #_LOCATIONSTATE</div>{/has_location}
            <a class="button-read-more" href="#_EVENTURL">'.esc_html__('READ MORE', 'thegov-core').'</a>
            </div>
            {has_image}<div class="wgl-events__bg" style="background-image: url(#_EVENTIMAGEURL)"></div>{/has_image}
            </div>
            </div>',
          'dbem_event_list_item_format_footer' => '</div>',
          'dbem_single_event_format' => '<div class="event-single_wrapper row">
            <div class="event-single_content wgl_col-4">
                   <i class="icon-event flaticon-clock"></i>
              <h6>'.esc_html__('Event Date:', 'thegov-core').'</h6>
              '.esc_html__('Start at', 'thegov-core').' #_12HSTARTTIME<br />
            #_EVENTDATES
            </div>
            {has_location}
            <div class="event-single_content wgl_col-4">
            <i class="icon-event flaticon-meeting-point"></i>
              <h6>'.esc_html__('Location', 'thegov-core').'</h6>
              #_LOCATIONLINK
            </div>
            {/has_location}
            <div class="event-single_content wgl_col-4">
            <i class="icon-event flaticon-paper-plane"></i>
              <h6>'.esc_html__('E-Mail', 'thegov-core').'</h6>
              <span>city@vox.com.ua</br>thegov@gmail.com</span>
            </div>
            </div>
            {has_bookings}
            <h3>'.esc_html__('Bookings', 'thegov-core').'</h3>
            #_BOOKINGFORM
            {/has_bookings}',
          'dbem_search_form_dates_separator' => '-',
        );

        //add new options
        foreach($dbem_options as $key => $value){
          update_option($key, $value);
        }

        global $wpdb;
        $wpdb->query(
          $wpdb->prepare(
            "
            UPDATE $wpdb->posts
            SET post_status = '%s'
            WHERE post_type = 'event'
            ",
            'publish'
          )
        );

     }
     
     // Uncomment the below
     add_action( 'wbc_importer_after_content_import', 'wbc_extended_example', 10, 2 );

  }
?>
