<?php
/*
Plugin Name: Slidemonger
Description: Lightweight & simple shortcode [slidemonger]
Version:     0.1
Author:      Andrew J Klimek
Author URI:  https://readycat.net
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Slidemonger is free software: you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by the Free 
Software Foundation, either version 2 of the License, or any later version.

Slidemonger is distributed in the hope that it will be useful, but without 
any warranty; without even the implied warranty of merchantability or fitness for a 
particular purpose. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with 
Slidemonger. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

add_action( 'wp_enqueue_scripts', 'slidemonger_scripts' );
add_shortcode( 'slidemonger', 'slidemonger' );

function slidemonger( $a, $c = '' ) {
	if ( !$c ) return "You have no closing [/slidemonger] or no content.";
	// handle the stupid autop stuff.
	if ( substr( $c, 0, 4 ) === '</p>' ) $c = substr( $c, 4 );
	if ( substr( $c, -3, 3 ) === '<p>' ) $c = substr( $c, 0, -3 );
	
	$idno = 1 + wp_cache_get( 'slidemonger_id' );
	wp_cache_set('slidemonger_id', $idno );
	
	$selector = !empty( $a['id'] ) ? $a['id'] : "slidemonger";
	$selector .= "-{$idno}";
	
	$snippet = "
	jQuery(document).ready(function($) {
		$('#{$selector}').unslider({
			infinite: true 
		});
	});
	";
	
	// if ( 1 === $idno ) $out .= "<style>". file_get_contents( __DIR__ .'/unslider/unslider.css' ) ."</style>\n";
	wp_enqueue_style( 'unslider' );
	wp_enqueue_script( 'unslider' );
	wp_add_inline_script( 'unslider', $snippet );
	
	$class = !empty( $a['class'] ) ? " {$a['class']}" : "";
	
	$out = "<div id='{$selector}' class='slidemonger{$class}'>" . do_shortcode( $c ) . "</div>";
	return $out;
}


function slidemonger_scripts() {
	wp_register_script( 'unslider', plugin_dir_url( __FILE__ ) . 'unslider/unslider-min.js', array('jquery'), '2.0.3' );
	wp_register_style( 'unslider', plugin_dir_url( __FILE__ ) . 'slidemonger.css', array(), filemtime( plugin_dir_path( __FILE__ ).'slidemonger.css' ) );
}