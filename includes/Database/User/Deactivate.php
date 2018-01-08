<?php

namespace Redacted\Database\User;

class Deactivate {
	public const DEACTIVATED = 0;
	public const USERNAME_INVALID = 1;
	public const PASSWORD_INVALID = 2;
	public const ERROR_UNKNOWN = 3;

	public const PHRASES = [
		self::DEACTIVATED => "Success",
		self::USERNAME_INVALID => "Invalid Username",
		self::PASSWORD_INVALID => "Invalid Password",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public const REDIRECT_URL = "../";

	public static $lastErrId = -1;

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "deactivate",
				"ajax" => true,
				"redirect" => self::REDIRECT_URL,
				"method" => "POST",
				"handler" => "deactivate.php",
				"button" => "deactivate",
				"additional_cases" => [],
				"additional_fields" => [],
				"success" => self::PHRASES[self::DEACTIVATED]
			],
			[
				"name" => "username",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"label" => "Username",
				"pattern" => ['^'.implode("" ,array_map(function($in) { return (strtolower($in)==strtoupper($in) ? $in : '['.strtolower($in).strtoupper($in).']'); }, str_split(\Redacted\User\User::isLoggedIn() ? $_SESSION["user"]->getUsername() : ""))).'$', "Your username."],
				"required" => true,
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
		];
	}

	public static function deactivate(string $username, string $password) : int {
		$userStmt = $GLOBALS["dbh"]->prepare("SELECT `ID`, `HASHED_PASSWORD` FROM `".DB_TABLES["users"]."` WHERE `USERNAME` = :USERNAME;");
		$userStmt->bindParam(":USERNAME", $username);
		if (!$userStmt->execute()) {
			error_log(" Deactivate error: **".(self::$lastErrId = microtime(true))."**, ".serialize($userStmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		if ($userStmt->rowCount() == 0) {
			return self::USERNAME_INVALID;
		}

		$userRow = $userStmt->fetchAll()[0];
		$userStmt->closeCursor();

		if (!password_verify($password, $userRow["HASHED_PASSWORD"])) {
			return self::PASSWORD_INVALID;
		}

		$deactivateStmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["users"]."` SET `DEACTIVATED` = 1 WHERE `ID` = :ID;");
		$deactivateStmt->bindParam(":ID", $userRow["ID"]);
		if (!$deactivateStmt->execute()) {
			error_log(" Deactivate error: **".(self::$lastErrId = microtime(true))."**, ".serialize($deactivateStmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		$deactivateStmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["artist_pages"]."` SET `DELETED` = 1 WHERE `USER_ID` = :USER_ID;");
		$deactivateStmt->bindParam(":USER_ID", $userRow["ID"]);
		if (!$deactivateStmt->execute()) {
			error_log(" Deactivate error: **".(self::$lastErrId = microtime(true))."**, ".serialize($deactivateStmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		$deactivateStmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["characters"]."` SET `DELETED` = 1 WHERE `USER_ID` = :USER_ID;");
		$deactivateStmt->bindParam(":USER_ID", $userRow["ID"]);
		if (!$deactivateStmt->execute()) {
			error_log(" Deactivate error: **".(self::$lastErrId = microtime(true))."**, ".serialize($deactivateStmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		\Redacted\Database\User\Login::logout();

		return self::DEACTIVATED;
	}
}
