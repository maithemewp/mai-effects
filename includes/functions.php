<?php

/**
 * Get the stylesheet handle.
 *
 * @since   0.3.0
 *
 * @return  string
 */
function maieffects_get_handle() {
	if ( function_exists( 'mai_get_handle' ) ) {
		return mai_get_handle();
	}
	return ( defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ) ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme';
}

/**
 * Get script suffix.
 *
 * @since   0.3.0
 *
 * @return  string
 */
function maieffects_get_suffix() {
	if ( function_exists( 'mai_get_suffix' ) ) {
		return mai_get_suffix();
	}
	$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	return $debug ? '' : '.min';
}

function maieffects_has_scroll_logo() {
	if ( ! maieffects_has_scroll_header() ) {
		return false;
	}
	if ( ! maieffects_has_scroll_logos() ) {
		return false;
	}
	return true;
}

function maieffects_has_scroll_header() {
	if ( function_exists( 'mai_has_scroll_header' ) ) {
		return mai_has_scroll_header();
	}
	$header_style = genesis_get_option( 'header_style' );
	return ( $header_style && in_array( $header_style, array( 'sticky', 'reveal', 'sticky_shrink', 'reveal_shrink' ) ) );
}

function maieffects_has_scroll_logos() {
	return ( function_exists( 'has_custom_logo' ) && has_custom_logo() && get_theme_mod( 'custom_scroll_logo' ) );
}
