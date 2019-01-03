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
		// add_filter( 'kirki_mai_styles_styles', array( $this, 'kirki_styles' ) );
	}

	/**
	 * Register scroll logo customizer settings.
	 *
	 * @since   0.2.0
	 *
	 * @param   $wp_customize  The customize object.
	 *
	 * @return  void
	 */
	function customizer_settings( $wp_customize ) {

		$wp_customize->add_setting(
			'custom_scroll_logo',
			array(
				'theme_supports' => array( 'custom-logo' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Cropped_Image_Control(
				$wp_customize,
				'custom_scroll_logo',
				array(
					'label'         => __( 'Scroll Logo' ),
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
			)
		);

		$wp_customize->add_setting(
			'custom_scroll_logo_width',
			array(
				'theme_supports' => array( 'custom-logo' ),
			)
		);
		$wp_customize->add_control( 'custom_scroll_logo_width', array(
			'type'        => 'number',
			'priority'    => 9,
			'section'     => 'title_tagline',
			'label'       => __( 'Scroll Logo Width (in px)', 'mai-styles' ),
			'description' => '',
			'input_attrs' => array(
				'min'         => 0,
				'step'        => 1,
				'placeholder' => '180',
			),
			'active_callback' => function() use ( $wp_customize ) {
				return ( maieffects_has_scroll_header() && ! empty( $wp_customize->get_setting( 'custom_scroll_logo' )->value() ) );
			},
		));

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
		$shrink_px = absint( $width * .7 ) . 'px';

		/**
		 * This CSS should follow the logo width code
		 * in wp_add_inline_style();
		 *
		 * We have a check for reveal header here
		 * because sticky header shouldn't do any scroll logo stuff on mobile.
		 */
		$css .= "
			@media only screen and (max-width: 768px) {
				.has-scroll-logo.has-reveal-header.scroll .custom-scroll-logo,
				.has-scroll-logo.has-reveal-header.scroll .custom-logo-link {
					max-width: {$shrink_px};
				}
			}
			@media only screen and (min-width: 769px) {
				.has-scroll-logo.scroll .custom-logo-link {
					max-width: {$width_px};
				}
			}
		";
		if ( mai_has_shrink_header() ) {
			$css .= "
				@media only screen and (min-width: 769px) {
					.has-scroll-logo.scroll .custom-scroll-logo,
					.has-scroll-logo.scroll .custom-logo-link {
						max-width: {$shrink_px};
					}
				}
			";
		}

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
		$image = wp_get_attachment_image( get_theme_mod( 'custom_scroll_logo' ), 'full', false, array( 'class' => 'custom-scroll-logo' ) );
		if ( ! $image ) {
			return $html;
		}
		return str_replace( '</a>', $image . '</a>', $html );
	}

}

new Mai_Effects_Scroll_Logo();
