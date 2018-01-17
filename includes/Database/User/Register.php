<?php

namespace Catalyst\Database\User;

class Register {
	public const ACCOUNT_CREATED = 0;
	public const USERNAME_INVALID = 1;
	public const USERNAME_EXISTS = 2;
	public const EMAIL_INVALID = 3;
	public const EMAIL_EXISTS = 4;
	public const PASSWORD_INVALID = 5;
	public const PASSWORDS_UNEQUAL = 6;
	public const NICKNAME_INVALID = 7;
	public const PICTURE_INVALID = 8;
	public const COLOR_INVALID = 9;
	public const CAPTCHA_INVALID = 10;
	public const TOS_INVALID = 11;
	public const ALREADY_LOGGED_IN = 12;
	public const ERROR_UNKNOWN = 13;

	public const PHRASES = [
		self::ACCOUNT_CREATED => "Success",
		self::USERNAME_INVALID => "Invalid Username",
		self::USERNAME_EXISTS => "Username is already taken",
		self::EMAIL_INVALID => "Invalid email",
		self::EMAIL_EXISTS => "Email is already in use.  Contact support for help",
		self::PASSWORD_INVALID => "Invalid password",
		self::PASSWORDS_UNEQUAL => "Passwords do not match",
		self::NICKNAME_INVALID => "Invalid nickname",
		self::PICTURE_INVALID => "Invalid profile photo",
		self::COLOR_INVALID => "Invalid color",
		self::TOS_INVALID => "Please accept the ToS",
		self::CAPTCHA_INVALID => "Invalid captcha",
		self::ALREADY_LOGGED_IN => "You are already logged in.  Try refreshing?",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public const REDIRECT_URL = "../Dashboard";

	public const CAPTCHA_KEY = "6Lf7A0EUAAAAAM7naF_3NGWGVAxMUK-qPQABEdAl";
	public const CAPTCHA_SECRET = REGISTER_CAPTCHA_SECRET;

	public static $lastErrId = -1;

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "register",
				"ajax" => true,
				"redirect" => self::REDIRECT_URL,
				"auth" => [
					["\Catalyst\User\User::isLoggedIn"],
					"\Catalyst\User\User::getAlreadyLoggedInHTML"
				],
				"method" => "POST",
				"handler" => "handler.php",
				"button" => "register",
				"additional_cases" => [
					self::ALREADY_LOGGED_IN =>
						'alert("'.self::PHRASES[self::ALREADY_LOGGED_IN].'");window.location="";return;break;',
				],
				"success" => self::PHRASES[self::ACCOUNT_CREATED],
				"additional_fields" => [],
				"flags" => [
					\Catalyst\Form\Flags::COLOR_PICKER
				]
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
				"error_text" => [self::PHRASES[self::USERNAME_INVALID], self::PHRASES[self::USERNAME_EXISTS]],
				"error_code" => [self::USERNAME_INVALID, self::USERNAME_EXISTS],
				"after_html" => '<p class="no-top-margin col s12 grey-text">2-64 characters of letters, numbers, period, dashes, and underscores only</p>'
			],
			[
				"name" => "password",
				"wrapper_classes" => "col s12",
				"type" => "password",
				"label" => "Password",
				"pattern" => ['^.{8,}$', "Please use at least 8 characters"],
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::PASSWORD_INVALID]],
				"error_code" => [self::PASSWORD_INVALID],
				"after_html" => '<p class="no-top-margin col s12">Please use at least 8 characters.</p>'
			],
			[
				"name" => "passwordconfirm",
				"wrapper_classes" => "col s12",
				"type" => "confirm-password",
				"linked_field" => "password",
				"label" => "Confirm Password",
				"pattern" => ['^.{8,}$', "Please use at least 8 characters"],
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::PASSWORDS_UNEQUAL]],
				"error_code" => [self::PASSWORDS_UNEQUAL]
			],
			[
				"name" => "email",
				"wrapper_classes" => "col s12",
				"type" => "email",
				"label" => "Email",
				"pattern" => ['^.{2,}@.{2,}\..{2,}$', "Valid email address"],
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::EMAIL_INVALID], self::PHRASES[self::EMAIL_EXISTS]],
				"error_code" => [self::EMAIL_INVALID, self::EMAIL_EXISTS]
			],
			[
				"name" => "nickname",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"label" => "Nickname",
				"pattern" => ['^.{2,100}$', "Between 2 and 100 characters"],
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::NICKNAME_INVALID]],
				"error_code" => [self::NICKNAME_INVALID]
			],
			[
				"name" => "pfp",
				"wrapper_classes" => "col s12",
				"type" => "image",
				"maxsize" => "2M",
				"label" => "Profile Picture (2MB limit)",
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::PICTURE_INVALID]],
				"error_code" => [self::PICTURE_INVALID]
			],
			[
				"name" => "pfpnsfw",
				"wrapper_classes" => "col s12 no-top-margin more-bottom-margin",
				"type" => "checkbox",
				"label" => "My profile picture is NSFW",
				"required" => false,
				"error_text" => [],
				"error_code" => []
			],
			[
				"name" => "color",
				"wrapper_classes" => "col s12",
				"type" => "color",
				"label" => "Color",
				"pattern" => ['^('.implode("|", array_keys(\Catalyst\Color::HEX_MAP)).')$', "One of the following: ".implode(", ", array_keys(\Catalyst\Color::HEX_MAP))],
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::COLOR_INVALID]],
				"error_code" => [self::COLOR_INVALID]
			],
			[
				"name" => "nsfw",
				"wrapper_classes" => "col s12",
				"type" => "checkbox",
				"label" => "I am above 18 years old and wish to see NSFW content",
				"required" => false,
				"error_text" => [],
				"error_code" => []
			],
			[
				"name" => "tos",
				"wrapper_classes" => "col s12",
				"type" => "checkbox",
				"label" => "I agree to the Terms of Service",
				"required" => true,
				"error_text" => [self::PHRASES[self::TOS_INVALID]],
				"error_code" => [self::TOS_INVALID]
			],
			[
				"type" => "captcha",
				"name" => "captcha",
				"key" => self::CAPTCHA_KEY,
				"secret" => self::CAPTCHA_SECRET,
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::CAPTCHA_INVALID]],
				"error_code" => [self::CAPTCHA_INVALID],
			]
		];
	}

	public static function usernameAvailable(string $username) : bool {
		$userStmt = $GLOBALS["dbh"]->prepare("SELECT 1 FROM `".DB_TABLES["users"]."` WHERE `USERNAME` = :USERNAME;");
		$userStmt->bindParam(":USERNAME", $username);
		$userStmt->execute();

		$userStmt->closeCursor();

		return ($userStmt->rowCount() === 0);
	}

	public static function emailAvailable(string $email) : bool {
		if (empty($email)) {
			return true;
		}
		$userStmt = $GLOBALS["dbh"]->prepare("SELECT 1 FROM `".DB_TABLES["users"]."` WHERE `EMAIL` = :EMAIL;");
		$userStmt->bindParam(":EMAIL", $email);
		$userStmt->execute();

		$userStmt->closeCursor();

		return ($userStmt->rowCount() === 0);
	}

	public static function register(
		string $username,
		string $password,
		string $email,
		string $nick,
		string $color,
		bool $nsfw,
		?array $pfp,
		bool $pfpnsfw
	) : int {
		$regStmt = $GLOBALS["dbh"]->prepare("INSERT INTO `".DB_TABLES["users"]."` (`FILE_TOKEN`,`USERNAME`,`HASHED_PASSWORD`,`PASSWORD_RESET_TOKEN`,`EMAIL`,`EMAIL_TOKEN`,`PICTURE_LOC`,`PICTURE_NSFW`,`NSFW`,`COLOR`,`NICK`) VALUES (:FILE_TOKEN,:USERNAME,:HASHED_PASSWORD,:PASSWORD_RESET_TOKEN,:EMAIL,:EMAIL_TOKEN,:PICTURE_LOC,:PICTURE_NSFW,:NSFW,UNHEX(:COLOR),:NICK)");

		$fileToken = \Catalyst\Tokens::generateUniqueUserFileToken();
		$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ["cost" => \Catalyst\Page\Values::BCRYPT_COST]);
		$passwordToken = \Catalyst\Tokens::generatePasswordResetToken();
		$email = $email ? $email : null;
		$emailToken = \Catalyst\Tokens::generateEmailVerificationToken();
		$pictureLoc = \Catalyst\Form\FileUpload::uploadImage($pfp, \Catalyst\Form\FileUpload::PROFILE_PHOTO, $fileToken);
		$pfpnsfw = $pfpnsfw ? 1 : 0;
		$nsfw = $nsfw ? 1 : 0;
		$color = (string)"$color";
		$nick = $nick ? $nick : $username;

		$regStmt->bindParam(":FILE_TOKEN", $fileToken);
		$regStmt->bindParam(":USERNAME", $username);
		$regStmt->bindParam(":HASHED_PASSWORD", $hashedPassword);
		$regStmt->bindParam(":PASSWORD_RESET_TOKEN", $passwordToken);
		$regStmt->bindParam(":EMAIL", $email);
		$regStmt->bindParam(":EMAIL_TOKEN", $emailToken);
		$regStmt->bindParam(":PICTURE_LOC", $pictureLoc);
		$regStmt->bindParam(":PICTURE_NSFW", $pfpnsfw);
		$regStmt->bindParam(":NSFW", $nsfw);
		$regStmt->bindParam(":COLOR", $color, \PDO::PARAM_STR);
		$regStmt->bindParam(":NICK", $nick);

		if (!$regStmt->execute()) {
			error_log(" Register error: **".(self::$lastErrId = microtime(true))."**, ".serialize($regStmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		\Catalyst\Database\User\Login::login($username, $password);

		\Catalyst\Database\User\EmailVerification::sendVerificationEmailToUser($_SESSION["user"]);

		return self::ACCOUNT_CREATED;
	}
}
