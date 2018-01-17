<?php

namespace Catalyst\Database\FeatureBoard;

class Groups {
	public static function get() : array {
		$stmt = $GLOBALS["dbh"]->query("SELECT `INTERNAL_NAME`,`NAME` FROM `".DB_TABLES["feature_board_groups"]."` ORDER BY `SORT` ASC;");
		$stmt->execute();

		return array_map(function($in) { return [$in["INTERNAL_NAME"], $in["NAME"]]; }, $stmt->fetchAll());
	}
}
