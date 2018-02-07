<?php

function calendar( $atts ){

}
add_shortcode('display_calendar', 'calendar');

add_action( 'wp_enqueue_scripts', 'my_scripts_for_map' );

function my_scripts_for_map(){
	$all_options = get_option('true_options');
	
}