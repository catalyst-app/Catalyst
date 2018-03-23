<?php

namespace Catalyst\API;

use \ReflectionClass;

/**
 * Contains the error messages for all error codes
 */
class ErrorCodes {
	// generic
	const ERR_10001 = 'Endpoint not found';
	const ERR_10002 = 'Invalid endpoint method';
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
	const ERR_99903 = 'You are not an artist';

	// email list
	const ERR_90001 = 'No email was entered';
	const ERR_90002 = 'Invalid email';
	const ERR_90003 = 'No context was entered';
	const ERR_90004 = 'Invalid context';

	// login
	const ERR_90101 = 'No username was entered';
	const ERR_90102 = 'Invalid username';
	const ERR_90103 = 'The username does not exist';
	const ERR_90104 = 'No password was entered';
	const ERR_90105 = 'Incorrect password';
	const ERR_90106 = 'No captcha was sent';
	const ERR_90107 = 'Invalid captcha';
	const ERR_90108 = 'This account has been suspended';
	const ERR_90109 = 'This account has been deactivated';
	const ERR_90110 = 'TOTP Challenge required';

	// totp login
	const ERR_90201 = 'There are no active TOTP logins';
	const ERR_90202 = 'No code was entered';
	const ERR_90203 = 'An invalid code was entered';
	const ERR_90204 = 'The code is invalid';

	// register
	const ERR_90301 = 'Username was not entered';
	const ERR_90302 = 'Username is invalid';
	const ERR_90303 = 'Username is already in use';
	const ERR_90304 = 'Nickname was not properly submitted';
	const ERR_90305 = 'Nickname is too long';
	const ERR_90306 = 'Email was not properly submitted';
	const ERR_90307 = 'Email is invalid';
	const ERR_90308 = 'Email is already in use';
	const ERR_90309 = 'Password was not entered';
	const ERR_90310 = 'Password does not meet the requirements';
	const ERR_90311 = 'Password confirmation was not entered';
	const ERR_90312 = 'Confirmation does not match the provided password';
	const ERR_90313 = 'Color was not entered';
	const ERR_90314 = 'Invalid color';
	const ERR_90315 = 'This file is too large';
	const ERR_90316 = 'This file is not an image';
	const ERR_90317 = 'The image is invalid';
	const ERR_90318 = 'The NSFW profile picture checkbox is invalid';
	const ERR_90319 = 'NSFW access checkbox is invalid';
	const ERR_90320 = 'Terms of service agreement checkbox is not valid';
	const ERR_90321 = 'You must agree to the Terms of Service';
	const ERR_90322 = 'CAPTCHA was not sent';
	const ERR_90323 = 'CAPTCHA response was invalid';
	const ERR_90324 = '@catalystapp.co emails are not allowed';

	// email verification
	const ERR_90401 = 'Token was not entered';
	const ERR_90402 = 'Token is invalid';
	const ERR_90403 = 'CAPTCHA was not sent';
	const ERR_90404 = 'CAPTCHA response was invalid';
	const ERR_90405 = 'Email is already verified';

	// settings
	const ERR_90501 = 'Username was not entered';
	const ERR_90502 = 'Username is invalid';
	const ERR_90503 = 'Username is in use by another user';
	const ERR_90504 = 'Nickname was not properly submitted';
	const ERR_90505 = 'Nickname is too long';
	const ERR_90506 = 'Email was not properly submitted';
	const ERR_90507 = 'Email is invalid';
	const ERR_90508 = 'Email is already in use';
	const ERR_90509 = 'New password was not properly submitted';
	const ERR_90510 = 'New password does not meet the requirements';
	const ERR_90511 = 'New password confirmation was not entered';
	const ERR_90512 = 'Password confirmation does not match the provided password';
	const ERR_90513 = 'Two-factor checkbox was not properly submitted';
	const ERR_90514 = 'Color was not entered';
	const ERR_90515 = 'Invalid color';
	const ERR_90516 = 'This file is too large';
	const ERR_90517 = 'This file is not an image';
	const ERR_90518 = 'The image is invalid';
	const ERR_90519 = 'The NSFW profile picture checkbox is invalid';
	const ERR_90520 = 'NSFW access checkbox is invalid';
	const ERR_90521 = 'Old password was not entered';
	const ERR_90522 = 'Old password is incorrect';
	const ERR_90523 = '@catalystapp.co emails are not allowed';

	// deactivate
	const ERR_90601 = 'No username was entered';
	const ERR_90602 = 'This username is not yours';
	const ERR_90603 = 'No password was entered';
	const ERR_90604 = 'Incorrect password';

	// add social
	const ERR_90701 = 'Internal error';
	const ERR_90702 = 'Missing label';
	const ERR_90703 = 'Label too long';
	const ERR_90704 = 'URL/email missing';
	const ERR_90705 = 'URL/email is invalid';
	const ERR_90706 = 'This domain is not allowed';
	const ERR_90707 = 'IP addresses are not allowed';
	const ERR_90708 = 'URL is not a valid scheme (http,https, or email only)';
	const ERR_90709 = 'Inline URL authentication is disallowed';
	const ERR_90710 = 'Non-standard ports are disallowed';
	const ERR_90711 = 'You aren\'t funny';
	const ERR_90712 = 'No social media type passed';
	const ERR_90713 = 'Invalid social media type passed';

	// new character
	const ERR_90801 = 'Missing name';
	const ERR_90802 = 'Invalid name';
	const ERR_90803 = 'Missing description';
	const ERR_90804 = 'Invalid description';
	const ERR_90805 = 'These files are too large.  Upload them separately';
	const ERR_90806 = 'This file is not an image';
	const ERR_90807 = 'The image is invalid';
	const ERR_90808 = 'Color was not entered';
	const ERR_90809 = 'Invalid color';
	const ERR_90810 = 'No publicity checkbox value';
	const ERR_90811 = 'Invalid publicity checkbox value';

	// delete character
	const ERR_90901 = 'Missing character token';
	const ERR_90902 = 'Invalid character token';
	const ERR_90903 = 'This character is not yours';

	// edit character
	const ERR_91001 = 'Missing name';
	const ERR_91002 = 'Invalid name';
	const ERR_91003 = 'Missing description';
	const ERR_91004 = 'Invalid description';
	const ERR_91005 = 'These files are too large.  Upload them separately';
	const ERR_91006 = 'This file is not an image';
	const ERR_91007 = 'The image is invalid';
	const ERR_91008 = 'Color was not entered';
	const ERR_91009 = 'Invalid color';
	const ERR_91010 = 'No publicity checkbox value';
	const ERR_91011 = 'Invalid publicity checkbox value';
	const ERR_91012 = 'Invalid character';

	// undelete artist
	const ERR_91101 = 'You are currently an artist';
	const ERR_91102 = 'You never were an artist';

	// new artist
	const ERR_91201 = 'Missing name';
	const ERR_91202 = 'Name too long';
	const ERR_91203 = 'Missing URL';
	const ERR_91204 = 'Invalid URL';
	const ERR_91205 = 'URL is already in use';
	const ERR_91206 = 'Missing description';
	const ERR_91207 = 'Invalid description';
	const ERR_91208 = 'Missing image';
	const ERR_91209 = 'Invalid image';
	const ERR_91210 = 'Image is too large';
	const ERR_91211 = 'Missing color';
	const ERR_91212 = 'Invalid color';
	const ERR_91213 = 'Missing ToS';
	const ERR_91214 = 'Invalid ToS';
	const ERR_91215 = 'You are already an artist';

	// delete artist
	const ERR_91301 = 'You are not an artist';

	// edit artist
	const ERR_91401 = 'Missing name';
	const ERR_91402 = 'Name too long';
	const ERR_91403 = 'Missing URL';
	const ERR_91404 = 'Invalid URL';
	const ERR_91405 = 'URL is already in use';
	const ERR_91406 = 'Missing description';
	const ERR_91407 = 'Invalid description';
	const ERR_91408 = 'Missing image';
	const ERR_91409 = 'Invalid image';
	const ERR_91410 = 'Image is too large';
	const ERR_91411 = 'Missing color';
	const ERR_91412 = 'Invalid color';
	const ERR_91413 = 'Missing ToS';
	const ERR_91414 = 'Invalid ToS';
	const ERR_91415 = 'You are not an artist';

	// new commission type
	const ERR_91501 = 'Missing name';
	const ERR_91502 = 'Invalid name';
	const ERR_91503 = 'Missing blurb';
	const ERR_91504 = 'Invalid blurb';
	const ERR_91505 = 'Missing description';
	const ERR_91506 = 'Invalid description';
	const ERR_91507 = 'Missing base cost';
	const ERR_91508 = 'Invalid range (0-1000000)';
	const ERR_91509 = 'Missing usd cost';
	const ERR_91510 = 'Invalid usd cost';
	const ERR_91511 = 'Missing attrs';
	const ERR_91512 = 'An unknown error occured';
	const ERR_91513 = 'An unknown error occured';
	const ERR_91514 = 'An unknown error occured';
	const ERR_91515 = 'An unknown error occured';
	const ERR_91516 = 'An unknown error occured';
	const ERR_91517 = 'An unknown error occured';
	const ERR_91518 = 'An unknown error occured';
	const ERR_91519 = 'An unknown error occured';
	const ERR_91520 = 'An unknown error occured';
	const ERR_91521 = 'An unknown error occured';
	const ERR_91522 = 'An unknown error occured';
	const ERR_91523 = 'An unknown error occured';
	const ERR_91524 = 'Files too large';
	const ERR_91525 = 'Not an image';
	const ERR_91526 = 'Invald image';

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
