<?php

/**
 * Do the scroll logo functionality.
 *
 * @since  0.2.0
 */
class Mai_Effects_Scroll_Logo {

	function __construct() {
		add_action( 'customize_register', array( $this, 'customizer_settings' ) );
		add_filter( 'get_custom_logo',    array( $this, 'custom_logo' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'inline_styles' ), 1010 ); // After Mai Theme Engine inline styles.
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
						return ( maistyles_has_scroll() && ! empty( $wp_customize->get_setting( 'custom_logo' )->value() ) );
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
				return ( maistyles_has_scroll() && ! empty( $wp_customize->get_setting( 'custom_scroll_logo' )->value() ) );
			},
		));

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
		$logo_id = get_theme_mod( 'custom_scroll_logo' );
		if ( ! $logo_id ) {
			return $html;
		}
		$image = wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => 'custom-scroll-logo' ) );
		if ( ! $image ) {
			return $html;
		}
		return str_replace( '</a>', $image . '</a>', $html );
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
		$css = "
			@media only screen and (max-width: 768px) {
				.has-scroll-logo.scroll .custom-scroll-logo,
				.has-scroll-logo.scroll .custom-logo-link {
					max-width: {$shrink_px};
				}
			}
			@media only screen and (min-width: 769px) {
				.has-scroll-logo.scroll .custom-scroll-logo,
				.has-scroll-logo.scroll .custom-logo-link {
					max-width: {$width_px};
				}
			}
		";
		if ( mai_has_shrink_header() ) {
			$css .= "
				.has-scroll-logo.scroll .custom-scroll-logo,
				@media only screen and (min-width: 769px) {
					.has-scroll-logo.scroll .custom-logo-link {
						max-width: {$shrink_px};
					}
				}
			";
		}
		$handle = ( defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ) ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme';
		wp_add_inline_style( $handle, $css );
	}

	/**
	 * Add custom CSSfor scroll logo functionality.
	 *
	 * Originally discovered a filter here https://github.com/aristath/kirki/issues/908
	 * but it has sinced changed to what we're using here.
	 *
	 * @since   0.2.0
	 *
	 * @uses    kirki/{$config_id}/styles
	 *
	 * @param   array  $css  The existing CSS config.
	 * @return  array  $css  The modified CSS config.
	 */
	function kirki_styles( $css ) {

		return $css;

		if ( ! maistyles_has_scroll_logo() ) {
			return $css;
		}

		$image = wp_get_attachment_image_src( $image_id, 'full' );
		if ( ! $image ) {
			return $css;
		}

		// Scoll Logo dimensions.
		$width  = $image[1];
		// $height = $image[2];

		$scroll_width = get_theme_mod( 'custom_scroll_logo_width' );
		if ( $scroll_width ) {
			// $height = round( $scroll_width / ( $width / $height ), 0 ); // aspect ratio
			$width  = $scroll_width;
		}

		// Image size on scroll.
		$css['@media only screen and (min-width: 545px)']['body.scroll .custom-logo-link']['max-width'] = sprintf( '%spx', $width );
		$css['@media only screen and (min-width: 545px)']['.custom-scroll-logo']['max-width'] = sprintf( '%spx', $width );

		return $css;
	}

}

new Mai_Effects_Scroll_Logo();
