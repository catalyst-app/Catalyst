<?php

namespace Catalyst\Database\CommissionType;

class Wishlist {
	public const SUCCESS = 0;
	public const ERROR_UNKNOWN = 1;

	public const PHRASES = [
		self::SUCCESS => "Success",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public static $lastErrId = -1;

	public static function add(\Catalyst\User\User $user, int $ctid) : int {
		if ($user->idIsOnWishlist($ctid)) {
			return self::ERROR_UNKNOWN;
		}

		$stmt = $GLOBALS["dbh"]->prepare("
			INSERT INTO `".DB_TABLES["user_wishlists"]."`
				(`USER_ID`, `COMMISSION_TYPE_ID`)
					VALUES
				(:USER_ID, :COMMISSION_TYPE_ID);
			");
		$uid = $user->getId();
		$stmt->bindParam(":USER_ID", $uid);
		$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
		if (!$stmt->execute($arr)) {
			error_log(" Add to wishlist error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		return self::SUCCESS;
	}

	public static function del(\Catalyst\User\User $user, int $ctid) : int {
		if (!$user->idIsOnWishlist($ctid)) {
			return self::ERROR_UNKNOWN;
		}

		$stmt = $GLOBALS["dbh"]->prepare("
			DELETE FROM `".DB_TABLES["user_wishlists"]."`
			WHERE
				`USER_ID` = :USER_ID
				AND
				`COMMISSION_TYPE_ID` = :COMMISSION_TYPE_ID;
			");
		$uid = $user->getId();
		$stmt->bindParam(":USER_ID", $uid);
		$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
		if (!$stmt->execute($arr)) {
			error_log(" Remove from wishlist error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		return self::SUCCESS;
	}
}
