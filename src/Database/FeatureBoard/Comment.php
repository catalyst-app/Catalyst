<?php

namespace Catalyst\Database\FeatureBoard;

class Comment {
	public const SUCCESS = 0;
	public const COMMENT_INVALID = 1;
	public const ERROR_UNKNOWN = 2;

	public const PHRASES = [
		self::SUCCESS => "Success",
		self::COMMENT_INVALID => "Invalid comment",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public static function get(int $id) : array {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `".DB_TABLES["users"]."`.`NICK`,`BODY` FROM `".DB_TABLES["feature_board_comments"]."` INNER JOIN `".DB_TABLES["users"]."` ON `".DB_TABLES["users"]."`.`ID` = `".DB_TABLES["feature_board_comments"]."`.`USER_ID` WHERE `FEATURE_ID` = :FEATURE_ID ORDER BY `".DB_TABLES["feature_board_comments"]."`.`ID` DESC;");
		$stmt->bindParam(":FEATURE_ID", $id);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "newcomment",
				"ajax" => true,
				"eval" => 'window.location = "";',
				"method" => "POST",
				"handler" => '"+$("html").attr("data-rootdir")+"FeatureBoard/comment.php',
				"button" => "create",
				"additional_cases" => [],
				"additional_fields" => [
					"feature" => '$(".meta-feature-id").attr("data-id")'
				],
				"success" => self::PHRASES[self::SUCCESS],
			],
			[
				"name" => "comment",
				"wrapper_classes" => "col s12",
				"type" => "markdown-textarea",
				"label" => "Comment",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::COMMENT_INVALID]],
				"error_code" => [self::COMMENT_INVALID],
			],
		];
	}

	public static function new(int $id, int $uid, string $comment) : int {
		$stmt = $GLOBALS["dbh"]->prepare("INSERT INTO `".DB_TABLES["feature_board_comments"]."` (`USER_ID`,`FEATURE_ID`,`BODY`) VALUES (:USER_ID,:FEATURE_ID,:BODY);");
		$stmt->bindParam(":USER_ID", $uid);
		$stmt->bindParam(":FEATURE_ID", $id);
		$stmt->bindParam(":BODY", $comment);

		if (!$stmt->execute()) {
			return self::ERROR_UNKNOWN;
		}

		return self::SUCCESS;
	}
}
