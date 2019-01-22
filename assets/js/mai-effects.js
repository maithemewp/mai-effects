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
		var $image   = $section.find( '.parallax-image' );

		// Parallax.
		var parallaxScene = new ScrollMagic.Scene({
			triggerElement: $section[0],
			triggerHook: 'onEnter',
			duration: getDuration(),
		})
		.on( 'progress', function(e) {
			// var distance = '-' + e.progress * 20 + '%';
			var distance = e.progress * 30 + '%';
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

	var fades = [];

	// Fade In.
	$( '.section.fadein' ).each( function(e){

		var $section = $(this);
		var $content = $section.find( '.section-content' );

		var fadeInScene = new ScrollMagic.Scene({
			triggerElement: $section[0],
			triggerHook: .8, // 20% up the page.
			duration: '30%',
		})
		.on( 'progress', function(e) {
			$content.css({
				'opacity': e.progress,
			});
		})
		.on( 'enter', function(e) {
			$content.addClass( 'enter' );
		})
		.on( 'end', function(e) {
			$content.addClass( 'end' );
		})
		// .addIndicators()
		.addTo(controller);

		// Add to our fades array.
		fades.push(fadeInScene);
	});

	// Fade In Up.
	$( '.section.fadeinup' ).each( function(e){

		var $section = $(this);
		var $content = $section.find( '.section-content' );

		var fadeInUpScene = new ScrollMagic.Scene({
			triggerElement: $section[0],
			triggerHook: .8, // 20% up the page.
			duration: '30%',
		})
		.on( 'progress', function(e) {
			var transform = ( 48 - ( e.progress * 48 ) ) + 'px';
			$content.css({
				'opacity': e.progress,
				'transform': 'translateY(' + transform + ')',
			});
		})
		// .addIndicators()
		.addTo(controller);

		// Add to our fades array.
		fades.push(fadeInUpScene);
	});

	// Fade In Down.
	$( '.section.fadeindown' ).each( function(e){

		var $section = $(this);
		var $content = $section.find( '.section-content' );

		var fadeInDownScene = new ScrollMagic.Scene({
			triggerElement: $section[0],
			triggerHook: .8, // 20% up the page.
			duration: '30%',
		})
		.on( 'progress', function(e) {
			var transform = ( 48 - ( e.progress * 48 ) ) + 'px';
			$content.css({
				'opacity': e.progress,
				'transform': 'translateY(-' + transform + ')',
			});
		})
		// .addIndicators()
		.addTo(controller);

		// Add to our fades array.
		fades.push(fadeInDownScene);
	});

	// Fade In Left.
	$( '.section.fadeinleft' ).each( function(e){

		var $section = $(this);
		var $content = $section.find( '.section-content' );

		var fadeInLeftScene = new ScrollMagic.Scene({
			triggerElement: $section[0],
			triggerHook: .8, // 20% up the page.
			duration: '30%',
		})
		.on( 'progress', function(e) {
			var transform = ( 48 - ( e.progress * 48 ) ) + 'px';
			$content.css({
				'opacity': e.progress,
				'transform': 'translateX(' + transform + ')',
			});
		})
		// .addIndicators()
		.addTo(controller);

		// Add to our fades array.
		fades.push(fadeInLeftScene);
	});

	// Fade In Right.
	$( '.section.fadeinright' ).each( function(e){

		var $section = $(this);
		var $content = $section.find( '.section-content' );

		var fadeInRightScene = new ScrollMagic.Scene({
			triggerElement: $section[0],
			triggerHook: .8, // 20% up the page.
			duration: '30%',
		})
		.on( 'progress', function(e) {
			var transform = ( 48 - ( e.progress * 48 ) ) + 'px';
			$content.css({
				'opacity': e.progress,
				'transform': 'translateX(-' + transform + ')',
			});
		})
		// .addIndicators()
		.addTo(controller);

		// Add to our fades array.
		fades.push(fadeInRightScene);
	});

	// Loop through all of our fades.
	for ( var i = 0; i < fades.length; i++ ) {
		/**
		 * Determine whether any of the fade sections are
		 * more than 50% down the page (triggerHook is 20% and duration is 30%).
		 * If so, remove the intial class which handles our keyframe fade.
		 * This allows ScrollMagic to do the fade tied to scroll position.
		 */
		var $section     = $( fades[i].triggerElement() );
		var windowHeight = $window.height();
		var offset       = $section.offset().top;
		var top          = offset - $(document).scrollTop();
		var percent      = Math.floor( top / windowHeight * 100 );
		var midFade      = ( percent >= 50 );

		// Skip if not midFade.
		if ( ! midFade ) {
			continue;
		}

		// Remove the initial class.
		$section.removeClass( 'initial' );
	}

})( document, jQuery );
