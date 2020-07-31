<?php
/**
 * Helper functions for handling AJAX requests
 *
 * @since 1.0.0
 * @package Um_Resend_Activation_Form
 * @subpackage Um_Resend_Activation_Form/includes
 * @author Yaron Guez <yaron@trestian.com>
 */

/**
 * Ajax helper class
 */
class Um_Raf_Ajax {

	/**
	 * Return response for WP ajax
	 *
	 * @param string $message Response message.
	 * @param bool   $success Success type.
	 */
	public static function return_response( $message, $success ) {
		wp_send_json(
			array(
				'success' => $success,
				'message' => $message,
			)
		);
	}

	/**
	 * Return error response
	 *
	 * @param string $message Response message.
	 */
	public static function return_error( $message ) {
		self::return_response( $message, false );
	}

	/**
	 * Return success response
	 *
	 * @param string $message Response message.
	 */
	public static function return_success( $message ) {
		self::return_response( $message, true );
	}

	/**
	 * Internal helper function to deal with missing data
	 *
	 * @param string $field Field key.
	 * @param string $message Message string.
	 * @return mixed
	 */
	public static function check_missing_data( $field, $message ) {
		if ( empty( $_POST[ $field ] ) ) { // @codingStandardsIgnoreLine
			self::return_error( $message );
		} else {
			return $_POST[ $field ]; // @codingStandardsIgnoreLine
		}
	}
}
