// Parallax.
document.querySelectorAll( '.parallax' ).forEach( function(element) {

	var image = element.querySelector( '.bg-image' );

	// Skip if no image.
	if ( ! image ) {
		return;
	}

	var parallaxScroll = basicScroll.create({
		elem: element,
		from: 'top-bottom',
		to: 'bottom-top',
		direct: image,
		props: {
			'--translateY': {
				from: '-20%',
				to: '20%',
			}
		},
	})
	.start();

});

// Fades.
document.querySelectorAll( '.has-fadein, .has-fadeinup, .has-fadeindown, .has-fadeinleft, .has-fadeinright' ).forEach( function(element) {

	var fadeClassAdded = false;

	var fadeScroll = basicScroll.create({
		elem: element,
		from: 'top-bottom',
		to: 'top-top',
		direct: true,
		inside: (instance, percentage, props) => {
			if ( fadeClassAdded ) {
				return;
			}
			if ( percentage <= 20 ) {
				return;
			}
			element.classList.add( 'doFade' );
			fadeClassAdded = true;
		},
		outside: (instance, percentage, props) => {
			// Fade in if loading the page after it's already scrolled past.
			if ( ( percentage >= 100 ) && ! fadeClassAdded ) {
				element.classList.add( 'doFade' );
				fadeClassAdded = true;
			}
		}
	})
	.start();

});
