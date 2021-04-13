<?php

class Author extends WP_Widget
{
private $isMultilingual = FALSE; //Is this site multilingual?
    
    function __construct() 
    {
        parent::__construct(
            'combined_image_author_widget', // Base ID
            esc_html__( 'WGL Author Said', 'thegov-core' ), // Name
            array( 'description' => esc_html__( 'WGL Widget ', 'thegov-core' ), ) // Args
        );

        //If WPML is active and was setup to have more than one language this website is multilingual.
        
        if ( is_admin() === TRUE ) {
            add_action('admin_enqueue_scripts', array($this, 'enqueue_backend_scripts') );
        }
    }


    public function enqueue_backend_scripts()
    {
        wp_enqueue_media(); //Enable the WP media uploader
        wp_enqueue_script('thegov-upload-img', get_template_directory_uri() . '/core/admin/js/img_upload.js', array('jquery'), false, true);
    }
    

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) 
    {
        $html = '';
        $title_name = 'title';
        $author_name = 'name';
        $text_name = 'text';
        $image_name = 'image';
        $image_signature = isset($instance['signature']) && !empty($instance['signature']) ? $instance['signature'] : '';
        $background_image = isset($instance['bg']) && !empty($instance['bg']) ? $instance['bg'] : '';

        $attachment_id = attachment_url_to_postid ($instance[$image_name]);
        $alt = '';
        // if no alt attribute is filled out then echo "Featured Image of article: Article Name"
        if ('' === get_post_meta($attachment_id, '_wp_attachment_image_alt', true)) {
            $alt = the_title_attribute(array('before' => esc_html__('Featured author image: ', 'thegov-core'), 'echo' => false));
        } else {
            $alt = trim(strip_tags(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)));
        }

        $widgetImg = ( (isset($instance[$image_name])) && (!empty($instance[$image_name])) )? '<img class="author-widget_img" src="' . esc_url(aq_resize($instance[$image_name], "300", "300", true, true, true)) . '" alt="'.esc_attr($alt).'">' :'';        

        //Get Image Signature
        $attachment_id_s = attachment_url_to_postid ($image_signature);
        $alt_s = '';
        // if no alt attribute is filled out then echo "Featured Image of article: Article Name"
        if ('' === get_post_meta($attachment_id_s, '_wp_attachment_image_alt', true)) {
            $alt_s = the_title_attribute(array('before' => esc_html__('Featured author signature: ', 'thegov-core'), 'echo' => false));
        } else {
            $alt_s = trim(strip_tags(get_post_meta($attachment_id_s, '_wp_attachment_image_alt', true)));
        }

        $widgetImgSign = ( $image_signature )? '<img class="author-widget_sign" src="' . esc_url($image_signature) . '" alt="'.esc_attr($alt_s).'">' :'';
        
        $title = ( (isset($instance[$title_name])) && (!empty($instance[$title_name])) )? $instance[$title_name]:FALSE; 
        $author_name = ( (isset($instance[$author_name])) && (!empty($instance[$author_name])) )? $instance[$author_name]:FALSE; 
        $text = ( (isset($instance[$text_name])) && (!empty($instance[$text_name])) )? $instance[$text_name] : '';

        $facebook = ( (isset( $instance['facebook'])) && (!empty($instance['facebook'])) )? $instance['facebook'] : '';
        $twitter = ( (isset( $instance['twitter'])) && (!empty($instance['twitter'])) )? $instance['twitter'] : '';
        $linkedin = ( (isset( $instance['linkedin'])) && (!empty($instance['linkedin'])) )? $instance['linkedin'] : '';
        $instagram = ( (isset( $instance['instagram'])) && (!empty($instance['instagram'])) )? $instance['instagram'] : '';
        
        $widgetClasses = 'thegov_author-widget';

        $widgetClasses.= ' widget thegov_widget';

        $bg_image = !empty($background_image) ? 'background-image: url('.esc_url($background_image).');' : '';

        $html.= '<div class="' . esc_attr($widgetClasses) . '">';
        
        if ( !empty($title) ) $html.= '<h3 class="widget-title">'.esc_html($title).'</h3>';
        $html.= '<div class="author-widget_wrapper"'.(!empty($bg_image) ? ' style="'.esc_attr($bg_image).'"' : '').'>';
                if(!empty($widgetImg)){
                    $html.= '<div class="author-widget_img-wrapper">' . $widgetImg . '</div>';              
                }
                
                if ( !empty($author_name) ) $html .= '<h4 class="author-widget_title">' . esc_html($author_name) . '</h4>';



                if ( !empty($text) ) $html .= '<p class="author-widget_text">' . esc_html($text) . '</p>';

                if(!empty($facebook) || !empty($twitter) || !empty($linkedin) || !empty($instagram)){
                    $html.= '<div class="author-widget_social">';
                }    
                    
                    if ( !empty($twitter) ) $html.= '<a class="author-widget_social-link fa fa-twitter" href="'.esc_url($twitter).'"></a>';
                    
                    if ( !empty($facebook) ) $html.= '<a class="author-widget_social-link fa fa-facebook" href="'.esc_url($facebook).'"></a>';

                    if ( !empty($linkedin) ) $html.= '<a class="author-widget_social-link fa fa-linkedin" href="'.esc_url($linkedin).'"></a>';                    

                    if ( !empty($instagram) ) $html.= '<a class="author-widget_social-link fa fa-instagram" href="'.esc_url($instagram).'"></a>';

                    
                if(!empty($facebook) || !empty($twitter) || !empty($linkedin) || !empty($instagram)){
                    $html.= '</div>';
                }

                if(!empty($widgetImgSign)){
                    $html.= '<div class="author-widget_img_sign-wrapper">' . $widgetImgSign;
                    $html.= '</div>';
                }
                
            $html.= '</div>';

        $html.= '</div>';

        echo Thegov_Theme_Helper::render_html($html);
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        $title_name = 'title';
        $title = ( (isset($instance[$title_name])) && (!empty( $instance[$title_name])) )? $instance[$title_name] : '';
        $author_name = 'name';
        $name = ( (isset($instance[$author_name])) && (!empty( $instance[$author_name])) )? $instance[$author_name] : '';
        
        $text_name = 'text';
        $text = ( (isset($instance[$text_name])) && (!empty($instance[$text_name])) )? $instance[$text_name] : '';

        $image_name = 'image';
        $image = ( (isset($instance[$image_name])) && (!empty($instance[$image_name])) )? $instance[$image_name] : '';
        
        $image_signature = 'signature';
        $signature = ( (isset($instance[$image_signature])) && (!empty($instance[$image_signature])) )? $instance[$image_signature] : '';        

        $bg_image = 'bg';
        $bg = ( (isset($instance[$bg_image])) && (!empty($instance[$bg_image])) )? $instance[$bg_image] : '';

        $facebook = ( (isset( $instance['facebook'])) && (!empty($instance['facebook'])) )? $instance['facebook'] : '';
        $twitter = ( (isset( $instance['twitter'])) && (!empty($instance['twitter'])) )? $instance['twitter'] : '';
        $linkedin = ( (isset( $instance['linkedin'])) && (!empty($instance['linkedin'])) )? $instance['linkedin'] : '';
        
        $instagram = ( (isset( $instance['instagram'])) && (!empty($instance['instagram'])) )? $instance['instagram'] : '';

        ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( $title_name ) ); ?>"><?php esc_html_e( 'Title:', 'thegov-core' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr(  $this->get_field_id( $title_name ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $title_name ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( $author_name ) ); ?>"><?php esc_html_e( 'Author Name:', 'thegov-core' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr(  $this->get_field_id( $author_name ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $author_name ) ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( $text_name ) ); ?>"><?php esc_html_e( 'Text:', 'thegov-core' ); ?></label> 
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( $text_name ) ); ?>" name="<?php echo esc_attr(  $this->get_field_name( $text_name ) ); ?>" row="2"><?php echo esc_html( $text ); ?></textarea>
        </p>

        <p>
          <label for="<?php echo esc_attr( $this->get_field_id($image_name) ); ?>"><?php esc_html_e( 'Author Image:', 'thegov-core' ); ?></label><br />
            <img class="thegov_media_image" src="<?php if(!empty($instance[$image_name])){echo esc_url( $instance[$image_name] );} ?>" style="max-width: 100%" />
            <input type="text" class="widefat thegov_media_url" name="<?php echo esc_attr( $this->get_field_name($image_name) ); ?>" id="<?php echo esc_attr( $this->get_field_id($image_name) ); ?>" value="<?php echo esc_attr( $image ); ?>">
            <a href="#" class="button thegov_media_upload"><?php esc_html_e('Upload', 'thegov-core'); ?></a>
        </p>        

        <p>
          <label for="<?php echo esc_attr( $this->get_field_id($bg_image) ); ?>"><?php esc_html_e( 'Background Image:', 'thegov-core' ); ?></label><br />
            <img class="thegov_media_image" src="<?php if(!empty($instance[$bg_image])){echo esc_url( $instance[$bg_image] );} ?>" style="max-width: 100%" />
            <input type="text" class="widefat thegov_media_url" name="<?php echo esc_attr( $this->get_field_name($bg_image) ); ?>" id="<?php echo esc_attr( $this->get_field_id($bg_image) ); ?>" value="<?php echo esc_attr( $bg ); ?>">
            <a href="#" class="button thegov_media_upload"><?php esc_html_e('Upload', 'thegov-core'); ?></a>
        </p>        

        <p>
          <label for="<?php echo esc_attr( $this->get_field_id($image_signature) ); ?>"><?php esc_html_e( 'Author Signature:', 'thegov-core' ); ?></label><br />
            <img class="thegov_media_image" src="<?php if(!empty($instance[$image_signature])){echo esc_url( $instance[$image_signature] );} ?>" style="max-width: 100%" />
            <input type="text" class="widefat thegov_media_url" name="<?php echo esc_attr( $this->get_field_name($image_signature) ); ?>" id="<?php echo esc_attr( $this->get_field_id($image_signature) ); ?>" value="<?php echo esc_attr( $signature ); ?>">
            <a href="#" class="button thegov_media_upload"><?php esc_html_e('Upload', 'thegov-core'); ?></a>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Facebook:', 'thegov-core' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="text" value="<?php echo esc_attr( $facebook ); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Twitter:', 'thegov-core' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="text" value="<?php echo esc_attr( $twitter ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'Linkedin:', 'thegov-core' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="text" value="<?php echo esc_attr( $linkedin ); ?>">
        </p>        

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_html_e( 'Instagram:', 'thegov-core' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="text" value="<?php echo esc_attr( $instagram ); ?>">
        </p>
        <?php

    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) 
    {
        return $new_instance;
    }
}

function author_register_widgets() {
    register_widget('author');
}

add_action('widgets_init', 'author_register_widgets');

?>