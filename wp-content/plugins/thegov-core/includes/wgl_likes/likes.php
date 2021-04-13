<?php
class WglSimpleLikes{

    protected static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function thegov_already_liked( $post_id, $is_comment ) {
        $post_users = NULL;
        $user_id = NULL;
        if ( is_user_logged_in() ) { // user is logged in
            $user_id = get_current_user_id();
            $post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, '_user_comment_liked' ) : get_post_meta( $post_id, '_user_liked' );
            if ( count( $post_meta_users ) != 0 ) {
                $post_users = $post_meta_users[0];
            }
        } else { // user is anonymous
            $user_id = $this->thegov_like_get_ip();
            $post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, '_user_comment_IP' ) : get_post_meta( $post_id, '_user_IP' );
            if ( count( $post_meta_users ) != 0 ) { // meta exists, set up values
                $post_users = $post_meta_users[0];
            }
        }
        if ( is_array( $post_users ) && in_array( $user_id, $post_users ) ) {
            return true;
        } else {
            return false;
        }
    }

    public function likes_button( $post_id, $is_comment = NULL ) {
        $is_comment = ( NULL == $is_comment ) ? 0 : 1;
        $output = '';
        $nonce = wp_create_nonce( 'simple-likes-nonce' ); // Security
        if ( $is_comment == 1 ) {
            $post_id_class = esc_attr( ' sl-comment-button-' . $post_id );
            $comment_class = esc_attr( ' sl-comment' );
            $like_count = get_comment_meta( $post_id, '_comment_like_count', true );
            $like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
        } else {
            $post_id_class = esc_attr( ' sl-button-' . $post_id );
            $comment_class = esc_attr( '' );
            $like_count = get_post_meta( $post_id, '_post_like_count', true );
            $like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
        }
        $count = $this->get_like_count( $like_count );
        $icon_empty = $this->thegov_get_unliked_icon();
        $icon_full = $this->thegov_get_liked_icon();
        // Loader
        $loader = '<span class="sl-loader"></span>';
        // Liked/Unliked Variables
        if ( $this->thegov_already_liked( $post_id, $is_comment ) ) {
            $class = esc_attr( ' liked' );
            $title = esc_html__( 'Unlike', 'thegov-core' );
            $icon = $icon_full;
        } else {
            $class = '';
            $title = esc_html__( 'Like', 'thegov-core' );
            $icon = $icon_empty;
        }
        $output = '<div class="sl-wrapper wgl-likes"><a href="' . admin_url( 'admin-ajax.php?action=thegov_like' . '&post_id=' . $post_id . '&nonce=' . $nonce . '&is_comment=' . $is_comment . '&disabled=true' ) . '" class="sl-button' . $post_id_class . $class . $comment_class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" data-iscomment="' . $is_comment . '" title="' . $title . '">' . $icon . $count . '</a>' . $loader . '</div>';
        return $output;
    }

    public function thegov_post_user_likes( $user_id, $post_id, $is_comment ) {
        $post_users = '';
        $post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, '_user_comment_liked' ) : get_post_meta( $post_id, '_user_liked' );
        if ( count( $post_meta_users ) != 0 ) {
            $post_users = $post_meta_users[0];
        }
        if ( !is_array( $post_users ) ) {
            $post_users = array();
        }
        if ( !in_array( $user_id, $post_users ) ) {
            $post_users['user-' . $user_id] = $user_id;
        }
        return $post_users;
    }

    public function thegov_post_ip_likes( $user_ip, $post_id, $is_comment ) {
        $post_users = '';
        $post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, '_user_comment_IP' ) : get_post_meta( $post_id, '_user_IP' );
        // Retrieve post information
        if ( count( $post_meta_users ) != 0 ) {
            $post_users = $post_meta_users[0];
        }
        if ( !is_array( $post_users ) ) {
            $post_users = array();
        }
        if ( !in_array( $user_ip, $post_users ) ) {
            $post_users['ip-' . $user_ip] = $user_ip;
        }
        return $post_users;
    }

    public function thegov_like_get_ip() {
        if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }
        $ip = filter_var( $ip, FILTER_VALIDATE_IP );
        $ip = ( $ip === false ) ? '0.0.0.0' : $ip;
        return $ip;
    }

    public function thegov_get_liked_icon() {
        /* If already using Font Awesome with your theme, replace svg with: <i class="fa fa-heart"></i> */
        $icon = '<span class="sl-icon flaticon-heart unliked"></span>';
        return $icon;
    }

    public function thegov_get_unliked_icon() {
        /* If already using Font Awesome with your theme, replace svg with: <i class="fa fa-heart-o"></i> */
        $icon = '<span class="sl-icon flaticon-valentines-heart liked"></span>';
        return $icon;
    }

    public function thegov_sl_format_count( $number ) {
        $precision = 2;
        if ( $number >= 1000 && $number < 1000000 ) {
            $formatted = number_format( $number/1000, $precision ).'K';
        } else if ( $number >= 1000000 && $number < 1000000000 ) {
            $formatted = number_format( $number/1000000, $precision ).'M';
        } else if ( $number >= 1000000000 ) {
            $formatted = number_format( $number/1000000000, $precision ).'B';
        } else {
            $formatted = $number; // Number is less than 1000
        }
        $formatted = str_replace( '.00', '', $formatted );
        return $formatted;
    }

    public function get_like_count( $like_count ) {
        $like_text = esc_html__( '0', 'thegov-core' );
        if ( is_numeric( $like_count ) && $like_count > 0 ) {
            $number = $this->thegov_sl_format_count( $like_count );
        } else {
            $number = $like_text;
        }
        $count = '<span class="sl-count">' . $number. " " .'</span>';
        return $count;
    }

}

function wgl_simple_likes() {
    return WglSimpleLikes::instance();
}
?>