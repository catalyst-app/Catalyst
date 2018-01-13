<?php

namespace Catalyst\Database\Integrations;

class Meta {
	private static $meta = null;

	public static function get() : array {
		if (is_array(self::$meta)) {
			return self::$meta;
		}

		$stmt = $GLOBALS["dbh"]->query("SELECT `VISIBLE`,`INTEGRATION_NAME`,`IMAGE_PATH`,`DEFAULT_HUMAN_NAME`,`CHIP_CLASSES` FROM `".DB_TABLES["integrations_meta"]."` ORDER BY `SORT_ORDER` ASC;");
		
		$result = $stmt->fetchAll();
		
		$stmt->closeCursor();

		$result = array_merge(...array_map(function($in) {
			return [$in["INTEGRATION_NAME"] => [ROOTDIR."integration_icons/".$in["IMAGE_PATH"], $in["DEFAULT_HUMAN_NAME"], $in["CHIP_CLASSES"], $in["VISIBLE"]]];
		}, $result));

		return (self::$meta = $result);
	}

	public static function getProfileAddSelectArray() : array {
		$arr = array_filter(self::get(), function($in) { return $in[3]; });
		return array_map(function($a, $b) {
			return [$a, $b[1]];
		}, array_keys($arr), $arr);
	}
}
