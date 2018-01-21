<?php

namespace Catalyst\Database\User;

class TOTPLogin {
	public const LOGGED_IN = 0;
	public const NO_PARTIAL_AUTH = 1;
	public const TOKEN_INVALID = 2;
	public const ALREADY_LOGGED_IN = 3;
	public const ERROR_UNKNOWN = 4;

	public const PHRASES = [
		self::LOGGED_IN => "Success",
		self::NO_PARTIAL_AUTH => "No partial login session",
		self::TOKEN_INVALID => "Invalid Token",
		self::ALREADY_LOGGED_IN => "You are already logged in.  Please try again.",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public const REDIRECT_URL = "../../Dashboard/";

	public static $lastErrId = -1;

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "totplogin",
				"ajax" => true,
				"auth" => [
					["\Catalyst\User\User::isLoggedIn"],
					"\Catalyst\User\User::getAlreadyLoggedInHTML"
				],
				"redirect" => self::REDIRECT_URL,
				"method" => "POST",
				"handler" => "handler.php",
				"button" => "login",
				"additional_cases" => [
					self::ALREADY_LOGGED_IN =>
						'alert("'.self::PHRASES[self::ALREADY_LOGGED_IN].'");window.location="";return;break;',
					self::NO_PARTIAL_AUTH =>
						'alert("'.self::PHRASES[self::NO_PARTIAL_AUTH].'");window.location=$("html").attr("data-rootdir")+"Login/";return;break;'
				],
				"additional_fields" => [],
				"success" => self::PHRASES[self::LOGGED_IN]
			],
			[
				"name" => "token",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"label" => "Token",
				"pattern" => ['^([0-9]){6}$', "6 digit code"],
				"required" => true,
				"primary" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::TOKEN_INVALID]],
				"error_code" => [self::TOKEN_INVALID],
				"after_html" => '<p class="col s12 no-top-margin">If you do not have access to this code, please <a href="'.ROOTDIR.'Help">contact support</a> with your recovery key.</p>',
				"other_attributes" => ["autocomplete" => "off"],
			],
		];
	}

	public static function login(string $token) : int {
		if (!\Catalyst\Database\User\Login::pending2FA()) {
			return self::NO_PARTIAL_AUTH;
		}

		$key = $_SESSION["pending_user"]->getTotpKey();
		$unixtimestamp = time()/30;
		
		for($i=-2; $i<=2; $i++) {
			$checktime = (int)($unixtimestamp+$i);
			$thiskey = self::oath_hotp($key, $checktime);
			
			if ($token == self::oath_truncate($thiskey,6)) {
				\Catalyst\Database\User\Login::loginAsId($_SESSION["pending_user"]->getId());
				unset($_SESSION["pending_user"]);

				return self::LOGGED_IN;
			}
			
		}

		return self::TOKEN_INVALID;
	}

	// modified from https://github.com/Voronenko/PHPOTP/
	private static function oath_hotp($key, $counter) { 
		$result = "";
		$orgcounter = $counter;         
		$cur_counter = array(0,0,0,0,0,0,0,0);

		for($i=7;$i>=0;$i--) { // C for unsigned char, * for  repeating to the end of the input data 
			$cur_counter[$i] = pack ('C*', $counter);
			$counter = $counter >> 8;
		}

		$binary = implode($cur_counter);
		// Pad to 8 characters
		str_pad($binary, 8, chr(0), STR_PAD_LEFT);
		
		$result = hash_hmac('sha1', $binary, $key);

		return $result;
	}

	private static function oath_truncate($hash, $length = 6, $debug=false) {
		$result=""; 

		$hashcharacters = str_split($hash,2);

		for ($j=0; $j<count($hashcharacters); $j++) {
			$hmac_result[]=hexdec($hashcharacters[$j]);
		}

		$offset = $hmac_result[19] & 0xf;

		$result = ((($hmac_result[$offset+0] & 0x7f) << 24 ) | (($hmac_result[$offset+1] & 0xff) << 16 ) | (($hmac_result[$offset+2] & 0xff) << 8 ) | ($hmac_result[$offset+3] & 0xff)) % pow(10,$length);
		return $result;
	}
}
