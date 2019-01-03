<?php

/**
 * Plugin Name:     Mai Effects
 * Plugin URI:      https://maitheme.com
 * Description:     Add various section effects to add a little flair to your Mai Theme powered website.
 * Version:         0.2.0
 *
 * Author:          BizBudding, Mike Hemberger
 * Author URI:      https://bizbudding.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main Mai_Effects Class.
 *
 * @since 1.0.0
 */
final class Mai_Effects {

	/**
	 * @var Mai_Effects The one true Mai_Effects
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Main Mai_Effects Instance.
	 *
	 * Insures that only one instance of Mai_Effects exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since   1.0.0
	 * @static  var array $instance
	 * @uses    Mai_Effects::setup_constants() Setup the constants needed.
	 * @uses    Mai_Effects::includes() Include the required files.
	 * @uses    Mai_Effects::setup() Activate, deactivate, etc.
	 * @see     Mai_Effects()
	 * @return  object | Mai_Effects The one true Mai_Effects
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			// Setup the setup
			self::$instance = new Mai_Effects;
			// Methods
			self::$instance->setup_constants();
			self::$instance->includes();
			self::$instance->run();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @return  void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mai-effects' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @return  void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mai-effects' ), '1.0' );
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function setup_constants() {

		// Plugin version.
		if ( ! defined( 'MAI_EFFECTS_VERSION' ) ) {
			define( 'MAI_EFFECTS_VERSION', '0.2.0' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'MAI_EFFECTS_PLUGIN_DIR' ) ) {
			define( 'MAI_EFFECTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Classes Path.
		if ( ! defined( 'MAI_EFFECTS_CLASSES_DIR' ) ) {
			define( 'MAI_EFFECTS_CLASSES_DIR', MAI_EFFECTS_PLUGIN_DIR . 'classes/' );
		}

		// Plugin Includes Path.
		if ( ! defined( 'MAI_EFFECTS_INCLUDES_DIR' ) ) {
			define( 'MAI_EFFECTS_INCLUDES_DIR', MAI_EFFECTS_PLUGIN_DIR . 'includes/' );
		}

		// Plugin Folder URL.
		if ( ! defined( 'MAI_EFFECTS_PLUGIN_URL' ) ) {
			define( 'MAI_EFFECTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'MAI_EFFECTS_PLUGIN_FILE' ) ) {
			define( 'MAI_EFFECTS_PLUGIN_FILE', __FILE__ );
		}

		// Plugin Base Name
		if ( ! defined( 'MAI_EFFECTS_BASENAME' ) ) {
			define( 'MAI_EFFECTS_BASENAME', dirname( plugin_basename( __FILE__ ) ) );
		}

	}

	/**
	 * Include vendor libraries.
	 *
	 * composer require yahnis-elsts/plugin-update-checker
	 *
	 * v4.5  Plugin Update Checker
	 *
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function includes() {
		// Vendor.
		require_once __DIR__ . '/vendor/autoload.php';
		// Classes.
		foreach ( glob( MAI_EFFECTS_CLASSES_DIR . '*.php' ) as $file ) { include_once $file; }
		// Includes.
		foreach ( glob( MAI_EFFECTS_INCLUDES_DIR . '*.php' ) as $file ) { include_once $file; }
	}

	/**
	 * Setup the plugin.
	 *
	 * @since   0.1.0
	 * @return  void
	 */
	public function run() {

		// Updater.
		add_action( 'plugins_loaded', array( $this, 'updater' ) );

		// Notice.
		if ( ! $this->should_run() ) {
			add_action( 'admin_init', function() {
				deactivate_plugins( plugin_basename( __FILE__ ) );
			});
			add_action( 'admin_notices', function() {
				printf( '<div class="notice notice-warning"><p>%s</p></div>', __( 'Mai Effects requires Mai Theme Engine plugin v1.7.0 or higher. As a result, this plugin has been deactivated.', 'mai-styles' ) );
			});
			return;
		}

		add_action( 'customize_register',                 array( $this, 'customizer_settings' ), 24 ); // Mai Theme settings are registered on 20.
		add_action( 'cmb2_admin_init',                    array( $this, 'metabox_settings' ), 14 ); // Mai Theme settings are registered on default/10.
		add_action( 'wp_enqueue_scripts',                 array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts',                 array( $this, 'inline_style' ), 1000 ); // Way late cause Engine changes stylesheet to 999.
		add_filter( 'genesis_theme_settings_defaults',    array( $this, 'genesis_defaults' ) );
		add_filter( 'mai_valid_section_args',             array( $this, 'validate_args' ) );
		add_filter( 'mai_banner_args',                    array( $this, 'banner_args' ) );
		add_filter( 'shortcode_atts_section',             array( $this, 'section_atts' ), 10, 3 );
		add_filter( 'mai_section_args',                   array( $this, 'section_args' ), 20, 2 );
		add_filter( 'genesis_markup_banner-area_content', array( $this, 'section_content' ), 10, 2 );
		add_filter( 'genesis_markup_section_content',     array( $this, 'section_content' ), 10, 2 );
	}

	/**
	 * Should we run our code?
	 *
	 * @since   0.1.0
	 *
	 * @return  bool
	 */
	public function should_run() {
		// If not running Mai Theme.
		if ( ! is_plugin_active( 'mai-theme-engine/mai-theme-engine.php' ) ) {
			return false;
		}
		// If we don't know the version.
		if ( ! defined( 'MAI_THEME_ENGINE_VERSION' ) ) {
			return false;
		}
		// If not running at least Mai Theme Engine 1.7.0.
		if ( version_compare( MAI_THEME_ENGINE_VERSION, '1.7.0', '<' ) ) {
			return false;
		}
		// Run!
		return true;
	}

	/**
	 * Setup the updater.
	 *
	 * composer require yahnis-elsts/plugin-update-checker
	 *
	 * @since   0.1.0
	 * @uses    https://github.com/YahnisElsts/plugin-update-checker/
	 * @return  void
	 */
	public function updater() {
		if ( ! is_admin() ) {
			return;
		}
		if ( ! class_exists( 'Puc_v4_Factory' ) ) {
			return;
		}
		$updater = Puc_v4_Factory::buildUpdateChecker( 'https://github.com/maithemewp/mai-effects/', __FILE__, 'mai-effects' );
	}

	/**
	 * Register scripts.
	 * Will be enqueued later, as needed.
	 *
	 * @since   0.1.0
	 *
	 * @return  void
	 */
	function register_scripts() {
		$suffix = maieffects_get_suffix();
		wp_register_script( 'mai-effects', MAI_EFFECTS_PLUGIN_URL . "assets/js/mai-effects{$suffix}.js", array(), MAI_EFFECTS_VERSION, true );
	}

	/**
	 * Add inline CSS.
	 *
	 * @since   0.1.0
	 *
	 * @return  void
	 */
	function inline_style() {
		$suffix = maieffects_get_suffix();
		$css    = file_get_contents( MAI_EFFECTS_PLUGIN_DIR . "assets/css/mai-effects{$suffix}.css" );
		wp_add_inline_style( maieffects_get_handle(), $css );
	}

	/**
	 * Set default empty values for our new custom settings.
	 *
	 * @since   0.1.0
	 *
	 * @param   array  $defaults  The existing defaults.
	 *
	 * @return  array  The modified defaults.
	 */
	function genesis_defaults( $defaults ) {
		$defaults['banner_effects']         = '';
		$defaults['banner_content_effects'] = '';
		return $defaults;
	}

	/**
	 * Validate (whitelist) the args so they can be passed to our sections.
	 * This is only important for Sections template in the current CMB2 structure.
	 *
	 * @since   0.1.0
	 *
	 * @param   array  $args  The existing args.
	 *
	 * @return  array  The modified args.
	 */
	function validate_args( $args ) {
		$args[] = 'effects';
		$args[] = 'content_effects';
		return $args;
	}

	/**
	 * Maybe add the banner effects to the args passed to the section helper.
	 *
	 * @since   0.1.0
	 *
	 * @param   array  $args  The existing args.
	 *
	 * @return  array  The modified args.
	 */
	function banner_args( $args ) {
		$effects = array(
			'effects'         => genesis_get_option( 'banner_effects' ),
			'content_effects' => genesis_get_option( 'banner_content_effects' ),
		);
		foreach ( $effects as $key => $value ) {
			if ( empty( $value ) ) {
				continue;
			}
			$args[ $key ] = $value;
		}
		return $args;
	}

	/**
	 * Allow our new section settings to get passed to the args/output of Mai_Section class.
	 *
	 * @since   0.1.0
	 *
	 * @param   array  $out    The output array of shortcode attributes.
	 * @param   array  $pairs  The supported attributes and their defaults.
	 * @param   array  $atts   The user defined shortcode attributes.
	 *
	 * @return  array  The modified output.
	 */
	function section_atts( $out, $pairs, $atts ) {
		$settings = array( 'effects', 'content_effects' );
		foreach ( $settings as $setting ) {
			if ( isset( $atts[ $setting ] ) && ! empty( $atts[ $setting ] ) ) {
				$out[ $setting ] = $atts[ $setting ];
			}
		}
		return $out;
	}

	/**
	 * Add necessary classes to make effects work.
	 * Enqueue the main script if it hasn't been yet.
	 *
	 * @since   0.1.0
	 *
	 * @param   array  $args           The existing section args.
	 * @param   array  $original_args  The original args passed to the section.
	 *
	 * @return  array  The modified args.
	 */
	function section_args( $args, $original_args ) {
		// Cache.
		static $has_effects = false;
		static $enqueued    = false;
		// Effects.
		$effect         = ( isset( $args['effects'] ) && ! empty( $args['effects'] ) ) ? $args['effects']: false;
		$content_effect = ( isset( $args['content_effects'] ) && ! empty( $args['content_effects'] ) ) ? $args['content_effects'] : false;
		if ( $effect ) {
			$has_effects = true;
			$args['class'] = mai_add_classes( sanitize_html_class( $effect ), $args['class'] );
		}
		if ( $content_effect ) {
			$has_effects = true;
			$args['class'] = mai_add_classes( sanitize_html_class( $content_effect ), $args['class'] );
		}
		if ( $has_effects ) {
			if ( ! $enqueued ) {
				wp_enqueue_script( 'mai-effects' );
				$enqueued = true;
			}
		}
		return $args;
	}

	/**
	 * Add the new inline image as the first element of the section content.
	 * This is the structure needed for parallax to work.
	 *
	 * @since   0.1.0
	 *
	 * @param   string  $content  The existing section content.
	 * @param   array   $args     The section args.
	 *
	 * @return  string|HTML
	 */
	function section_content( $content, $args ) {
		if ( ! isset( $args['params']['effects'] ) || 'parallax' !== $args['params']['effects'] ) {
			return $content;
		}
		if ( ! isset( $args['params']['image'] ) || empty( $args['params']['image'] ) ) {
			return $content;
		}
		$image_size = ( isset( $args['params']['image_size'] ) && ! empty( $args['params']['image_size'] ) ) ? $args['params']['image_size']: 'section';
		// $image_data = wp_get_attachment_image_src( $args['params']['image'], $image_size );
		// $image      = wp_get_attachment_image( $args['params']['image'], $image_size, false, array( 'class' => 'parallax-image', 'data-rellax-percentage' => '0.5' ) );
		// $image      = wp_get_attachment_image( $args['params']['image'], $image_size, false, array( 'class' => 'parallax-image' ) );
		$image      = wp_get_attachment_image( $args['params']['image'], $image_size, false, array( 'class' => 'parallax-image' ) );
		// if ( $image_data ) {
		if ( $image ) {
			// <div class="parallax" style="background-image: url('images/sea.jpg');" parallax></div>
			// $image   = sprintf( '<div class="parallax" style="background-image:url(%s);" parallax></div>', $image_data[0] );
			// $image   = sprintf( '<div class="parallax-image" style="background-image:url(%s);"></div>', $image_data[0] );
			// $image   = sprintf( '<div class="parallax__image" data-parallax-image="%s"></div>', $image_data[0] );
			// $image   = sprintf( '<div class="parallax-wrap">%s</div>', $image );
			$content = $image . $content;
		}
		return $content;
	}

	/**
	 * Register new Customizer elements.
	 *
	 * @since   0.1.0
	 *
	 * @param   WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 *
	 * @return  void
	 */
	function customizer_settings( $wp_customize ) {

		/* *************** *
		* Mai Banner Area *
		* *************** */

		$section        = 'mai_banner_area';
		$settings_field = 'genesis-settings';

		// Background Effects.
		$wp_customize->add_setting(
			_mai_customizer_get_field_name( $settings_field, 'banner_effects' ),
			array(
				'default'           => _mai_customizer_multicheck_sanitize_key( mai_get_default_option( 'banner_effects' ) ),
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_key',
			)
		);
		$wp_customize->add_control( _mai_customizer_get_field_name( $settings_field, 'banner_effects' ), array(
			'label'    => __( 'Background Effects', 'mai-theme-engine' ),
			'section'  => $section,
			'type'     => 'select',
			'priority' => 8,
			'choices'  => array(
				''         => __( '- None -', 'genesis' ),
				'parallax' => __( 'Parallax', 'mai-theme-engine' ),
			),
		) );

		// Content Effects.
		$wp_customize->add_setting(
			_mai_customizer_get_field_name( $settings_field, 'banner_content_effects' ),
			array(
				'default'           => _mai_customizer_multicheck_sanitize_key( mai_get_default_option( 'banner_content_effects' ) ),
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_key',
			)
		);
		$wp_customize->add_control( _mai_customizer_get_field_name( $settings_field, 'banner_content_effects' ), array(
			'label'    => __( 'Content Effects', 'mai-theme-engine' ),
			'section'  => $section,
			'type'     => 'select',
			'priority' => 8,
			'choices'  => array(
				''            => __( '- None -', 'genesis' ),
				'fadein'      => __( 'Fade In', 'mai-theme-engine' ),
				'fadeinup'    => __( 'Fade In/Up', 'mai-theme-engine' ),
				'fadeindown'  => __( 'Fade In/Down', 'mai-theme-engine' ),
				'fadeinleft'  => __( 'Fade In/Left', 'mai-theme-engine' ),
				'fadeinright' => __( 'Fade In/Right', 'mai-theme-engine' ),
			),
		) );
	}

	/**
	 * Register settings to the existing Sections template metabox.
	 *
	 * @since   0.1.0
	 *
	 * @return  void
	 */
	function metabox_settings() {

		// Get the sections metabox.
		$sections = cmb2_get_metabox( 'mai_sections' );

		// Bail if this metabox is not registered.
		if ( ! $sections ) {
			return;
		}

		// Background Effects.
		$sections->add_group_field( 'mai_sections', array(
			'name'              => __( 'Background Effects', 'mai-theme-engine' ),
			'id'                => 'effects',
			'type'              => 'select',
			'select_all_button' => false,
			'options'           => array(
				''         => __( '- None -', 'genesis' ),
				'parallax' => __( 'Parallax', 'mai-theme-engine' ),
			),
		), 4 );

		// Content Effects.
		$sections->add_group_field( 'mai_sections', array(
			'name'              => __( 'Content Effects', 'mai-theme-engine' ),
			'id'                => 'content_effects',
			'type'              => 'select',
			'select_all_button' => false,
			'options'           => array(
				''            => __( '- None -', 'genesis' ),
				'fadein'      => __( 'Fade In', 'mai-theme-engine' ),
				'fadeinup'    => __( 'Fade In/Up', 'mai-theme-engine' ),
				'fadeindown'  => __( 'Fade In/Down', 'mai-theme-engine' ),
				'fadeinleft'  => __( 'Fade In/Left', 'mai-theme-engine' ),
				'fadeinright' => __( 'Fade In/Right', 'mai-theme-engine' ),
			),
		), 5 );
	}
}

/**
 * The main function for that returns Mai_Effects
 *
 * The main function responsible for returning the one true Mai_Effects
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $plugin = Mai_Effects(); ?>
 *
 * @since 1.0.0
 *
 * @return object|Mai_Effects The one true Mai_Effects Instance.
 */
function Mai_Effects() {
	return Mai_Effects::instance();
}

// Get Mai_Effects Running.
Mai_Effects();
