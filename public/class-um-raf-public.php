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
class Um_Raf_Public {

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
	 * Whether or not a custom CSS exists
	 *
	 * @var bool
	 */
	private $has_custom_css;

	const NONCE_ACTION = 'um_raf_nonce';

	const CUSTOM_CSS = 'um_raf.css';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->has_custom_css = file_exists( get_stylesheet_directory() . '/' . self::CUSTOM_CSS ) ? true : false;
	}

	/**
	 * Initialize functions
	 */
	public function init() {
		// Enqueue scrips and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Register shortcode.
		add_shortcode( 'um_resend_activation_form', array( $this, 'output_form' ) );

		// Register ajax callbacks.
		add_action( 'wp_ajax_um_raf_submit', array( $this, 'process_submission' ) );
		add_action( 'wp_ajax_nopriv_um_raf_submit', array( $this, 'process_submission' ) );

		// Set resend links.
		add_action( 'um_after_login_fields', array( $this, 'add_resend_link_to_login_form' ), 1002 );
		add_action( 'um_raf_show_resend_link', array( $this, 'display_resend_link' ), 10, 1 );

		// Add captcha field.
		add_action( 'um_raf_before_button', array( $this, 'add_captcha' ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		// Register the form style to be enqued with shortcode.
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/um-raf-public.css', array(), $this->version, 'all' );

		// Register custom CSS if present.
		if ( $this->has_custom_css ) {
			wp_register_style( $this->plugin_name . '-custom', get_stylesheet_directory_uri() . '/' . self::CUSTOM_CSS, array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( $this->plugin_name . '-google-recapthca-api-v2', 'https://www.google.com/recaptcha/api.js' ); // @codingStandardsIgnoreLine
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/um-raf-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Check if captcha enabled?
	 *
	 * We only support version 2 right now.
	 *
	 * @since 1.0.2
	 * @author Vijay Hardaha
	 * @return bool
	 */
	public function captcha_enabled() {
		$enable       = false;
		$recaptcha    = UM()->options()->get( 'g_recaptcha_status' );
		$your_sitekey = UM()->options()->get( 'g_recaptcha_sitekey' );
		$your_secret  = UM()->options()->get( 'g_recaptcha_secretkey' );

		if ( $recaptcha ) {
			$enable = true;
		}

		if ( ! $your_sitekey || ! $your_secret ) {
			$enable = false;
		}

		return $enable;
	}

	/**
	 * Return shortcode output
	 *
	 * @return string
	 */
	public function output_form() {
		if ( ! class_exists( 'UM' ) ) {
			return apply_filters( 'um_raf_no_um_message', __( 'The plugin Ultimate Member is required and not currently installed.', 'um_raf' ) );
		}

		// Load the scripts and styles.
		wp_enqueue_style( $this->plugin_name );
		if ( $this->captcha_enabled() ) {
			wp_enqueue_script( $this->plugin_name . '-google-recapthca-api-v2' );
		}
		wp_enqueue_script( $this->plugin_name );

		// Load custom styles.
		if ( $this->has_custom_css ) {
			wp_enqueue_style( $this->plugin_name . '-custom' );
		}

		// Add the ajax URL.
		wp_localize_script(
			$this->plugin_name,
			'UM_RAF',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( self::NONCE_ACTION ),
			)
		);

		// Fetch the template output.
		ob_start();
		Um_Raf_Template::get_template( 'um-raf-output' );
		$output = ob_get_clean();

		// Return for shortcode.
		return $output;
	}

	/**
	 * Process form submission
	 *
	 * @return void
	 */
	public function process_submission() {
		Um_Raf_Ajax::check_missing_data( 'email', 'Email is required' );
		Um_Raf_Ajax::check_missing_data( 'nonce', 'Invalid form submission' );

		if ( ! check_ajax_referer( self::NONCE_ACTION, 'nonce', false ) ) {
			Um_Raf_Ajax::return_error( apply_filters( 'um_raf_invalid_nonce_message', __( 'The page has expired. Please refresh your browser.', 'um_raf' ) ) );
		}

		if ( $this->captcha_enabled() ) {
			Um_Raf_Ajax::check_missing_data( 'recaptcha_input', 'Invalid form submission - Recaptcha required' );
			$secretkey = UM()->options()->get( 'g_recaptcha_secretkey' );
			// Sends post request to the URL and tranforms response to JSON.
			$captcha = json_decode( file_get_contents( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secretkey . '&response=' . $_POST['recaptcha_input'] ) ); // @codingStandardsIgnoreLine
			if ( true !== $captcha->success ) {
				Um_Raf_Ajax::return_error( apply_filters( 'um_raf_invalid_nonce_message', __( 'Invalid recaptcha input - please reload the page and try again', 'um_raf' ) ) );
			}
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		if ( ! is_email( $email ) ) {
			Um_Raf_Ajax::return_error( __( 'Invalid email provided', 'um_raf' ) );
		}

		$user = get_user_by( 'email', $email );
		if ( false === $user ) {
			Um_Raf_Ajax::return_error( apply_filters( 'um_raf_no_user_message', __( 'No user exists with that email', 'um_raf' ) ) );
		}

		if ( $user->get( 'account_status' ) !== 'awaiting_email_confirmation' ) {
			Um_Raf_Ajax::return_error( apply_filters( 'um_raf_not_pending_message', __( 'That email address has already been confirmed.', 'um_raf' ) ) );
		}

		um_fetch_user( $user->ID );

		UM()->user()->email_pending();

		Um_Raf_Ajax::return_success( apply_filters( 'um_raf_email_sent_message', __( 'Your activation email has been resent.', 'um_raf' ) ) );
	}

	/**
	 * Add captcha field if enabled
	 *
	 * @since 1.0.2
	 * @author Vijay Hardaha
	 */
	public function add_captcha() {
		if ( $this->captcha_enabled() ) {
			$sitekey = UM()->options()->get( 'g_recaptcha_sitekey' );
			?>
			<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $sitekey ); ?>"></div>
			<?php
		}
	}

	/**
	 * Display resend link in login form
	 */
	public function add_resend_link_to_login_form() {
		?>
		<div class="um-col-alt-b"><?php do_action( 'um_raf_show_resend_link', 'um-link-alt' ); ?></div>
		<?php
	}

	/**
	 * Display send link
	 *
	 * @param string $class Class name.
	 */
	public function display_resend_link( $class = '' ) {
		if ( ! function_exists( 'um_get_core_page' ) ) {
			return;
		}

		$resend_url = um_get_core_page( 'resend-activation' );
		if ( strlen( $resend_url ) === 0 ) {
			return;
		}
		?>
		<a href="<?php echo esc_url( $resend_url ); ?>" class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( apply_filters( 'um_raf_resend_form_link_text', __( 'Resend your activation email', 'um_raf' ) ) ); ?></a>
		<?php
	}
}
