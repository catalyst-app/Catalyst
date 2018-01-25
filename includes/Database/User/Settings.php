<?php

namespace Catalyst\Database\User;

class Settings {
	public const UPDATED = 0;
	public const USERNAME_INVALID = 1;
	public const USERNAME_OWNED_BY_OTHER_USER = 2;
	public const EMAIL_INVALID = 3;
	public const EMAIL_OWNED_BY_OTHER_USER = 4;
	public const NEW_PASSWORD_INVALID = 5;
	public const NEW_PASSWORDS_UNEQUAL = 6;
	public const NICKNAME_INVALID = 7;
	public const PICTURE_INVALID = 8;
	public const COLOR_INVALID = 9;
	public const OLD_PASSWORD_INVALID = 10;
	public const TOTP_INIT = 11;
	public const ERROR_UNKNOWN = 12;

	public const PHRASES = [
		self::UPDATED => "Success",
		self::USERNAME_INVALID => "Invalid Username",
		self::USERNAME_OWNED_BY_OTHER_USER => "Username is already taken",
		self::EMAIL_INVALID => "Invalid email",
		self::EMAIL_OWNED_BY_OTHER_USER => "Email is already in use.",
		self::NEW_PASSWORD_INVALID => "Invalid password",
		self::NEW_PASSWORDS_UNEQUAL => "Passwords do not match",
		self::NICKNAME_INVALID => "Invalid nickname",
		self::PICTURE_INVALID => "Invalid profile photo",
		self::COLOR_INVALID => "Invalid color",
		self::OLD_PASSWORD_INVALID => "Incorrect password",
		self::TOTP_INIT => "TOTP needs to be initialized",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public const REDIRECT_URL = "../Dashboard";

	public static $lastErrId = -1;

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "settings",
				"ajax" => true,
				"eval" => 'window.location="'.self::REDIRECT_URL.'";',
				"auth" => [
					["\Catalyst\User\User::isLoggedOut"],
					"\Catalyst\User\User::getNotLoggedInHTML"
				],
				"method" => "POST",
				"handler" => "handler.php",
				"button" => "save",
				"additional_cases" => [
					self::TOTP_INIT => 'window.location="TOTP";return;break;'
				],
				"success" => self::PHRASES[self::UPDATED],
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
				"default" => \Catalyst\User\User::isLoggedIn() ? $_SESSION["user"]->getUsername() : "",
				"primary" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::USERNAME_INVALID], self::PHRASES[self::USERNAME_OWNED_BY_OTHER_USER]],
				"error_code" => [self::USERNAME_INVALID, self::USERNAME_OWNED_BY_OTHER_USER],
				"after_html" => '<p class="no-top-margin col s12 grey-text">2-64 characters of letters, numbers, period, dashes, and underscores only</p>'
			],
			[
				"name" => "password",
				"wrapper_classes" => "col s12",
				"type" => "password",
				"label" => "New Password",
				"pattern" => ['^.{8,}$', "Please use at least 8 characters"],
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::NEW_PASSWORD_INVALID]],
				"error_code" => [self::NEW_PASSWORD_INVALID],
				"after_html" => '<p class="no-top-margin col s12 grey-text">Only fill out this field if you wish to change your password.</p>',
				"other_attributes" => ["autocomplete" => "off"]
			],
			[
				"name" => "passwordconfirm",
				"wrapper_classes" => "col s12",
				"type" => "confirm-password",
				"linked_field" => "password",
				"label" => "Confirm New Password",
				"pattern" => ['^.{8,}$', "Please use at least 8 characters"],
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::NEW_PASSWORDS_UNEQUAL]],
				"error_code" => [self::NEW_PASSWORDS_UNEQUAL],
				"other_attributes" => ["autocomplete" => "off"]
			],
			[
				"name" => "totp",
				"wrapper_classes" => "col s12",
				"type" => "checkbox",
				"label" => "Enable Two Factor Authentication (Google Authenticator or similar required)",
				"required" => false,
				"validate" => true,
				"default" => \Catalyst\User\User::isLoggedIn() ? $_SESSION["user"]->isTotpEnabled() : "",
				"error_text" => [self::PHRASES[self::ERROR_UNKNOWN]],
				"error_code" => [self::ERROR_UNKNOWN],
				"after_html" => (\Catalyst\User\User::isLoggedIn() && $_SESSION["user"]->isTotpEnabled()) ? '<p class="col s12 no-top-margin">If you need to regenerate your code, disable and reenable this option</p>' : '',
				"other_attributes" => ["autocomplete" => "off"]
			],
			[
				"name" => "email",
				"wrapper_classes" => "col s12",
				"type" => "email",
				"label" => "Email",
				"default" => \Catalyst\User\User::isLoggedIn() ? ($_SESSION["user"]->getEmail() ? $_SESSION["user"]->getEmail() : "") : "",
				"pattern" => ['^.{2,}@.{2,}\..{2,}$', "Valid email address"],
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::EMAIL_INVALID], self::PHRASES[self::EMAIL_OWNED_BY_OTHER_USER]],
				"error_code" => [self::EMAIL_INVALID, self::EMAIL_OWNED_BY_OTHER_USER]
			],
			[
				"name" => "nickname",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"label" => "Nickname",
				"default" => \Catalyst\User\User::isLoggedIn() ? $_SESSION["user"]->getNickname() : "",
				"pattern" => ['^.{2,100}$', "Between 2 and 100 characters"],
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::NICKNAME_INVALID]],
				"error_code" => [self::NICKNAME_INVALID]
			],
			[
				"name" => "color",
				"wrapper_classes" => "col s12",
				"type" => "color",
				"label" => "Color",
				"pattern" => ['^('.implode("|", array_keys(\Catalyst\Color::HEX_MAP)).')$', "One of the following: ".implode(", ", array_keys(\Catalyst\Color::HEX_MAP))],
				"default" => \Catalyst\User\User::isLoggedIn() ? $_SESSION["user"]->getColor() : "",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::COLOR_INVALID]],
				"error_code" => [self::COLOR_INVALID]
			],
			[
				"name" => "pfp",
				"wrapper_classes" => "col s12",
				"type" => "image",
				"maxsize" => "10M",
				"label" => "Profile Picture (10MB limit)",
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::PICTURE_INVALID]],
				"error_code" => [self::PICTURE_INVALID],
				"after_html" => '<p class="col s12 no-top-margin grey-text">Only upload one if you would like to change your picture</p>'
			],
			[
				"name" => "pfpnsfw",
				"wrapper_classes" => "col s12 no-top-margin more-bottom-margin",
				"type" => "checkbox",
				"default" => \Catalyst\User\User::isLoggedIn() ? $_SESSION["user"]->getProfilePictureNsfw() : false,
				"label" => "My profile picture is NSFW",
				"required" => false,
				"error_text" => [],
				"error_code" => []
			],
			[
				"name" => "nsfw",
				"wrapper_classes" => "col s12",
				"type" => "checkbox",
				"default" => \Catalyst\User\User::isLoggedIn() ? $_SESSION["user"]->isNsfw() : false,
				"label" => "I am above 18 years old and wish to see NSFW content",
				"required" => false,
				"error_text" => [],
				"error_code" => []
			],
			[
				"name" => "oldpassword",
				"wrapper_classes" => "col s12",
				"type" => "password",
				"label" => "Old Password",
				"pattern" => ['^.{8,}$', "Please use at least 8 characters"],
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::OLD_PASSWORD_INVALID]],
				"error_code" => [self::OLD_PASSWORD_INVALID],
			],
		];
	}

	public static function update(
		string $username,
		string $password,
		bool $totp,
		string $email,
		string $nick,
		string $color,
		bool $nsfw,
		?array $pfp,
		bool $pfpnsfw,
		string $oldPassword
	) : int {
		$id = $_SESSION["user"]->getId();

		$testUnameStmt = $GLOBALS["dbh"]->prepare("SELECT 1 FROM `".DB_TABLES["users"]."` WHERE `ID` != :ID AND `USERNAME` = :USERNAME");
		$testUnameStmt->bindParam(":ID", $id);
		$testUnameStmt->bindParam(":USERNAME", $username);
		if (!$testUnameStmt->execute()) {
			error_log(" Settings error: **".(self::$lastErrId = microtime(true))."**, ".serialize($testUnameStmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}
		if ($testUnameStmt->rowCount() >= 1) {
			return self::USERNAME_OWNED_BY_OTHER_USER;
		}

		if (!empty($email)) {
			$testEmailStmt = $GLOBALS["dbh"]->prepare("SELECT 1 FROM `".DB_TABLES["users"]."` WHERE `ID` != :ID AND `EMAIL` = :EMAIL");
			$testEmailStmt->bindParam(":ID", $id);
			$testEmailStmt->bindParam(":EMAIL", $email);
			if (!$testEmailStmt->execute()) {
				error_log(" Settings error: **".(self::$lastErrId = microtime(true))."**, ".serialize($testEmailStmt->errorInfo()));
				return self::ERROR_UNKNOWN;
			}
			if ($testEmailStmt->rowCount() >= 1) {
				return self::EMAIL_OWNED_BY_OTHER_USER;
			}
		}

		$getStmt = $GLOBALS["dbh"]->prepare("SELECT `FILE_TOKEN`,`USERNAME`, `HASHED_PASSWORD`, `PASSWORD_RESET_TOKEN`, `TOTP_KEY`, `TOTP_RESET_TOKEN`, `EMAIL`, `EMAIL_VERIFIED`, `EMAIL_TOKEN`, `PICTURE_LOC`, `PICTURE_NSFW`, `NSFW`, `COLOR`, `NICK` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$getStmt->bindParam(":ID", $id);

		if (!$getStmt->execute()) {
			error_log(" Settings error: **".(self::$lastErrId = microtime(true))."**, ".serialize($getStmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		$user = $getStmt->fetchAll()[0];
		$getStmt->closeCursor();

		if (!password_verify($oldPassword, $user["HASHED_PASSWORD"])) {
			return self::OLD_PASSWORD_INVALID;
		}

		$updateStmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["users"]."` SET 
				`USERNAME` = :USERNAME,
				`HASHED_PASSWORD` = :HASHED_PASSWORD,
				`PASSWORD_RESET_TOKEN` = :PASSWORD_RESET_TOKEN,
				`TOTP_KEY` = :TOTP_KEY,
				`TOTP_RESET_TOKEN` = :TOTP_RESET_TOKEN,
				`EMAIL` = :EMAIL,
				`EMAIL_VERIFIED` = :EMAIL_VERIFIED,
				`EMAIL_TOKEN` = :EMAIL_TOKEN,
				`PICTURE_LOC` = :PICTURE_LOC,
				`PICTURE_NSFW` = :PICTURE_NSFW,
				`NSFW` = :NSFW,
				`COLOR` = UNHEX(:COLOR),
				`NICK` = :NICK
			WHERE `ID` = :ID");

		$username = empty($username) ? $user["USERNAME"] : $username;
		$hashedPassword = empty($password) ? $user["HASHED_PASSWORD"] : password_hash($password, PASSWORD_BCRYPT, ["cost" => \Catalyst\Page\Values::BCRYPT_COST]);
		$passwordToken = empty($password) ? $user["PASSWORD_RESET_TOKEN"] : \Catalyst\Tokens::generatePasswordResetToken();
		$email = empty($email) ? null : $email;
		$emailToken = ($email == $user["EMAIL"]) ? $user["EMAIL_TOKEN"] : \Catalyst\Tokens::generateEmailVerificationToken();

		$emailVerified = ($user["EMAIL"] == $email) ? $user["EMAIL_VERIFIED"] : 0;

		$totpKey = null;
		$totpReset = (!is_null($user["TOTP_RESET_TOKEN"]) ? $user["TOTP_RESET_TOKEN"] : null);
		if ($totp && !is_null($user["TOTP_KEY"])) {
			$totpKey = $user["TOTP_KEY"];
		} else if ($totp) {
			$chars = "234567ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$key = implode("",array_map(function($in) use ($chars) { return $chars[$in]; }, array_rand(str_split($chars), 16)));
			
			list($t, $b, $totpKey) = array("ABCDEFGHIJKLMNOPQRSTUVWXYZ234567", "", "");

			$lut = ["A"=>0,"B"=>1,"C"=>2,"D"=>3,"E"=>4,"F"=>5,"G"=>6,"H"=>7,"I"=>8,"J"=>9,"K"=>10,"L"=>11,"M"=>12,"N"=>13,"O"=>14,"P"=>15,"Q"=>16,"R"=>17,"S"=>18,"T"=>19,"U"=>20,"V"=>21,"W"=>22,"X"=>23,"Y"=>24,"Z"=>25,"2"=>26,"3"=>27,"4"=>28,"5"=>29,"6"=>30,"7"=>31];

			$l = strlen($key);
			$n = 0;
			$j = 0;
			$totpKey = "";

			for ($i = 0; $i < $l; $i++) {
				$n = $n << 5;
				$n = $n + $lut[$key[$i]];
				$j = $j + 5;
				if ($j >= 8) {
					$j = $j - 8;
					$totpKey .= chr(($n & (0xFF << $j)) >> $j);
				}
			}

			if (is_null($user["TOTP_RESET_TOKEN"])) {
				$totpReset = \Catalyst\Tokens::generateTotpResetToken();
			}

			$totpInitialized = true;
		}
		
		$newPic = \Catalyst\Form\FileUpload::uploadImage($pfp, \Catalyst\Form\FileUpload::PROFILE_PHOTO, $user["FILE_TOKEN"]);
		if (!is_null($newPic)) {
			\Catalyst\Form\FileUpload::delete($user["FILE_TOKEN"].$user["PICTURE_LOC"], \Catalyst\Form\FileUpload::PROFILE_PHOTO);
		}

		$pictureLoc = is_null($newPic) ? $user["PICTURE_LOC"] : $newPic;
		$pfpnsfw = $pfpnsfw ? 1 : 0;
		$nsfw = $nsfw ? 1 : 0;
		$color = (string)"$color";
		$nick = empty($nick) ? $user["NICK"] : $nick;

		$updateStmt->bindParam(":USERNAME", $username);
		$updateStmt->bindParam(":HASHED_PASSWORD", $hashedPassword);
		$updateStmt->bindParam(":PASSWORD_RESET_TOKEN", $passwordToken);
		$updateStmt->bindParam(":TOTP_KEY", $totpKey);
		$updateStmt->bindParam(":TOTP_RESET_TOKEN", $totpReset);
		$updateStmt->bindParam(":EMAIL", $email);
		$updateStmt->bindParam(":EMAIL_VERIFIED", $emailVerified);
		$updateStmt->bindParam(":EMAIL_TOKEN", $emailToken);
		$updateStmt->bindParam(":PICTURE_LOC", $pictureLoc);
		$updateStmt->bindParam(":PICTURE_NSFW", $pfpnsfw);
		$updateStmt->bindParam(":NSFW", $nsfw);
		$updateStmt->bindParam(":COLOR", $color, \PDO::PARAM_STR);
		$updateStmt->bindParam(":NICK", $nick);

		$updateStmt->bindParam(":ID", $id);

		if (!$updateStmt->execute()) {
			error_log(" Settings error: **".(self::$lastErrId = microtime(true))."**, ".serialize($updateStmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		\Catalyst\Database\User\EmailVerification::sendVerificationEmailToUser($_SESSION["user"]);

		if (isset($totpInitialized) && $totpInitialized) {
			return self::TOTP_INIT;
		}
		return self::UPDATED;
	}
}
