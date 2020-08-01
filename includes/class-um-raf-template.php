<?php
/**
 * Helper functions for loading template files
 *
 * @since 1.0.0
 * @package Um_Resend_Activation_Form
 * @subpackage Um_Resend_Activation_Form/includes
 * @author Yaron Guez <yaron@trestian.com>
 */

/**
 * Template helper class
 */
class Um_Raf_Template {

	/**
	 * Gets the plugin file path
	 *
	 * @param string $path Path string.
	 *
	 * @return string
	 */
	public static function get_path( $path ) {
		return plugin_dir_path( dirname( __FILE__ ) ) . $path;
	}

	/**
	 * Load a template while allowing theme and developers to override it
	 *
	 * @access public
	 * @param string $name Template name(default: '').
	 * @param array  $data Template args data.
	 */
	public static function get_template( $name = '', $data = array() ) {
		$template = '';

		// Look in yourtheme/name.php.
		if ( $name ) {
			$template = locate_template( array( "{$name}.php" ) );
		}

		// Look for template in partials directory.
		if ( ! $template && $name && file_exists( self::get_path( "templates/{$name}.php" ) ) ) {
			$template = self::get_path( "templates/{$name}.php" );
		}

		// Allow 3rd party plugins to filter template file from their plugin.
		$template = apply_filters( 'um_raf_template_path', $template, $name );

		if ( ! $template ) {
			return;
		}

		// Load globals to be accessible in template.
		global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

		// Load any query variables to be accessible in template.
		if ( is_array( $wp_query->query_vars ) ) {
			extract( $wp_query->query_vars, EXTR_SKIP ); // @codingStandardsIgnoreLine
		}

		// If a search variable was loaded from the query vars, escape its contents.
		if ( isset( $s ) ) {
			$s = esc_attr( $s );
		}

		// Load any data passed in as array.
		extract( $data ); // @codingStandardsIgnoreLine

		// Launch the template!
		require $template;
	}
}
