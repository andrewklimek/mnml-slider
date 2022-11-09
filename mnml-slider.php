<?php
/*
Plugin Name: Minimalist Slider
Description: a very light-weight slider plugin.  Shortcode defaults: [mnmlslider track='.mnmlslider-inner' slide='{$track} > *' max_width=220 max_columns=6 time=500 auto=6000 buttons=right]
Version:     0.6
Plugin URI:  https://github.com/andrewklimek/mnml-slider
Author:      Andrew J Klimek
Author URI:  https://github.com/andrewklimek
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Minimalist Slider is free software: you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by the Free 
Software Foundation, either version 2 of the License, or any later version.

Minimalist Slider is distributed in the hope that it will be useful, but without 
any warranty; without even the implied warranty of merchantability or fitness for a 
particular purpose. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with 
Minimalist Slider. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/


function mnmlslider($a, $c){
	
	$id = 'mnmlslider-' . mt_rand();
	
	$track = !empty( $a['track'] ) ? $a['track'] : ".mnmlslider-inner";
	$selector = !empty( $a['slide'] ) ? $a['slide'] : "{$track}>*";
	$max_width = !empty( $a['max_width'] ) ? $a['max_width'] : 220;
	$max_columns = !empty( $a['max_columns'] ) ? $a['max_columns'] : 6;
	$time = !empty( $a['time'] ) ? $a['time'] : 500;
	$auto_scroll = empty( $a['auto'] ) ? 0 : ( is_numeric($a['auto']) ? $a['auto'] : ( "true" === $a['auto'] ? 6000 : 0 ) );
	$buttons = empty( $a['buttons'] ) ? "right" : ( $a['buttons'] === "false" || $a['buttons'] === "none" ? 0 : $a['buttons'] );

	ob_start();

	?>
	<div id=<?php echo $id ?> style=overflow-x:hidden>
	<?php if ( $buttons ) : ?>
	<div class=mnmlslider-arrows>
	<button class=mnmlslider-prev><div class=mnmlslider-arrow></div></button>
	<button class=mnmlslider-next><div class=mnmlslider-arrow></div></button>
	</div>
	<?php endif; ?>
	<div class=mnmlslider-inner>
	<?php
	
	echo do_shortcode( $c );
	
	?>
	</div>
	<style>
	.mnmlslider-arrows{text-align:<?php echo $buttons; ?>}
	.mnmlslider-arrow {
		width: 1rem;
		height: 1rem;
		background: none;
		border: 0 solid currentColor;
		border-width: 0 0 2px 2px;
		transform: rotate(45deg);
	}
	.mnmlslider-prev,.mnmlslider-next{padding:1rem 0 1rem 1rem;background:none;outline:0;border:0}
	.mnmlslider-next {transform:rotate(180deg)}
	<?php echo "#{$id} {$track}"; ?>{display:-ms-flexbox;display:flex;transition:transform linear <?php echo $time; ?>ms;position:relative}
	<?php echo "#{$id} {$selector}"; ?>{width:50%;-ms-flex:0 0 auto;flex:none}
	<?php for ( $i = 2; $i <= $max_columns; ++$i ) {
		echo "@media(min-width:" . strval($i * $max_width) . "px){#{$id} {$selector}{width:" . strval(100 / $i) . "%}}";
	} ?>
	</style>
	<script>(function(){
	var instance = document.getElementById('<?php echo $id; ?>')
		, track = instance.querySelector('<?php echo $track; ?>')
		, slides = track.children
		, os = 0
		, travel
		, mouse;
	
	if ( slides.length >= <?php echo $max_columns; ?> ) {
	<?php
	// copy first n slides to end for infinite loop effect	
		$duplicate_slides = [];
		for ( $i = 0; $i < $max_columns; ++$i ) $duplicate_slides[] = "slides[{$i}].outerHTML";
		echo "track.insertAdjacentHTML('beforeend', " . implode( "+", $duplicate_slides ) . ");";
	?>
	}
	track.addEventListener('touchstart',function(e){travel=e.changedTouches[0].pageX;});
	track.addEventListener('touchend',function(e){travel-=e.changedTouches[0].pageX; travel < -60 ? next(1) : travel > 60 ? next(0) : travel=0;});
	track.addEventListener('mousedown',function(e){e.preventDefault(); mouse=e.pageX;});
	track.addEventListener('mouseup',function(e){mouse-=e.pageX; mouse < -30 ? next(1) : mouse > 30 ? next(0) : mouse=0;});
	track.addEventListener('click',function(e){mouse && e.preventDefault();});
	function next(prev) {
		<?php if ( $auto_scroll ) echo "clearInterval(iid);iid=setInterval(next,{$auto_scroll});"; ?>
		prev?--os:++os;
		if(os<0)os=0;
		track.style.transition='';
		track.style.transform = 'translateX(-'+ os * 100 / Math.max( 2, Math.min( <?php echo $max_columns; ?>, Math.floor(innerWidth / <?php echo $max_width; ?>) ) ) +'%)';
		
		if ( slides.length > <?php echo $max_columns - 1; ?> && os > slides.length - <?php echo $max_columns + 1; ?> ) setTimeout( function(){	
			os = 0;
			track.style.transition = 'none';
			track.style.transform = '';
		}, <?php echo $time; ?> );
	}
	<?php
	if ( $buttons )
		echo "instance.querySelector('.mnmlslider-prev').addEventListener('click', function(){ next(1) });"
		. "instance.querySelector('.mnmlslider-next').addEventListener('click', function(){ next(0) });";
	
	if ( $auto_scroll )
		echo "iid=setInterval(next,{$auto_scroll});"
		. "document.addEventListener('visibilitychange',function(){document.hidden ? clearInterval(iid) : (iid=setInterval(next,{$auto_scroll}));});";
	?>
	})();</script>
	</div>
	<?php
	
	$return = ob_get_clean();

	return $return;
	
}
add_shortcode( 'mnmlslider', 'mnmlslider');

