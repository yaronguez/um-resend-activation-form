<?php

/**
 * Helper functions for handling AJAX requests
 *
 * @since      1.0.0
 * @package    Um_Resend_Activation_Form
 * @subpackage Um_Resend_Activation_Form/includes
 * @author     Yaron Guez <yaron@trestian.com>
 */
class Um_Raf_Ajax {

	public static function return_response($message, $success){
		$response = json_encode(array(
			'success' => $success,
			message => $message
		));
		echo $response;
		wp_die();
	}

	public static function return_error($message){
		self::return_response($message, false);
	}

	public static function return_success($message){
		self::return_response($message, true);
	}

	// internal helper function to deal with missing data
	public static function check_missing_data($field, $message)
	{
		if (empty($_POST[$field])) {
			self::return_error($message);
		} else {
			return $_POST[$field];
		}
	}

}
