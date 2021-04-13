<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @author      Dovy Paukstys (dovy)
 * @version     3.0.0
 */

// Exit if accessed directly

if( !defined( 'ABSPATH' ) ) exit;
// Don't duplicate me!

if( !class_exists( 'ReduxFramework_extension_custom_preset' ) ) {
    /**
     * Main ReduxFramework custom_field extension class
     *
     * @since       3.1.6
     */

    class ReduxFramework_extension_custom_preset {
        
        // Set the version number of your extension here
        public static $version       = '1.0.0';
        // Set the name of your extension here
        public $ext_name             = 'Custom Preset';
        
        // Set the minumum required version of Redux here (optional).
        // Leave blank to require no minimum version.
        // This allows you to specify a minimum required version of Redux in the event
        // you do not want to support older versions.
        public $min_redux_version    = '3.0.0';
        // Protected vars
        protected $parent;
        public $extension_url;
        public $extension_dir;
        public static $theInstance;        


        // Private vars
        private $options;
        
        /**
        * Class Constructor. Defines the args for the extions class
        *
        * @since       1.0.0
        * @access      public
        * @param       array $sections Panel sections.
        * @param       array $args Class constructor arguments.
        * @param       array $extra_tabs Extra panel tabs.
        * @return      void
        */
        
        public function __construct( $parent ) {
            
            $this->parent = $parent;
            
            if (is_admin() && !$this->is_minimum_version()) {
                return;
            }
            
            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                
                $dir = Redux_Helpers::cleanFilePath( dirname( __FILE__ ) );
                $_dir = trailingslashit( $dir );

                $wp_content_url = trailingslashit( Redux_Helpers::cleanFilePath( ( is_ssl() ? str_replace( 'http://', 'https://', WP_CONTENT_URL ) : WP_CONTENT_URL ) ) );

                $wp_content_dir = trailingslashit( Redux_Helpers::cleanFilePath( WP_CONTENT_DIR ) );
                $wp_content_dir = trailingslashit( str_replace( '//', '/', $wp_content_dir ) );
                $relative_url   = str_replace( $wp_content_dir, '', $_dir );
                $this->extension_url     = trailingslashit( $wp_content_url . $relative_url );
            }
            
            $this->field_name = 'custom_preset';
            self::$theInstance = $this;
            add_filter( 'redux/'.$this->parent->args['opt_name'].'/field/class/'.$this->field_name, array( &$this, 'overload_field_path' ) ); // Adds the local field
            add_action( "wp_ajax_" . $this->parent->args['opt_name'] . '_ajax_save_preset', array( $this, "ajax_save" ) );
            add_action( "wp_ajax_" . $this->parent->args['opt_name'] . '_ajax_rename_preset', array( $this, "ajax_rename" ) );
        }
        public function getInstance() {
            return self::$theInstance;
        }
        
        // Forces the use of the embeded field path vs what the core typically would use    
        public function overload_field_path($field) {
            return dirname(__FILE__).'/'.$this->field_name.'/field_'.$this->field_name.'.php';
        }

        private function is_minimum_version () {
            $redux_ver = ReduxFramework::$_version;
            if ($this->min_redux_version != '') {
                if (version_compare($redux_ver, $this->min_redux_version) < 0) {
                    $msg = '<strong>' . esc_html__( 'The', 'redux-framework') . ' ' .  $this->ext_name . ' ' .  esc_html__('extension requires', 'redux-framework') . ' Redux Framework ' . esc_html__('version', 'redux-framework') . ' ' . $this->min_redux_version . ' ' .  esc_html__('or higher.','redux-framework' ) . '</strong>&nbsp;&nbsp;' . esc_html__( 'You are currently running', 'redux-framework') . ' Redux Framework ' . esc_html__('version','redux-framework' ) . ' ' . $redux_ver . '.<br/><br/>' . esc_html__('This field will not render in your option panel, and featuress of this extension will not be available until the latest version of','redux-framework' ) . ' Redux Framework ' . esc_html__('has been installed.','redux-framework' );
                    
                    $data = array(
                        'parent'    => $this->parent,
                        'type'      => 'error',
                        'msg'       => $msg,
                        'id'        => $this->ext_name . '_notice_' . self::$version,
                        'dismiss'   => false
                    );
                    
                    if (method_exists('Redux_Admin_Notices', 'set_notice')) {
                        Redux_Admin_Notices::set_notice($data);
                    } else {
                        echo '<div class="error">';
                        echo     '<p>';
                        echo         $msg;
                        echo     '</p>';
                        echo '</div>';
                    }
                    return false;
                }
            }
            
            return true;
        }
                    
        function redux_parse_str( $string ) {
            if ( '' == $string ) {
                return false;
            }

            $result = array();
            $pairs  = explode( '&', $string );

            foreach ( $pairs as $key => $pair ) {
                    // use the original parse_str() on each element
                parse_str( $pair, $params );

                $k = key( $params );

                if ( ! isset( $result[ $k ] ) ) {
                    $result += $params;
                } else {
                    $result[ $k ] = $this->redux_array_merge_recursive_distinct( $result[ $k ], $params[ $k ] );
                }
            }

            return $result;
        }

        function redux_array_merge_recursive_distinct( array $array1, array $array2 ) {
            $merged = $array1;

            foreach ( $array2 as $key => $value ) {
                if ( is_array( $value ) && isset( $merged[ $key ] ) && is_array( $merged[ $key ] ) ) {
                    $merged[ $key ] = $this->redux_array_merge_recursive_distinct( $merged[ $key ], $value );
                } else if ( is_numeric( $key ) && isset( $merged[ $key ] ) ) {
                    $merged[] = $value;
                } else {
                    $merged[ $key ] = $value;
                }
            }

            return $merged;
        }
    
        public function setOption($redux, $value, $atts){
            if ( ! empty ( $value ) ) {
                
                $list_presets = get_option($redux->args['opt_name']. '_preset');
                $name_preset = $atts['name_preset'];

                $list_presets[$name_preset] = $value;
                if(isset($atts['delete']) && $atts['delete'] == 'true'){
                   unset($list_presets[$name_preset]); 
                }
                $this->options = $value;

                update_option( $redux->args['opt_name']. '_preset', $list_presets );
            
            }
        }

        public function rename_preset($redux, $atts){
            $list_presets = get_option($redux->args['opt_name']. '_preset');
            
            $name_preset = $atts['name_preset'];
            $rename_preset = $atts['rename_preset'];
            $temp_preset = $list_presets[$name_preset];
            if(!$list_presets[$name_preset]){
                unset($list_presets['default'][$name_preset]);
                $list_presets['default'][$rename_preset] = $temp_preset;
                $list_presets['default'][$rename_preset]['opt-js-preset'] = $rename_preset;
            }else{
                unset($list_presets[$name_preset]);
                $list_presets[$rename_preset] = $temp_preset;
                $list_presets[$rename_preset]['opt-js-preset'] = $rename_preset;
            }
            update_option( $redux->args['opt_name']. '_preset', $list_presets ); 
        } 

        public function ajax_save() {
            
            if ( ! wp_verify_nonce( $_REQUEST['nonce'], "redux_ajax_nonce" . $this->parent->args['opt_name'] ) ) {
                echo json_encode( array(
                    'status' => __( 'Invalid security credential.  Please reload the page and try again.', 'redux-framework' ),
                    'action' => ''
                ) );

                die();
            }

            if ( ! current_user_can( $this->parent->args['page_permissions'] ) ) {
                echo json_encode( array(
                    'status' => __( 'Invalid user capability.  Please reload the page and try again.', 'redux-framework' ),
                    'action' => ''
                ) );

                die();
            }
            $redux = ReduxFrameworkInstances::get_instance( $_POST['opt_name'] );

            if ( ! empty ( $_POST['data'] ) && ! empty ( $redux->args['opt_name'] ) ) {

                $values = array();
                $_POST['data'] = stripslashes( $_POST['data'] );

                $values = $this->redux_parse_str( $_POST['data'] );

                $values = $values[ $redux->args['opt_name'] ];

                if ( function_exists( 'get_magic_quotes_gpc' ) && get_magic_quotes_gpc() ) {
                    $values = array_map( 'stripslashes_deep', $values );
                }

                if ( ! empty ( $values ) ) {

                    try {
                        if ( isset ( $redux->validation_ran ) ) {
                            unset ( $redux->validation_ran );
                        } 

                        $this->setOption($redux, $redux->_validate_options( $values ), $_POST );

                        $return_array = array(
                            'status'   => 'success',
                            'options'  => $this->options,
                            'data'  => $_POST,
                            'errors'   => isset ( $redux->localize_data['errors'] ) ? $redux->localize_data['errors'] : null,
                            'warnings' => isset ( $redux->localize_data['warnings'] ) ? $redux->localize_data['warnings'] : null,
                        );

                    } catch ( Exception $e ) {
                        $return_array = array( 'status' => $e->getMessage() );
                    }
                } else {
                    echo json_encode( array( 'status' => __( 'Your panel has no fields. Nothing to save.', 'redux-framework' ) ) );
                }
            }

            if ( isset( $return_array ) ) {
                echo json_encode( apply_filters( "redux/options/{$this->parent->args['opt_name']}/ajax_save/response", $return_array ) );
            }

            die ();
        }



        public function ajax_rename() {
            
            if ( ! wp_verify_nonce( $_REQUEST['nonce'], "redux_ajax_nonce" . $this->parent->args['opt_name'] ) ) {
                echo json_encode( array(
                    'status' => __( 'Invalid security credential.  Please reload the page and try again.', 'redux-framework' ),
                    'action' => ''
                ) );

                die();
            }

            if ( ! current_user_can( $this->parent->args['page_permissions'] ) ) {
                echo json_encode( array(
                    'status' => __( 'Invalid user capability.  Please reload the page and try again.', 'redux-framework' ),
                    'action' => ''
                ) );

                die();
            }
            $redux = ReduxFrameworkInstances::get_instance( $_POST['opt_name'] );

            if (! empty ( $redux->args['opt_name'] ) ) {

                try {
                    if ( isset ( $redux->validation_ran ) ) {
                        unset ( $redux->validation_ran );
                    } 

                    $this->rename_preset($redux, $_POST );

                    $return_array = array(
                        'status'   => 'success',
                        'data'  => $_POST,
                        'errors'   => isset ( $redux->localize_data['errors'] ) ? $redux->localize_data['errors'] : null,
                        'warnings' => isset ( $redux->localize_data['warnings'] ) ? $redux->localize_data['warnings'] : null,
                    );

                } catch ( Exception $e ) {
                    $return_array = array( 'status' => $e->getMessage() );
                }

            }

            if ( isset( $return_array ) ) {
                echo json_encode( apply_filters( "redux/options/{$this->parent->args['opt_name']}/ajax_save/response", $return_array ) );
            }

            die ();
        }
    } // class
} // if