<?php

namespace Redacted\Database;

class SocialMedia {
	public const SUCCESS = 0;
	public const NETWORK_INVALID = 1;
	public const URL_INVALID = 2;
	public const NAME_INVALID = 3;
	public const ERROR_UNKNOWN = 4;

	public const PHRASES = [
		self::SUCCESS => "Success",
		self::NETWORK_INVALID => "Invalid network",
		self::URL_INVALID => "Invalid url",
		self::NAME_INVALID => "Invalid name",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public const REDIRECT_URL = "";

	public static $lastErrId = -1;
	public static $lastInsertId = -1;

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "addnetwork",
				"ajax" => true,
				"eval" => 'addSocialChip(JSON.parse(response));',
				"method" => "POST",
				"handler" => "add.php",
				"button" => "add",
				"additional_cases" => [],
				"additional_fields" => [],
				"success" => self::PHRASES[self::SUCCESS]
			],
			[
				"name" => "network",
				"wrapper_classes" => "col s12",
				"type" => "select",
				"options" => \Redacted\Database\Integrations\Meta::getProfileAddSelectArray(),
				"label" => "Network",
				"required" => true,
				"error_text" => [self::PHRASES[self::NETWORK_INVALID]],
				"error_code" => [self::NETWORK_INVALID],
			],
			[
				"name" => "name",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"label" => "Display name",
				"pattern" => ['^.{2,64}$', "Between 2 and 64 characters."],
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::NAME_INVALID]],
				"error_code" => [self::NAME_INVALID],
			],
			[
				"name" => "url",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"label" => "URL or e-mail (Please include the https:// if URL)",
				"required" => false,
				"validate" => true,
				"pattern" => ['^(https?://.{2,}|.{2,}@.{2,})$', "Valid URL or e-mail"],
				"error_text" => [self::PHRASES[self::URL_INVALID]],
				"error_code" => [self::URL_INVALID],
			],
		];
	}

	public static function addToUser(\Redacted\User\User $user, string $network, string $name, ?string $url) {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT IFNULL(MAX(`SORT`), 0) AS `NEXT_SORT` FROM `".DB_TABLES["user_social_media"]."` WHERE `USER_ID` = :USER_ID;");
		$id = $user->getId();
		$stmt->bindParam(":USER_ID", $id);
		if (!$stmt->execute()) {
			error_log(" Add social media error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}
		$nextSort = $stmt->fetchAll()[0]["NEXT_SORT"];
		$stmt->closeCursor();

		$stmt = $GLOBALS["dbh"]->prepare("INSERT INTO `".DB_TABLES["user_social_media"]."`
				(`SORT`, `USER_ID`, `NETWORK`, `SERVICE_URL`, `DISP_NAME`)
			VALUES
				(:SORT,:USER_ID,:NETWORK,:SERVICE_URL,:DISP_NAME);");
		$stmt->bindParam(":SORT", $nextSort);
		$stmt->bindParam(":USER_ID", $id);
		$stmt->bindParam(":NETWORK", $network);
		$stmt->bindParam(":SERVICE_URL", $url);
		$stmt->bindParam(":DISP_NAME", $name);

		if (!$stmt->execute()) {
			error_log(" Add social media error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		return self::SUCCESS;

	}
	public static function addToArtist(\Redacted\Artist\Artist $artist, string $network, string $name, ?string $url) {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT IFNULL(MAX(`SORT`), 0) AS `NEXT_SORT` FROM `".DB_TABLES["artist_social_media"]."` WHERE `ARTIST_ID` = :ARTIST_ID;");
		$id = $artist->getId();
		$stmt->bindParam(":ARTIST_ID", $id);
		if (!$stmt->execute()) {
			error_log(" Add social media error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}
		$nextSort = $stmt->fetchAll()[0]["NEXT_SORT"];
		$stmt->closeCursor();

		$stmt = $GLOBALS["dbh"]->prepare("INSERT INTO `".DB_TABLES["artist_social_media"]."`
				(`SORT`, `ARTIST_ID`, `NETWORK`, `SERVICE_URL`, `DISP_NAME`)
			VALUES
				(:SORT,:ARTIST_ID,:NETWORK,:SERVICE_URL,:DISP_NAME);");
		$stmt->bindParam(":SORT", $nextSort);
		$stmt->bindParam(":ARTIST_ID", $id);
		$stmt->bindParam(":NETWORK", $network);
		$stmt->bindParam(":SERVICE_URL", $url);
		$stmt->bindParam(":DISP_NAME", $name);

		if (!$stmt->execute()) {
			error_log(" Add social media error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		return self::SUCCESS;
	}

	public static function delFromUser(\Redacted\User\User $user, int $id) {
		$stmt = $GLOBALS["dbh"]->prepare("DELETE FROM `".DB_TABLES["user_social_media"]."` WHERE `ID` = :ID AND `USER_ID` = :USER_ID;");
		$uid = $user->getId();
		$stmt->bindParam(":ID", $id);
		$stmt->bindParam(":USER_ID", $uid);

		if (!$stmt->execute()) {
			error_log(" Del social media error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		return self::SUCCESS;
	}

	public static function delFromArtist(\Redacted\Artist\Artist $artist, int $id) {
		$stmt = $GLOBALS["dbh"]->prepare("DELETE FROM `".DB_TABLES["artist_social_media"]."` WHERE `ID` = :ID AND `ARTIST_ID` = :ARTIST_ID;");
		$aid = $artist->getId();
		$stmt->bindParam(":ID", $id);
		$stmt->bindParam(":ARTIST_ID", $aid);

		if (!$stmt->execute()) {
			error_log(" Del social media error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		return self::SUCCESS;
	}

	public static function arrangeUser(\Redacted\User\User $user, array $ids) {
		$i = 0;
		$uid = $user->getId();
		foreach ($ids as $id) {
			$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["user_social_media"]."` SET `SORT` = :SORT WHERE `ID` = :ID AND `USER_ID` = :USER_ID;");
			$stmt->bindParam(":SORT", $i);
			$stmt->bindParam(":ID", $id);
			$stmt->bindParam(":USER_ID", $uid);

			if (!$stmt->execute()) {
				error_log(" Arrange social media error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
				return self::ERROR_UNKNOWN;
			}

			$i++;
		}

		return self::SUCCESS;
	}

	public static function arrangeArtist(\Redacted\Artist\Artist $artist, array $ids) {
		$i = 0;
		$aid = $artist->getId();
		foreach ($ids as $id) {
			$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["artist_social_media"]."` SET `SORT` = :SORT WHERE `ID` = :ID AND `ARTIST_ID` = :ARTIST_ID;");
			$stmt->bindParam(":SORT", $i);
			$stmt->bindParam(":ID", $id);
			$stmt->bindParam(":ARTIST_ID", $aid);

			if (!$stmt->execute()) {
				error_log(" Arrange social media error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
				return self::ERROR_UNKNOWN;
			}

			$i++;
		}

		return self::SUCCESS;
	}
}
