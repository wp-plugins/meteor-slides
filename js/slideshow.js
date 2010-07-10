// jQuery Cycle script which powers the Slideshow: http://jquery.malsup.com/cycle/

var $j = jQuery.noConflict();

var $slidespeed = parseInt( meteorslidessettings.meteorslideshowspeed );

var $slidetimeout = parseInt( meteorslidessettings.meteorslideshowduration );

var $slidetransition = meteorslidessettings.meteorslideshowtransition;

$j(document).ready(function() {
    $j('.meteor-slides').cycle({
		fx: $slidetransition,
		speed: $slidespeed,
		timeout: $slidetimeout,
		pause: '1'
	});
});