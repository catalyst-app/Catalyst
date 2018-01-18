<?php

namespace Catalyst\Database\FeatureBoard;

class Item {
	public static function getForGroup(string $group) : array {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `".DB_TABLES["feature_board_items"]."`.`ID`,`".DB_TABLES["feature_board_items"]."`.`NAME`,`".DB_TABLES["feature_board_items"]."`.`AUTOGEN_URL`,`".DB_TABLES["feature_board_items"]."`.`CREATED_TS`,`".DB_TABLES["feature_board_items"]."`.`AUTHOR_ID`,`".DB_TABLES["feature_board_items"]."`.`STATUS` AS `STATUS_KEY`,`".DB_TABLES["feature_board_statuses"]."`.`NAME` AS `STATUS` FROM `".DB_TABLES["feature_board_items"]."` INNER JOIN `".DB_TABLES["feature_board_statuses"]."` ON `".DB_TABLES["feature_board_statuses"]."`.`INTERNAL_NAME` = `".DB_TABLES["feature_board_items"]."`.`STATUS` WHERE `".DB_TABLES["feature_board_items"]."`.`STATUS` != 'AWAITING_REVIEW' AND `GROUP` = :GROUP;");
		$stmt->bindParam(":GROUP", $group);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	public static function getVotes(int $id) : array {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `VOTE_TYPE` FROM `".DB_TABLES["feature_board_votes"]."` WHERE `FEATURE_ID` = :FEATURE_ID;");
		$stmt->bindParam(":FEATURE_ID", $id);
		$stmt->execute();

		return array_count_values(array_column($stmt->fetchAll(), 0)) + ["YES"=>0,"MAYBE"=>0,"NO"=>0,"IRRELEVANT"=>0];
	}

	public static function hasVoted(int $id, int $uid) : bool {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT 1 FROM `".DB_TABLES["feature_board_votes"]."` WHERE `USER_ID` = :USER_ID AND `FEATURE_ID` = :FEATURE_ID;");
		$stmt->bindParam(":USER_ID", $uid);
		$stmt->bindParam(":FEATURE_ID", $id);
		$stmt->execute();

		return (bool)($stmt->rowCount());
	}

	public static function castVote(int $id, string $vote, int $uid) : bool {
		$stmt = $GLOBALS["dbh"]->prepare("INSERT INTO `".DB_TABLES["feature_board_votes"]."` (`USER_ID`,`FEATURE_ID`,`VOTE_TYPE`) VALUES (:USER_ID,:FEATURE_ID,:VOTE_TYPE);");
		$stmt->bindParam(":USER_ID", $uid);
		$stmt->bindParam(":FEATURE_ID", $id);
		$stmt->bindParam(":VOTE_TYPE", $vote);
		if (!$stmt->execute()) {
			return false;
		}

		return true;
	}
}
