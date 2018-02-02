<?php

namespace Catalyst\Database\User;
use Catalyst\Controller;

class Login {
	public const CREDENTIALS_VALID = 0;
	public const USERNAME_INVALID = 1;
	public const PASSWORD_INVALID = 2;
	public const CAPTCHA_INVALID = 3;
	public const ACCOUNT_DISABLED = 4;
	public const ALREADY_LOGGED_IN = 5;
	public const TOTP_REQUIRED = 6;
	public const ERROR_UNKNOWN = 7;

	public const PHRASES = [
		self::CREDENTIALS_VALID => "Success",
		self::USERNAME_INVALID => "Invalid Username",
		self::PASSWORD_INVALID => "Invalid Password",
		self::CAPTCHA_INVALID => "Invalid Captcha",
		self::ACCOUNT_DISABLED => "This account has been disabled.  Please contact support for more information.",
		self::ALREADY_LOGGED_IN => "You are already logged in.  Please try again.",
		self::TOTP_REQUIRED => "Please authenticate with your 2FA code",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public const REDIRECT_URL = "../Dashboard/";

	public const CAPTCHA_KEY = "6LfGBUEUAAAAAIC4spvBe8kIKhQlU_JsAVuTfnid";
	public const CAPTCHA_SECRET = LOGIN_CAPTCHA_SECRET;

	public static $lastErrId = -1;

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "login",
				"ajax" => true,
				"redirect" => self::REDIRECT_URL,
				"auth" => [
					["\Catalyst\User\User::isLoggedIn"],
					"\Catalyst\User\User::getAlreadyLoggedInHTML"
				],
				"method" => "POST",
				"handler" => "handler.php",
				"button" => "login",
				"additional_cases" => [
					self::ACCOUNT_DISABLED =>
						'alert("'.self::PHRASES[self::ACCOUNT_DISABLED].'");return;break;',
					self::ALREADY_LOGGED_IN =>
						'alert("'.self::PHRASES[self::ALREADY_LOGGED_IN].'");window.location="";return;break;',
					self::TOTP_REQUIRED =>
						'window.location=$("html").attr("data-rootdir")+"Login/TOTP/";return;break;'
				],
				"additional_fields" => [],
				"success" => self::PHRASES[self::CREDENTIALS_VALID]
			],
			[
				"name" => "username",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"label" => "Username",
				"pattern" => ['^([A-Za-z0-9._-]){2,64}$', "2-64 characters of letters, numbers, period, dashes, and underscores only."],
				"required" => true,
				"primary" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::USERNAME_INVALID]],
				"error_code" => [self::USERNAME_INVALID],
			],
			[
				"name" => "password",
				"wrapper_classes" => "col s12",
				"type" => "password",
				"label" => "Password",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::PASSWORD_INVALID]],
				"error_code" => [self::PASSWORD_INVALID],
			],
			[
				"type" => "captcha",
				"name" => "captcha",
				"key" => Controller::isDevelMode() ? \Catalyst\Page\Values::DEBUG_CAPTCHA_KEY : self::CAPTCHA_KEY,
				"secret" => Controller::isDevelMode() ? \Catalyst\Page\Values::DEBUG_CAPTCHA_SECRET : self::CAPTCHA_SECRET,
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::CAPTCHA_INVALID]],
				"error_code" => [self::CAPTCHA_INVALID],
			]
		];
	}

	public static function login(string $username, string $password) : int {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ID`, `DEACTIVATED`, `SUSPENDED`, `HASHED_PASSWORD`, `TOTP_KEY` FROM `".DB_TABLES["users"]."` WHERE `USERNAME` = :USERNAME;");
		$stmt->bindParam(":USERNAME", $username);
		if (!$stmt->execute()) {
			error_log(" Login error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		if ($stmt->rowCount() == 0) {
			return self::USERNAME_INVALID;
		}

		$userRow = $stmt->fetchAll()[0];
		$stmt->closeCursor();

		if (!password_verify($password, $userRow["HASHED_PASSWORD"])) {
			return self::PASSWORD_INVALID;
		}

		if ($userRow["DEACTIVATED"] || $userRow["SUSPENDED"]) {
			return self::ACCOUNT_DISABLED;
		}

		if ($userRow["TOTP_KEY"] !== null) {
			$_SESSION["pending_user"] = new \Catalyst\User\User($userRow["ID"]);
			return self::TOTP_REQUIRED;
		}

		self::loginAsId($userRow["ID"]);

		return self::CREDENTIALS_VALID;
	}

	public static function pending2FA() : bool {
		return isset($_SESSION["pending_user"]) && !is_null($_SESSION["pending_user"]) && $_SESSION["pending_user"] instanceof \Catalyst\User\User;
	}

	public static function loginAsId(int $id) {
		$_SESSION["user"] = new \Catalyst\User\User($id);
	}

	public static function logout() {
		session_destroy();
	}
}
