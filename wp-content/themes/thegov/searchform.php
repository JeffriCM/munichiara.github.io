<?php
/**
 * Template for displaying search forms in Thegov
 *
 * @package thegov
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 * @version 1.1.2
 */

?>
<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>
<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-form">
    <input type="text" id="<?php echo esc_attr($unique_id); ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'thegov' ); ?>" value="<?php echo get_search_query(); ?>" name="s" required>
    <input class="search-button" type="submit" value="<?php esc_attr_e('Search', 'thegov'); ?>">
</form>