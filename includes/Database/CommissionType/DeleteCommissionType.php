<?php

namespace Redacted\Database\CommissionType;

class DeleteCommissionType {
	public const SUCCESS = 0;
	public const NOT_LOGGED_IN = 1;
	public const NOT_AN_ARTIST = 2;
	public const ID_INVALID = 3;
	public const ERROR_UNKNOWN = 4;

	public const PHRASES = [
		self::SUCCESS => "Success",
		self::NOT_LOGGED_IN => "You are not logged in.  Please refresh or try again.",
		self::NOT_AN_ARTIST => "You are not an artist.  Please create an artist page",
		self::ID_INVALID => "Invalid ID",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public static $lastErrId = -1;

	public static function delete(
		int $id,
		\Redacted\Artist\Artist $artist
	) : int {
		$aid = $artist->getId();
		$stmt = $GLOBALS["dbh"]->prepare("
			UPDATE
				`".DB_TABLES["commission_types"]."`
			SET
				`DELETED` = 1
			WHERE
					`ARTIST_PAGE_ID` = :ARTIST_PAGE_ID
				AND
					`ID` = :ID;");
		$stmt->bindParam(":ARTIST_PAGE_ID", $aid);
		$stmt->bindParam(":ID", $id);
		if (!$stmt->execute()) {
			error_log(" Delete commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		return self::SUCCESS;
	}
}
