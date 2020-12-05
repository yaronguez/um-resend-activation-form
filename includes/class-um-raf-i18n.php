<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link https://www.trestian.com
 * @since 1.0.0
 *
 * @package Um_Resend_Activation_Form
 * @subpackage Um_Resend_Activation_Form/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since 1.0.0
 * @package Um_Resend_Activation_Form
 * @subpackage Um_Resend_Activation_Form/includes
 * @author Yaron Guez <yaron@trestian.com>
 */
class Um_Raf_i18n {

	/**
	 * Initialize functions
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			$locale = is_admin() ? get_user_locale() : get_locale();
		}

		$locale = apply_filters( 'plugin_locale', $locale, 'um_raf' );

		unload_textdomain( 'um_raf' );
		load_textdomain( 'um_raf', WP_LANG_DIR . '/um-resend-activation-form/um_raf-' . $locale . '.mo' );
		load_plugin_textdomain( 'um_raf', false, plugin_basename( dirname( UM_RAF_PLUGIN_FILE ) ) . '/languages' );
	}
}
