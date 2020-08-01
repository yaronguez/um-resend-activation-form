<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link https://www.trestian.com
 * @since 1.0.0
 *
 * @package Um_Resend_Activation_Form
 * @subpackage Um_Resend_Activation_Form/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since 1.0.0
 * @package Um_Resend_Activation_Form
 * @subpackage Um_Resend_Activation_Form/includes
 * @author  Yaron Guez <yaron@trestian.com>
 */
class Um_Resend_Activation_Form {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'um-resend-activation-form';
		$this->version     = '1.0.1';
		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Um_Resend_Activation_Form_Loader. Orchestrates the hooks of the plugin.
	 * - Um_Resend_Activation_Form_i18n. Defines internationalization functionality.
	 * - Um_Resend_Activation_Form_Admin. Defines all hooks for the admin area.
	 * - Um_Resend_Activation_Form_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-um-raf-i18n.php';

		/**
		 * Template helper class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-um-raf-template.php';

		/**
		 * AJAX helper class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-um-raf-ajax.php';

		/**
		 * TGM Plugin Activation library
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tgm-plugin-activation.php';

		/**
		 * Public facing actions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-um-raf-public.php';

		/**
		 * Admin facing actions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-um-raf-admin.php';

	}

	/**
	 * Define admin hook
	 */
	private function define_admin_hooks() {
		$admin = new Um_Raf_Admin( $this->get_plugin_name(), $this->get_version() );
		$admin->init();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Um_Resend_Activation_Form_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function set_locale() {
		$plugin_i18n = new Um_Raf_i18n();
		$plugin_i18n->init();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function define_public_hooks() {
		$plugin_public = new Um_Raf_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_public->init();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->set_locale();
		$this->define_public_hooks();
		$this->define_admin_hooks();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @return string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
