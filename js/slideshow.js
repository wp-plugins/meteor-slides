// jQuery Cycle script which powers the Slideshow: http://jquery.malsup.com/cycle/

var $j = jQuery.noConflict();

var $slidespeed = parseInt( meteorslidessettings.meteorslideshowspeed );

var $slidetimeout = parseInt( meteorslidessettings.meteorslideshowduration );

var $slideheight = parseInt( meteorslidessettings.meteorslideshowheight );

var $slidewidth = parseInt( meteorslidessettings.meteorslideshowwidth );

var $slidetransition = meteorslidessettings.meteorslideshowtransition;

$j(document).ready(function() {

    $j('.slides').cycle({
	
		fx: $slidetransition,
		speed: $slidespeed,
		timeout: $slidetimeout,
		pause: '1',
		prev:   '#prev', 
		next:   '#next'
		
	});
	
	$j('.meteor-slides,#meteor-nav a,.slides').css('height', $slideheight);
		
	$j('.meteor-slides,.slides').css('width', $slidewidth);
		
	$j(".meteor-slides").hover(function() {
	
		$j("ul#meteor-nav").css('display', 'block');
		
	}, function() {
	
		$j("ul#meteor-nav").css('display', 'none');
		
	});

});