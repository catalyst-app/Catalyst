<?php

namespace Catalyst\Database\FeatureBoard;

class Comment {
	public static function get(int $id) : array {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `".DB_TABLES["users"]."`.`NICK`,`BODY` FROM `".DB_TABLES["feature_board_comments"]."` INNER JOIN `".DB_TABLES["users"]."` ON `".DB_TABLES["users"]."`.`ID` = `".DB_TABLES["feature_board_comments"]."`.`USER_ID` WHERE `FEATURE_ID` = :FEATURE_ID ORDER BY `".DB_TABLES["feature_board_comments"]."`.`ID` DESC;");
		$stmt->bindParam(":FEATURE_ID", $id);
		$stmt->execute();

		return $stmt->fetchAll();
	}
}
