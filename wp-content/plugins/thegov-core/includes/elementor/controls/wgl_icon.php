<?php
namespace WglAddons\Controls;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
* Wgl Elementor Custom Icon Control
*
*
* @class        Wgl_Icon
* @version      1.0
* @category Class
* @author       WebGeniusLab
*/

class Wgl_Icon extends Base_Data_Control{

    /**
     * Get radio image control type.
     *
     * Retrieve the control type, in this case `radio-image`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function get_type() {
        return 'wgl-icon';
    }

    public function enqueue() {
        // Scripts
        wp_enqueue_script( 'wgl-elementor-extensions', WGL_ELEMENTOR_ADDONS_URL . 'assets/js/wgl_elementor_extenstions.js');

        // Style
        wp_enqueue_style( 'wgl-elementor-extensions', WGL_ELEMENTOR_ADDONS_URL . 'assets/css/wgl_elementor_extenstions.css');
    }

    public static function get_flaticons( ) {
        return array(
            'flaticon-search' => 'search',
            'flaticon-arrow' => 'arrow',
            'flaticon-aim' => 'aim',
            'flaticon-embassy' => 'embassy',
            'flaticon-bus' => 'bus',
            'flaticon-team' => 'team',
            'flaticon-forest' => 'forest',
            'flaticon-school' => 'school',
            'flaticon-right' => 'right',
            'flaticon-arrow-pointing-to-down' => 'arrow-pointing-to-down',
            'flaticon-arrow-pointing-to-left' => 'arrow-pointing-to-left',
            'flaticon-arrow-pointing-to-up' => 'arrow-pointing-to-up',
            'flaticon-left-quote' => 'left-quote',
            'flaticon-right-quote' => 'right-quote',
            'flaticon-play' => 'play',
            'flaticon-map' => 'map',
            'flaticon-edit' => 'edit',
            'flaticon-phone' => 'phone',
            'flaticon-clock' => 'clock',
            'flaticon-calendar' => 'calendar',
            'flaticon-court' => 'court',
            'flaticon-town' => 'town',
            'flaticon-monument' => 'monument',
            'flaticon-fair' => 'fair',
            'flaticon-bench' => 'bench',
            'flaticon-bank' => 'bank',
            'flaticon-share' => 'share',
            'flaticon-caret-down' => 'caret-down',
            'flaticon-antique-elegant-building-with-columns' => 'antique-elegant-building-with-columns',
            'flaticon-hot-air-ballon' => 'hot-air-ballon',
            'flaticon-car' => 'car',
            'flaticon-shopping-purse-icon' => 'shopping-purse-icon',
            'flaticon-author-sign' => 'author-sign',
            'flaticon-speech' => 'speech',
            'flaticon-group' => 'group',
            'flaticon-like' => 'like',
            'flaticon-open-book' => 'open-book',
            'flaticon-building' => 'building',
            'flaticon-cityscape' => 'cityscape',
            'flaticon-city-hall' => 'city-hall',
            'flaticon-shopping-bag' => 'shopping-bag',
            'flaticon-filter' => 'filter',
            'flaticon-route' => 'route',
            'flaticon-sad' => 'sad',
            'flaticon-meeting-point' => 'meeting-point',
            'flaticon-portfolio' => 'portfolio',
            'flaticon-pie-chart' => 'pie-chart',
            'flaticon-tree' => 'tree',
            'flaticon-instagram' => 'instagram',
            'flaticon-cancel' => 'cancel',
            'flaticon-paper-plane' => 'paper-plane',
            'flaticon-link' => 'link',
            'flaticon-chain' => 'chain',
            'flaticon-close' => 'close',
            'flaticon-close-cross' => 'close-cross',
            'flaticon-bookmark-black-shape' => 'bookmark-black-shape',
            'flaticon-bookmark-white' => 'bookmark-white',
            'flaticon-tag' => 'tag',
            'flaticon-banner' => 'banner',
            'flaticon-bookmark' => 'bookmark',
            'flaticon-document' => 'document',
            'flaticon-folder' => 'folder',
            'flaticon-folder-1' => 'folder-1',
            'flaticon-magnifying-glass' => 'magnifying-glass',
            'flaticon-magnifying-glass-1' => 'magnifying-glass-1',
            'flaticon-magnifier' => 'magnifier',
            'flaticon-valentines-heart' => 'valentines-heart',
            'flaticon-favorite-heart-button' => 'favorite-heart-button',
            'flaticon-eye-open' => 'eye-open',
            'flaticon-gear' => 'gear',
            'flaticon-gear-1' => 'gear-1',
            'flaticon-bicycle' => 'bicycle',
            'flaticon-car-1' => 'car-1',
            'flaticon-city-hall-1' => 'city-hall-1',
            'flaticon-scooter' => 'scooter',
            'flaticon-hot-air-balloon' => 'hot-air-balloon',
            'flaticon-office-block' => 'office-block',
            'flaticon-fountain' => 'fountain',
            'flaticon-bar' => 'bar',
            'flaticon-truck' => 'truck',
            'flaticon-metro' => 'metro',
            'flaticon-ice-cream' => 'ice-cream',
            'flaticon-user-shape' => 'user-shape',
            'flaticon-home' => 'home',
            'flaticon-speech-bubbles-comment-option' => 'speech-bubbles-comment-option',
            'flaticon-quote' => 'quote',
            'flaticon-heart' => 'heart',
            'flaticon-night' => 'night',
            'flaticon-heart-1' => 'heart-1',
            'flaticon-clip' => 'clip',
        );
    }

    /**
     * Get radio image control default settings.
     *
     *
     * @since 1.0.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function get_default_settings() {
        return [
            'label_block' => true,
            'options' => self::get_flaticons(),
            'include' => '',
            'exclude' => '',
            'select2options' => [],
        ];
    }

    /**
     * Render radio image control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     * @access public
     */
    public function content_template() {

        $control_uid = $this->get_control_uid();
        ?>
        <div class="elementor-control-field">
            <# if ( data.label ) {#>
                <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper">
                <select id="<?php echo $control_uid; ?>" class="elementor-control-icon elementor-select2" type="select2"  data-setting="{{ data.name }}" data-placeholder="<?php echo __( 'Select Icon', 'thegov-core' ); ?>">
                    <# _.each( data.options, function( option_title, option_value ) {
                        var value = data.controlValue;
                        if ( typeof value == 'string' ) {
                            var selected = ( option_value === value ) ? 'selected' : '';
                        } else if ( null !== value ) {
                            var value = _.values( value );
                            var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
                        }
                        #>
                    <option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
                    <# } ); #>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}

?>