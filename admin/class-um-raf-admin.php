<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link https://www.trestian.com
 * @since 1.0.0
 *
 * @package Um_Resend_Activation_Form
 * @subpackage Um_Resend_Activation_Form/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package Um_Resend_Activation_Form
 * @subpackage Um_Resend_Activation_Form/public
 * @author Yaron Guez <yaron@trestian.com>
 */
class Um_Raf_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Initialize functions
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'tgmpa_register', array( $this, 'required_plugins' ) );
		add_filter( 'um_core_pages', array( $this, 'add_resend_page' ), 100, 1 );
	}

	/**
	 * Register required plugins
	 */
	public function required_plugins() {
		// Required plugins.
		$plugins = array(
			// Require Ultimate Member.
			array(
				'name'     => 'Ultimate Member',
				'slug'     => 'ultimate-member',
				'required' => true,
			),
		);

		$config = array(
			'id'           => 'um-resend-activation-form',  // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',   // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'plugins.php', // Parent menu slug.
			'capability'   => 'manage_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true, // Show admin notices or not.
			'dismissable'  => true, // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',   // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,    // Automatically activate plugins after installation or not.
			'message'      => '',   // Message to output right before the plugins table.
			'strings'      => array(
				/* translators: 1: plugins count */
				'notice_can_install_required'  => _n_noop(
					'Ultimate Member Resend Activation Form requires the following plugin: %1$s.',
					'Ultimate Member Resend Activation Form requires the following plugins: %1$s.',
					'um_raf'
				),
				/* translators: 1: plugins count */
				'notice_can_activate_required' => _n_noop(
					'The following plugin, required by Ultimate Member Resend Activation Form, is currently inactive: %1$s.',
					'The following plugins, required by Ultimate Member Resend Activation Form, are currently inactive: %1$s.',
					'um_raf'
				),
			),

		);

		tgmpa( $plugins, $config );
	}

	/**
	 * Add page for resend activation
	 *
	 * @param array $pages Pages array.
	 * @return array
	 */
	public function add_resend_page( $pages ) {
		$pages['resend-activation'] = array(
			'title' => __( 'Resend Activation Email', 'um_raf' ),
		);
		return $pages;
	}
}
