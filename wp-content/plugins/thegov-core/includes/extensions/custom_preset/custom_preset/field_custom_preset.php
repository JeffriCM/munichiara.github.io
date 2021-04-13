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
 * @author      Dovy Paukstys
 * @version     3.1.5
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_custom_preset' ) ) {

    /**
     * Main ReduxFramework_custom_field class
     *
     * @since       1.0.0
     */
    class ReduxFramework_custom_preset{
    
        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value ='', $parent ) {
        
            
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;
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
            // Set default args for this field to avoid bad indexes. Change this to anything you use.
            $defaults = array(
                'options'           => array(),
                'stylesheet'        => '',
                'output'            => true,
                'enqueue'           => true,
                'enqueue_frontend'  => true
            );
            $this->field = wp_parse_args( $this->field, $defaults );            
        
        }

        public function render() {
            $defaults = array();

            $this->field = wp_parse_args( $this->field, $defaults );
            $id = $this->parent->args['opt_name'] . '-' . $this->field['id'];
            
            ?>
            <div class="wgl_preset_notice">
                <?php
                    esc_html_e('Notice', 'thegov-core');
                    echo '<ul>';
                        echo '<li>';
                            echo '<div class="notice_h">';
                            echo '</div>';
                            esc_html_e('We will reset all options inside header builder', 'thegov-core');
                        echo '</li>';
                        echo '<li>';
                            esc_html_e('You can modify your header using header builder after choosing header builder template. Click "Save Preset", to save the header builder layout', 'thegov-core');
                        echo '</li>';
                    echo '<ul>';
                ?>
            </div>
            <div id="wgl_custom_preset" class="wgl_custom_preset-wrapper">
            <div id="redux-add-preset-action"><input type="submit" id="redux-add-preset" value="<?php esc_html_e( 'Add Preset', 'thegov-core' ) ?>"><i class="fa fa-plus"></i></div>

            <div id="redux-save-preset-action" style="display:none;">
                <div class="wrap-save-preset">
                    <input type="text" name="name_preset" class="save-name-preset">
                    <span class="spinner"></span>
                    <input type="submit" id="redux-save-preset" class="button-primary" value="<?php esc_html_e( 'Save', 'thegov-core' ) ?>">                    
                </div>
                <span class="close-save-preset"><i class="fa fa-times" aria-hidden="true"></i></span>
            </div>          
           
            <?php    
            
            $options_presets = array();
            $list_presets = get_option($this->parent->args['opt_name']. '_preset');      
            $list_defualt_preset = array();

            if(is_array($list_presets)){              
                foreach ($list_presets as $key => $value) {
                    if($key == 'default'){
                        $list_defualt_preset = $list_presets[$key];
                        foreach ($list_presets[$key] as $k => $v) {
                                $options_presets[$k] = array(
                                    'alt'     => $k,
                                    'presets' => json_encode( $v ),
                                    'default' => true

                            );  
                        } 
                    }
                    if($key != 'default'){
                        $options_presets[$key] = array(
                            'alt'     => $key,
                            'presets' => json_encode( $value ),
                            'default' => false
                        );
                    }
                }                  
            }

            $checked_preset = false;

            $this->field['options'] = $options_presets;

            if ( isset( $this->field['options'] ) ) {
                if(!empty($this->field['options'])){
                    echo "<h4>".__('Saved Templates', 'thegov-core')."</h4>";
                }

                echo '<div class="redux-table-container">';
                echo '<ul class="redux-custom-preset-select">';

                $x = 1;

                foreach ( $this->field['options'] as $k => $v ) {
                    if ( ! isset( $v['title'] ) ) {
                        $v['title'] = '';
                    }

                    if ( ! isset( $v['alt'] ) ) {
                        $v['alt'] = $v['title'];
                    }

                    if ( ! isset( $v['class'] ) ) {
                        $v['class'] = '';
                    }                    
                    
                    $theValue = $k;

                    $selected = ( checked( $this->value, $theValue, false ) != '' ) ? ' redux-custom-preset-select-selected' : '';

                    $presets   = '';

                    $this->field['class'] .= ' noUpdate ';

                    $this->field['class'] = trim($this->field['class']);
                    if ( ! isset( $v['presets'] ) ) {
                        $v['presets'] = array();
                    }

                    if ( ! is_array( $v['presets'] ) ) {
                        $v['presets'] = json_decode( $v['presets'], true );
                    }

                    // Only highlight the preset if it's the same

                    if ( $selected ) {
                        if ( empty( $v['presets'] ) ) {
                            $selected = false;
                        } else {
                            foreach ($v['presets'] as $pk => $pv ) {
                                if ( ! isset( $this->parent->options[ $pk ] ) ) {
                                    $selected = false;
                                }
                                if ( ! $selected ) { 
                                    $this->value = "";
                                    break;
                                }
                            }
                        }
                    }

                    if($selected){
                    	$checked_preset = true;
                    }
                    $v['presets']['redux-backup'] = 1;

                    $presets   = ' data-presets="' . htmlspecialchars( json_encode( $v['presets'] ), ENT_QUOTES, 'UTF-8' ) . '"';
                    $is_preset = true;

                    $this->field['class'] = trim( $this->field['class'] ) . ' redux-presets';

                    $is_preset_class = $is_preset ? '-preset-' : ' ';

                    echo '<li class="redux-custom-preset-select'.($selected ? ' item_selected' : "").'">';

                    echo '<label class="' . $selected . ' redux-custom-preset-select' . $is_preset_class . $this->field['id'] . '_' . $x . '" for="' . $this->field['id'] . '_' . ( array_search( $k, array_keys( $this->field['options'] ) ) + 1 ) . '">';

                    echo '<input type="radio" class="' . $this->field['class'] . '" id="' . $this->field['id'] . '_' . ( array_search( $k, array_keys( $this->field['options'] ) ) + 1 ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '" value="' . $theValue . '" ' . checked( $this->value, $theValue, false ) . $presets . '/>';

                        echo '<span class="' . $v['class'] . ' js_preset_insert">' . $v['alt'] . '</span>';                        

                    echo '</label>';
                    echo '<div class="wrapper-btn-preset">';                        
                        
                        if ( $selected ) { 
                            echo '<div id="selected-btn-preset" class="btn-preset">';                                               
                                echo '<input type="submit" class="selected-btn_custom btn-primary redux-update-preset" value="'.apply_filters( "wgl-changed-text-save-preset-{$this->parent->args['opt_name']}", __( 'Save Preset', 'thegov-core' ) ).'">'; 
                                
                            echo '</div>';
                        }   
                        
                        if(!$v['default'] && !array_key_exists($k, $list_defualt_preset)){ 
                            echo '<input type="submit" class="selected-btn_custom btn-primary redux-rename-preset" value="'.apply_filters( "wgl-changed-text-rename-preset-{$this->parent->args['opt_name']}", __( 'Rename', 'thegov-core' ) ).'">';

                             echo '<div class="delete-preset">';
                                echo '<a href="#" title="'.apply_filters( "wgl-changed-text-delete-preset-{$this->parent->args['opt_name']}", __( 'Delete Preset', 'thegov-core' ) ).'" class="redux-delete-preset">';
                                echo '<i class="fa fa-trash" aria-hidden="true"></i>';
                                echo '</a>';
                                echo '<span class="spinner"></span>';               
                            echo '</div>';                           
                        }
                        
                        if(!$v['default'] && array_key_exists($k, $list_defualt_preset)){
                        	if($selected){
	                            echo '<div class="delete-preset">';
	                                echo '<a href="#" title="'.apply_filters( "wgl-changed-text-reset-preset-{$this->parent->args['opt_name']}", __( 'Reset', 'thegov-core' ) ).'" class="redux-delete-preset reset-default">';
	                                echo '<i class="fa fa-undo" aria-hidden="true"></i>';
	                                echo '</a>';
	                                echo '<span class="spinner"></span>';               
	                            echo '</div>';                          		
                        	}
                         
                        }                     

                    echo '</div>';

                    echo '</li>';

                    $x ++;
                }

                echo '</ul>';
                echo '</div>';
            }
            if(!$checked_preset){
            	echo '<div class="overlay_header" style="display: none;"></div>';
            }
            // Close Redux Custom field Preset
            echo "</div>";
            
        }
         
        /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {
            wp_enqueue_script(
                'redux-field-icon-custom_preset-js', 
                $this->extension_url . 'field_custom_preset.js', 
                array( 'jquery' ),
                time(),
                true
            );
            
            $vars = array(
                'delete'   => __( 'Delete Preset?', 'thegov-core' ),
                'reset'   => __( 'Reset to default?', 'thegov-core' ),
                'taken_name'   => __( 'Sorry this name is already exists', 'thegov-core' ),
                'empty_name'   => __( 'Empty name', 'thegov-core' ),
            );

            wp_localize_script( 'redux-field-icon-custom_preset-js', 'wglLocalVars', $vars );

            wp_enqueue_style(
                'redux-field-icon-custom_preset-css', 
                $this->extension_url . 'field_custom_preset.css',
                time(),
                true
            );
        
        }
        
    }
}
