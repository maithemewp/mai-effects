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
			triggerElement: $section,
			triggerHook: 'onEnter',
			duration: getDuration(),
		})
		.on( 'progress', function(e) {
			var distance = '-' + e.progress * 20 + '%';
			// jQuery 1.8+ handles browser prefixes.
			$image.css( 'transform', 'translateY(' + distance + ')' );
		})
		// .addIndicators()
		.addTo(controller);

		function getDuration() {
			return $window.height() + $section.height();
		}
		function updateDuration() {
			parallaxScene.offset( getDuration() );
		}

		// Update duration if browser on resize or similar shift.
		parallaxScene.on( 'shift', function(e) {
			updateDuration();
		});

	});

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

		fadeInUpScene.on( 'start', function(e) {
			if ( fadeInUpScene.progress() < 1 ) {
				$section.removeClass( 'initial' );
			}
		});

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

		// fadeInRightScene.setClassToggle( $section[0], 'inview' );

		// fadeInRightScene.on( 'start', function(e) {
			// console.log( fadeInRightScene.progress() );
		// })
	});


})( document, jQuery );
