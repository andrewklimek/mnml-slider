<?php
/*
Plugin Name: Minimalist Slider
Description: a very light-weight slider plugin.  Shortcode [mnmlslider slide='.hentry' track='.mnmlslider']
Version:     0.1
Plugin URI:  
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


/*

TODO:
Add option to specify number of slides per page and maybe what pixel it switches to 1
Add option for seconds between transition
*/

function mnmlslider($a, $c){
	
	$id = 'mnmlslider-' . mt_rand();
	
	$selector = !empty( $a['slide'] ) ? $a['slide'] : "article";
	$track = !empty( $a['track'] ) ? $a['track'] : ".mnmlslider-inner";

	ob_start();
	
	?>
	<div id=<?php echo $id ?> style=overflow-x:hidden>
	<div class=r>
	<button class=mnmlslider-prev><div class=mnmlslider-arrow></div></button>
	<button class=mnmlslider-next><div class=mnmlslider-arrow></div></button>
	</div>
	<div class=mnmlslider-inner>
	<?php
	
	echo do_shortcode( $c );
	
	?>
	</div>
	<style>
	.mnmlslider-arrow {
		width: 1rem;
		height: 1rem;
		background: none;
		border: 0 solid currentColor;
		border-width: 0 0 2px 2px;
		transform: rotate(45deg);
	}
	.mnmlslider-prev,
	.mnmlslider-next {
		padding: 1rem 0 1rem 1rem;
		background: none;
	}
	.mnmlslider-next {
		transform: rotate(180deg);
	}
	/*@media(min-width:330px){*/
	<?php echo "#{$id} {$track}"; ?>{display:-ms-flexbox;display:flex;transition:transform linear .5s;position:relative}
	<?php echo "#{$id} {$selector}"; ?>{width:50%;-ms-flex:0 0 auto;flex:none}
	/*}*/
	@media(min-width:660px){<?php echo "#{$id} {$selector}"; ?>{width:33.33%}}
	@media(min-width:880px){<?php echo "#{$id} {$selector}"; ?>{width:25%}}
	@media(min-width:1100px){<?php echo "#{$id} {$selector}"; ?>{width:20%}}
	@media(min-width:1320px){<?php echo "#{$id} {$selector}"; ?>{width:16.67%}}
	/*@media(max-width:899){
	.quickcat-container{transform: translateX(0) !important;}
	}*/
	</style>
	<script>(function(){
	var instance = document.getElementById('<?php echo $id; ?>')
		, track = instance.querySelector('<?php echo $track; ?>')
		, slides = track.children
		, os = 0
		, touch;
		
	
	// copy first 3 slides to end for infinite loop effect
	if ( slides.length > 5 ) {
	track.insertAdjacentHTML('beforeend', slides[0].outerHTML + slides[1].outerHTML + slides[2].outerHTML + slides[3].outerHTML + slides[4].outerHTML + slides[5].outerHTML);
	}
	track.addEventListener('touchstart', function(e){ touch=e.changedTouches[0].pageX; });
	track.addEventListener('touchend', function(e){ touch-=e.changedTouches[0].pageX; touch < -60 ? next(1) : touch > 60 ? next(0) : touch=0; });
	
	function next(prev)
	{	
		prev?--os:++os;
		if(os<0)os=0;
	
		track.style.transition='';
		// track.style.transform = 'translateX(-'+ os * 100 / 6 +'%)';
		track.style.transform = 'translateX(-'+ os * 100 / Math.max( 2, Math.min( 6, Math.floor(innerWidth / 220) ) ) +'%)';
		
		if ( slides.length > 5 && os > slides.length - 7 ) setTimeout( function(){	
			os = 0;
			track.style.transition = 'none';
			track.style.transform = '';
		}, 500 );
	}
	
	instance.querySelector('.mnmlslider-prev').addEventListener('click', function(){ next(1) });
	instance.querySelector('.mnmlslider-next').addEventListener('click', function(){ next(0) });
	})();</script>
	</div>
	<?php
	
	/**** Before Minified
	
	var track = document.querySelector('.mnmonials-track')
		, slides = track.children
		// , slideNo = slides.length - 1
		, os = 0
		, iid, touch;
		
	
	// copy first 3 slides to end for infinite loop effect
	track.insertAdjacentHTML('beforeend', slides[0].outerHTML + slides[1].outerHTML + slides[2].outerHTML );
	
	iid=setInterval(next,6e3);
	
	document.addEventListener('visibilitychange',function(){document.hidden ? clearInterval(iid) : (iid=setInterval(next,6e3));});
	
	track.addEventListener('touchstart', function(e){ touch=e.changedTouches[0].pageX; });
	track.addEventListener('touchend', function(e){ touch-=e.changedTouches[0].pageX; touch < -60 ? next(1) : touch > 60 ? next() : touch=0; touch && clearInterval(iid); });
	
	function next(prev)
	{	
		prev?--os:++os;
		if(os<0)os=0;
	
		track.style.transition='';
		// track.style.transform = 'translateX(-'+ os * 100 / Math.min( 3, Math.floor(innerWidth / 300) ) +'%)';
		track.style.transform = 'translateX(-'+ os * 100 / (innerWidth < 900 ? 1 : 3) +'%)';
		
		if ( os > slides.length - 4 ) setTimeout( function(){	
			os = 0;
			track.style.transition = 'none';
			track.style.transform = '';
		}, 3e3 );
	}
	
	** End Before Minified */

	
	
	$return = ob_get_clean();

	
	return $return;
	
}
add_shortcode( 'mnmlslider', 'mnmlslider');

