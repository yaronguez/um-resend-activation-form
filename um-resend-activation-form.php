<?php
/**
 * Plugin Name:       Ultimate Member - Resend Activation Email Form
 * Plugin URI:        https://github.com/yaronguez/um-resend-activation-form
 * Description:       Adds a [um_resend_activation_form] shortcode allowing users to resend their account activation email
 * Version:           1.0.3
 * Author:            Yaron Guez
 * Author URI:        https://www.trestian.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       um_raf
 * Domain Path:       /languages/
 * GitHub Plugin URI: https://github.com/yaronguez/um-resend-activation-form
 *
 * @link              https://www.trestian.com
 * @since             1.0.0
 * @package           Um_Resend_Activation_Form
 *
 * @wordpress-plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'UM_RAF_PLUGIN_FILE' ) ) {
	define( 'UM_RAF_PLUGIN_FILE', __FILE__ );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-um-raf-activator.php
 */
function activate_um_resend_activation_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-um-raf-activator.php';
	Um_Raf_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-um-raf-deactivator.php
 */
function deactivate_um_resend_activation_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-um-raf-deactivator.php';
	Um_Raf_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_um_resend_activation_form' );
register_deactivation_hook( __FILE__, 'deactivate_um_resend_activation_form' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-um-resend-activation-form.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_um_resend_activation_form() {
	$plugin = new Um_Resend_Activation_Form();
	$plugin->run();
}
run_um_resend_activation_form();
