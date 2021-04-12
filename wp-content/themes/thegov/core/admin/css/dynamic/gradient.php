<?php
if ( !defined( 'ABSPATH' ) ) { exit; }

$css .= '
.theme-gradient input[type="submit"],
.rev_slider .rev-btn.gradient-button,
body .widget .widget-title .widget-title_wrapper:before,
.thegov_module_progress_bar .progress_bar,
.thegov_module_testimonials.type_inline_top .testimonials_meta_wrap:after{';
if ( (bool)$use_gradient_switch ) {
	$css .= '
		background: -webkit-linear-gradient(left, '.$theme_gradient_from.' 0%, '.$theme_gradient_to.' 50%, '.$theme_gradient_from.' 100%);
		background-size: 300%, 1px;
		background-position: 0%;
	}';
} else {
	$css .= 'background-color:'.$theme_color.';}';
}

?>