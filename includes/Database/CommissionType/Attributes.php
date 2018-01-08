<?php

namespace Redacted\Database\CommissionType;

class Attributes {
	private static $meta = null;

	public static function get() : array {
		if (is_array(self::$meta)) {
			return self::$meta;
		}

		$stmt = $GLOBALS["dbh"]->query("
			SELECT
				`".DB_TABLES["commission_type_attributes"]."`.`SET_KEY`,
				`".DB_TABLES["commission_type_attributes"]."`.`NAME`,
				`".DB_TABLES["commission_type_attributes"]."`.`DESCRIPTION`,
				`".DB_TABLES["commission_type_attributes"]."`.`GROUP`,
				`".DB_TABLES["commission_type_attribute_groups"]."`.`LABEL`
					AS `GROUP_LABEL`
			FROM
				`".DB_TABLES["commission_type_attributes"]."`
			LEFT JOIN
				`".DB_TABLES["commission_type_attribute_groups"]."`
				ON
					`".DB_TABLES["commission_type_attributes"]."`.`GROUP` = `".DB_TABLES["commission_type_attribute_groups"]."`.`GROUP`
			ORDER BY
				`".DB_TABLES["commission_type_attribute_groups"]."`.`SORT` ASC,
				`".DB_TABLES["commission_type_attributes"]."`.`SORT` ASC;");
		
		$result = $stmt->fetchAll();
		
		$stmt->closeCursor();

		$resultArr = [];

		foreach ($result as $row) {
			if (!isset($resultArr[$row["GROUP"]])) {
				$resultArr[$row["GROUP"]] = [
					"label" => $row["GROUP_LABEL"],
					"items" => []
				];
			}
			$resultArr[$row["GROUP"]]["items"][] = [
				$row["SET_KEY"],
				$row["NAME"],
				$row["DESCRIPTION"]
			];
		}

		return (self::$meta = $resultArr);
	}
}
