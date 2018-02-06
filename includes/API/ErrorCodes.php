<?php

namespace Catalyst\API;

use \ReflectionClass;

/**
 * Contains the error messages for all error codes
 */
class ErrorCodes {
	// generic
	const ERR_10001 = 'Endpoint not found';
	const ERR_99999 = 'An internal error occured';

	// api-related
	const ERR_11001 = 'Client header not passed';
	const ERR_11002 = 'User header not passed';
	const ERR_11003 = 'Client header is invalid';
	const ERR_11004 = 'User header is invalid';
	const ERR_11005 = 'Client does not exist';
	const ERR_11006 = 'User tokens are invalid';

	// get user
	const ERR_20001 = 'User account does not exist';

	// internal
	// auth-related
	const ERR_99901 = 'No user is logged in';
	const ERR_99902 = 'A user is already logged in';

	// email list
	const ERR_90001 = 'No email was passed';
	const ERR_90002 = 'Invalid email was passed';
	const ERR_90003 = 'No context was passed';
	const ERR_90004 = 'An invalid context was passed';

	// login
	const ERR_90101 = 'No username was passed';
	const ERR_90102 = 'Invalid username';
	const ERR_90103 = 'No password was passed';
	const ERR_90104 = 'An incorrect password was passed';
	const ERR_90105 = 'No captcha response was sent';
	const ERR_90106 = 'Invalid captcha';
	const ERR_90107 = 'This account has been suspended';
	const ERR_90108 = 'This account has been disabled';
	const ERR_90109 = 'TOTP Challenge required';

	/**
	 * Get an associative array of code => message based on class constants
	 * 
	 * @return array Associative array, code => message of all codes
	 */
	public static function getAssoc() : array {
		$reflectedClass = new ReflectionClass(__CLASS__);
		$constants = $reflectedClass->getConstants();

		$result = [];
		foreach ($constants as $name => $value) {
			$cutName = str_replace("ERR_", "", $name);
			$result[(int)$cutName] = $value;
		}

		return $result;
	}
}
