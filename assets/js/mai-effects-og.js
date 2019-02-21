( function( document, $, undefined ) {

	// Bail if ScrollMagic isn't loaded somehow.
	if ( 'function' !== typeof ScrollMagic ) {
		return;
	}

	var $window = $(window);

	// Setup ScrollMagic Controller.
	var controller = new ScrollMagic.Controller();

	// Parallax.
	$( '.section.parallax' ).each( function(e){

		var $section = $(this);
		var $image   = $section.find( '.bg-image' );

		// Parallax.
		var parallaxScene = new ScrollMagic.Scene({
			triggerElement: $section[0],
			triggerHook: 'onEnter',
			duration: getDuration(),
		})
		.on( 'progress', function(e) {
			// Total of 30% movement, but -15% to +15%.
			var distance = e.progress * 40 - 20 + '%';
			// jQuery 1.8+ handles browser prefixes.
			$image.css( 'transform', 'translateY(' + distance + ')' );
		})
		// .addIndicators()
		.addTo(controller);

		// Get the duration. Full window height plus the section height.
		function getDuration() {
			return $window.height() + $section.height();
		}
		// Update duration if browser on resize or similar shift.
		parallaxScene.on( 'shift', function(e) {
			parallaxScene.duration( getDuration() );
		});
	});

	// Fade Effects.
	$( '.has-fadein, .has-fadeinup, .has-fadeindown, .has-fadeinleft, .has-fadeinright' ).each( function(e) {

		var $element = $(this);

		var fadeScene = new ScrollMagic.Scene({
			triggerElement: $element[0],
			triggerHook: .9, // 10% up the page.
		})
		.on( 'enter', function(e) {
			$element.addClass( 'doFade' );
		})
		// .addIndicators()
		.addTo(controller);
	});

})( document, jQuery );
