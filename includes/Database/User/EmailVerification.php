<?php

namespace Redacted\Database\User;

class EmailVerification {
	public const VERIFIED = 0;
	public const TOKEN_INVALID = 1;
	public const EMAIL_INVALID = 2;
	public const CAPTCHA_INVALID = 3;
	public const ALREADY_VERIFIED = 4;
	public const ERROR_UNKNOWN = 5;

	public const PHRASES = [
		self::VERIFIED => "Success",
		self::TOKEN_INVALID => "Invalid token",
		self::CAPTCHA_INVALID => "Invalid Captcha",
		self::ALREADY_VERIFIED => "You have already verified your email.",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public const REDIRECT_URL = "../Dashboard";

	public const CAPTCHA_KEY = "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI";
	public const CAPTCHA_SECRET = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe";

	public static $lastErrId = -1;

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "emailverify",
				"ajax" => true,
				"redirect" => self::REDIRECT_URL,
				"auth" => [
					["\Redacted\User\User::isLoggedOut"],
					"\Redacted\User\User::getNotLoggedInHTML"
				],
				"method" => "POST",
				"handler" => "handler.php",
				"button" => "verify",
				"additional_cases" => [
					self::ALREADY_VERIFIED =>
						'alert("'.self::PHRASES[self::ALREADY_VERIFIED].'");window.location="";return;break;'
				],
				"additional_fields" => [],
				"success" => self::PHRASES[self::VERIFIED]
			],
			[
				"name" => "token",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"default" => isset($_SESSION["token"]) ? $_SESSION["token"] : "",
				"label" => "Token",
				"pattern" => [\Redacted\Tokens::EMAIL_VERIFICATION_TOKEN_REGEX, "A valid token."],
				"required" => true,
				"primary" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::TOKEN_INVALID]],
				"error_code" => [self::TOKEN_INVALID],
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

	public static function sendVerificationEmailToUser(\Redacted\User\User $user) {
		if ($user->emailIsVerified()) {
			return;
		}

		preg_match("/^(.*)(EmailVerification|Register|Settings)/", \Redacted\Page\UniversalFunctions::getRequestURI(), $out);
		$url = $out[1]."EmailVerification/?token=".$user->getEmailToken();

		\Redacted\Email::sendEmail(
			[[$user->getEmail(), $user->getNickname()]],
			"Redacted - Email verification",
			'<html><head><style>'.\Redacted\Email::getCSS($user->getColorHex()).'</style></head><body><div class="container"><div class="section"><h1 class="center header hide-on-small-only">Email Verification</h1><h3 class="center header hide-on-med-and-up">Email Verification</h3></div><div class="section"><p class="flow-text">Thank you for registering with Redacted!</p><p class="flow-text">Please click the button below to activate your account.</p><div><a href="'.$url.'" class="btn">Verify</a></div><p>Alternatively, use the token <span style="font-weight: 700;">'.$user->getEmailToken().'</span> to verify your email.</p></div></div></body></html>',
			implode("\r\n", [
				"Email Verification",
				"",
				"Thank you for registering with Redacted!",
				"Please go to the following URL to verify your account:",
				$url,
				"Alternatively, use the token ".$user->getEmailToken()
			])
		);
	}

	public static function verify(\Redacted\User\User $user, string $token) : int {
		if ($user->getEmailToken() != $token) {
			return self::TOKEN_INVALID;
		}

		$id = $user->getId();

		$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["users"]."` SET `EMAIL_VERIFIED` = 1 WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $id);
		$stmt->execute();

		$user->clearCache("EMAIL_VERIFIED");

		return self::VERIFIED;
	}
}
