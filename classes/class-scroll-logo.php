<?php

/**
 * Do the scroll logo functionality.
 *
 * @since  0.2.0
 */
class Mai_Effects_Scroll_Logo {

	function __construct() {
		add_action( 'customize_register', array( $this, 'customizer_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'inline_styles' ), 1010 ); // After Mai Theme Engine inline styles.
		add_filter( 'body_class',         array( $this, 'body_class' ) );
		add_filter( 'get_custom_logo',    array( $this, 'custom_logo' ) );
	}

	/**
	 * Register scroll logo customizer settings.
	 *
	 * @since   0.2.0
	 * @param   $wp_customize  The customize object.
	 * @return  void
	 */
	function customizer_settings( $wp_customize ) {

		$wp_customize->add_setting(
			'custom_scroll_logo',
			array(
				'theme_supports' => array( 'custom-logo' ),
			)
		);
		$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'custom_scroll_logo',
			array(
				'label'         => esc_attr__( 'Scroll Logo', 'mai-styles' ),
				'section'       => 'title_tagline',
				'priority'      => 9,
				'height'        => '120',
				'width'         => '240',
				'flex_height'   => true,
				'flex_width'    => true,
				'button_labels' => array(
					'select'       => __( 'Select logo', 'mai-styles' ),
					'change'       => __( 'Change logo', 'mai-styles' ),
					'remove'       => __( 'Remove', 'mai-styles' ),
					'default'      => __( 'Default', 'mai-styles' ),
					'placeholder'  => __( 'No logo selected', 'mai-styles' ),
					'frame_title'  => __( 'Select logo', 'mai-styles' ),
					'frame_button' => __( 'Choose logo', 'mai-styles' ),
				),
				'active_callback' => function() use ( $wp_customize ) {
					return ( maieffects_has_scroll_header() && ! empty( $wp_customize->get_setting( 'custom_logo' )->value() ) );
				},
			)
		) );

		if ( ! class_exists( 'Mai_Customize_Control_Slider' ) ) {
			return;
		}

		$wp_customize->add_setting( 'custom_scroll_logo_width',
			array(
				'default'           => 120,
				'sanitize_callback' => 'absint',
				'theme_supports'    => array( 'custom-logo' ),
			)
		);
		$wp_customize->add_control( new Mai_Customize_Control_Slider( $wp_customize, 'custom_scroll_logo_width',
			array(
				'label'       => esc_attr__( 'Scroll Logo Width', 'mai-styles' ),
				'section'     => 'title_tagline',
				'priority'    => 9,
				'input_attrs' => array(
					'min'  => 0,   // Required.
					'max'  => 800, // Required.
					'step' => 1,   // Required.
				),
				'active_callback' => function() use ( $wp_customize ) {
					return ( maieffects_has_scroll_header() && ! empty( $wp_customize->get_setting( 'custom_scroll_logo' )->value() ) );
				},
			)
		) );
	}



	/**
	 * Add inline CSS.
	 * Way late cause Engine changes stylesheet to 999.
	 *
	 * @since   0.2.0
	 *
	 * @link    http://www.billerickson.net/code/enqueue-inline-styles/
	 * @link    https://sridharkatakam.com/chevron-shaped-featured-parallax-section-in-genesis-using-clip-path/
	 *
	 * @return  void
	 */
	function inline_styles() {

		if ( ! get_theme_mod( 'custom_logo' ) ) {
			return;
		}

		$image_id = get_theme_mod( 'custom_scroll_logo' );
		if ( ! $image_id ) {
			return;
		}

		$image = wp_get_attachment_image_src( $image_id, 'full' );
		if ( ! $image ) {
			return;
		}

		$suffix = maieffects_get_suffix();
		$css    = file_get_contents( MAI_EFFECTS_PLUGIN_DIR . "assets/css/mai-scroll-logo{$suffix}.css" );

		// Scoll Logo dimensions.
		$width = $image[1];
		// $height = $image[2];

		$scroll_width = get_theme_mod( 'custom_scroll_logo_width' );
		if ( $scroll_width ) {
			// $height = round( $scroll_width / ( $width / $height ), 0 ); // aspect ratio
			$width  = $scroll_width;
		}


		$width_px  = absint( $width ) . 'px';
		// $shrink_px = absint( $width * .7 ) . 'px';

		/**
		 * This CSS should follow the logo width code
		 * in wp_add_inline_style();
		 *
		 * We have a check for reveal header here
		 * because sticky header shouldn't do any scroll logo stuff on mobile.
		 */
		// $css .= "
		// 	@media only screen and (max-width: 768px) {
		// 		.has-scroll-logo.has-reveal-header.scroll .custom-scroll-logo,
		// 		.has-scroll-logo.has-reveal-header.scroll .custom-logo-link {
		// 			max-width: {$shrink_px};
		// 		}
		// 	}
		// 	@media only screen and (min-width: 769px) {
		// 		.has-scroll-logo.scroll .custom-logo-link {
		// 			max-width: {$width_px};
		// 		}
		// 	}
		// ";
		// if ( mai_has_shrink_header() ) {
			// $css .= "
			// 	@media only screen and (min-width: 769px) {
			// 		.has-scroll-logo.scroll .custom-scroll-logo,
			// 		.has-scroll-logo.scroll .custom-logo-link {
			// 			max-width: {$shrink_px};
			// 		}
			// 	}
			// ";
			$css .= "
				@media only screen and (min-width: 769px) {
					.has-scroll-logo.scroll .custom-scroll-logo,
					.has-scroll-logo.scroll .custom-logo-link {
						max-width: {$width_px};
					}
				}
			";
		// }

		wp_add_inline_style( maieffects_get_handle(), $css );
	}

	/**
	 * Add custom body class.
	 *
	 * @param   array  The existing body classes.
	 *
	 * @return  array  Modified classes.
	 */
	function body_class( $classes ) {
		if ( maieffects_has_scroll_logo() ) {
			$classes[] = 'has-scroll-logo';
		}
		return $classes;
	}

	/**
	 * Display the scroll logo.
	 *
	 * @since   0.2.0
	 *
	 * @param   string  $html  The existing logo HTML.
	 *
	 * @return  string  The modified HTML.
	 */
	function custom_logo( $html ) {
		if ( ! maieffects_has_scroll_logo() ) {
			return $html;
		}
		$image = wp_get_attachment_image( get_theme_mod( 'custom_scroll_logo' ), 'full', false, array( 'class' => 'custom-scroll-logo', 'style' => 'display:none;' ) );
		// $image = wp_get_attachment_image( get_theme_mod( 'custom_scroll_logo' ), 'full', false, array( 'class' => 'custom-scroll-logo' ) );
		if ( ! $image ) {
			return $html;
		}
		return str_replace( '</a>', $image . '</a>', $html );
	}

}

new Mai_Effects_Scroll_Logo();


/**
 * Pretty Printing
 *
 * @since   1.0.0
 * @author  Chris Bratlien
 *
 * @param   mixed $obj
 * @param   string $label
 *
 * @return  null
 */
function mai_pp( $obj, $label = '' ) {
	$data = json_encode( print_r( $obj,true ) );
	?>
	<style type="text/css">
		#maiLogger {
			position: absolute;
			top: 30px;
			right: 0px;
			border-left: 4px solid #bbb;
			padding: 6px;
			background: white;
			color: #444;
			z-index: 999;
			font-size: 1.2rem;
			width: 40vw;
			height: calc( 100vh - 30px );
			overflow: scroll;
		}
	</style>
	<script type="text/javascript">
		var doStuff = function() {
			var obj    = <?php echo $data; ?>;
			var logger = document.getElementById('maiLogger');
			if ( ! logger ) {
				logger = document.createElement('div');
				logger.id = 'maiLogger';
				document.body.appendChild(logger);
			}
			////console.log(obj);
			var pre = document.createElement('pre');
			var h2  = document.createElement('h2');
			pre.innerHTML = obj;
			h2.innerHTML  = '<?php echo addslashes($label); ?>';
			logger.appendChild(h2);
			logger.appendChild(pre);
		};
		window.addEventListener( "DOMContentLoaded", doStuff, false );
	</script>
	<?php
}
