<?php

/**
 * Plugin Name:     Mai Effects
 * Plugin URI:      https://maitheme.com
 * Description:     Add various section effects to add a little flair to your Mai Theme powered website.
 * Version:         0.1.0
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
			self::$instance->setup();
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
			define( 'MAI_EFFECTS_VERSION', '0.1.0' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'MAI_EFFECTS_PLUGIN_DIR' ) ) {
			define( 'MAI_EFFECTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
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
	 * Include required files.
	 *
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function includes() {
		foreach ( glob( MAI_EFFECTS_INCLUDES_DIR . '*.php' ) as $file ) { include $file; }
	}

	public function setup() {
		add_action( 'plugins_loaded',                     array( $this, 'updater' ) );
		add_action( 'customize_register',                 array( $this, 'customizer_settings' ), 24 ); // Mai Theme settings are registered on 20.
		add_action( 'wp_enqueue_scripts',                 array( $this, 'enqueue' ) );
		add_action( 'wp_enqueue_scripts',                 array( $this, 'inline_style' ), 1000 ); // Way late cause Engine changes stylesheet to 999.
		add_filter( 'genesis_theme_settings_defaults',    array( $this, 'genesis_defaults' ));
		add_filter( 'mai_banner_args',                    array( $this, 'banner_args' ) );
		add_filter( 'genesis_markup_banner-area_content', array( $this, 'section' ), 10, 2 );
		add_filter( 'genesis_markup_section_content',     array( $this, 'section' ), 10, 2 );
	}

	/**
	 * Setup the updater.
	 *
	 * @uses    https://github.com/YahnisElsts/plugin-update-checker/
	 *
	 * @return  void
	 */
	public function updater() {
		if ( ! is_admin() ) {
			return;
		}
		if ( ! class_exists( 'Puc_v4_Factory' ) ) {
			require_once MAI_EFFECTS_INCLUDES_DIR . 'vendor/plugin-update-checker/plugin-update-checker.php'; // 4.4
		}
		$updater = Puc_v4_Factory::buildUpdateChecker( 'https://github.com/maithemewp/mai-effects/', __FILE__, 'mai-effects' );
	}

	/**
	 * Register new Customizer elements.
	 *
	 * @param   WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	function customizer_settings( $wp_customize ) {

		if ( ! class_exists( 'Mai_Theme_Engine' ) ) {
			return;
		}

		if ( ! class_exists( 'Mai_Customize_Control_Multicheck' ) ) {
			return;
		}

		/* *************** *
		* Mai Banner Area *
		* *************** */

		$section        = 'mai_banner_area';
		$settings_field = 'genesis-settings';

		// Banner Effects.
		$wp_customize->add_setting(
			_mai_customizer_get_field_name( $settings_field, 'banner_effects' ),
			array(
				'default'           => _mai_customizer_multicheck_sanitize_key( mai_get_default_option( 'banner_effects' ) ),
				'type'              => 'option',
				'sanitize_callback' => '_mai_customizer_multicheck_sanitize_key',
			)
		);
		$wp_customize->add_control(
			new Mai_Customize_Control_Multicheck( $wp_customize,
				'banner_effects',
				array(
					'label'    => __( 'Banner Effects', 'mai-theme-engine' ),
					'section'  => $section,
					'settings' => _mai_customizer_get_field_name( $settings_field, 'banner_effects' ),
					'priority' => 8,
					'choices'  => array(
						'parallax'    => __( 'Parallax', 'mai-theme-engine' ),
						'fadeinup'    => __( 'Fade In/Up', 'mai-theme-engine' ),
						'fadeindown'  => __( 'Fade In/Down', 'mai-theme-engine' ),
						'fadeinleft'  => __( 'Fade In/Left', 'mai-theme-engine' ),
						'fadeinright' => __( 'Fade In/Right', 'mai-theme-engine' ),
					),
				)
			)
		);
	}

	function enqueue() {
		wp_register_script( 'mai-effects', MAI_EFFECTS_PLUGIN_URL . 'assets/js/mai-effects.min.js', array(), MAI_EFFECTS_VERSION, true );
	}

	function genesis_defaults( $defaults ) {
		$defaults['banner_effects'] = array();
		return $defaults;
	}

	function banner_args( $args ) {
		$effects = genesis_get_option( 'banner_effects' );
		if ( ! $effects ) {
			return $args;
		}
		wp_enqueue_script( 'mai-effects' );
		foreach ( $effects as $effect ) {
			$args['class'] .= ' ' . sanitize_html_class( $effect );
		}
		return $args;
	}

	function section( $content, $args ) {
		if ( ! isset( $args['params']['class'] ) ) {
			return $content;
		}
		if ( false  === strpos( $args['params']['class'], 'parallax' ) ) {
			return $content;
		}
		if ( ! isset( $args['params']['image'] ) || empty( $args['params']['image'] ) ) {
			return $content;
		}
		if ( ! isset( $args['params']['image_size'] ) || empty( $args['params']['image_size'] ) ) {
			return $content;
		}
		$image = wp_get_attachment_image( $args['params']['image'], $args['params']['image_size'], false, array( 'class' => 'parallax-image' ) );
		if ( $image ) {
			wp_enqueue_script( 'mai-effects' );
			$content = $image . $content;
		}
		return $content;
	}

	/**
	 * Add inline CSS.
	 *
	 * @since 0.3.0
	 *
	 * @link  http://www.billerickson.net/code/enqueue-inline-styles/
	 * @link  https://sridharkatakam.com/chevron-shaped-featured-parallax-section-in-genesis-using-clip-path/
	 */
	function inline_style() {
		$css = '
			/* Parallax */
			.section.parallax {
				background-image: none !important;
				position: relative;
				overflow: hidden;
			}

			.section.parallax .parallax-image {
				display: block;
				min-width: 100%;
				min-height: 100%;
				margin: auto;
				position: absolute;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
			}

			/* Fade */
			.js .fadeinup .section-content,
			.js .fadeinleft .section-content,
			.js .fadeinright .section-content {
				opacity: 0;
				overflow: hidden;
				-webkit-animation-duration: 1s;
				animation-duration: 1s;
				-webkit-animation-fill-mode: both;
				animation-fill-mode: both;
				-webkit-animation-timing-function: ease-in-out;
				animation-timing-function: ease-in-out;
			}

			.js .fadeInUp .section-content {
				-webkit-animation-name: fadeInUp;
				animation-name: fadeInUp;
			}
			.js .fadeInLeft .section-content {
				-webkit-animation-name: fadeInLeft;
				animation-name: fadeInLeft;
			}
			.js .fadeInRight .section-content {
				-webkit-animation-name: fadeInRight;
				animation-name: fadeInRight;
			}

			@-webkit-keyframes fadeInUp {
				from { opacity: 0; -webkit-transform: translateY(24px); }
				to { opacity: 1; -webkit-transform: translateY(0); }
			}

			@keyframes fadeInUp {
				from { opacity: 0; -webkit-transform: translateY(24px); transform: translateY(24px); }
				to { opacity: 1; -webkit-transform: translateY(0); transform: translateY(0); }
			}

			@-webkit-keyframes fadeInLeft {
				from { opacity: 0; -webkit-transform: translateX(48px); }
				to { opacity: 1; -webkit-transform: translateX(0); }
			}

			@keyframes fadeInLeft {
				from { opacity: 0; -webkit-transform: translateX(48px); transform: translateX(48px); }
				to { opacity: 1; -webkit-transform: translateX(0); transform: translateX(0); }
			}

			@-webkit-keyframes fadeInRight {
				from { opacity: 0; -webkit-transform: translateX(-48px); }
				to { opacity: 1; -webkit-transform: translateX(0); }
			}

			@keyframes fadeInRight {
				from { opacity: 0; -webkit-transform: translateX(-48px); transform: translateX(-48px); }
				to { opacity: 1; -webkit-transform: translateX(0); transform: translateX(0); }
			}
		';
		$handle = ( defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ) ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme';
		wp_add_inline_style( $handle, $css );
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
