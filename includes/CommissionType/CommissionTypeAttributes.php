<?php

namespace Catalyst\CommissionType;

use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\{JoinClause, MultipleOrderByClause, OrderByClause};
use \Catalyst\Database\Query\SelectQuery;

/**
 * Used to handle array of commission type attributes
 */
class CommissionTypeAttributes {
	/**
	 * Cache of attributes, in DB format
	 * @var null|string
	 */
	protected static $attributes = null;

	/**
	 * Gets the attributes in an internal format, pulls from DB if needed
	 * 
	 * @return array attributes
	 */
	protected static function getAttributes() : array {
		if (!is_null(self::$attributes)) {
			return self::$attributes;
		}
		$stmt = new SelectQuery();

		$stmt->setTable(Tables::COMMISSION_TYPE_ATTRIBUTES);

		$stmt->addColumn(new Column("SET_KEY", Tables::COMMISSION_TYPE_ATTRIBUTES));
		$stmt->addColumn(new Column("NAME", Tables::COMMISSION_TYPE_ATTRIBUTES));
		$stmt->addColumn(new Column("DESCRIPTION", Tables::COMMISSION_TYPE_ATTRIBUTES));
		$stmt->addColumn(new Column("LABEL", Tables::COMMISSION_TYPE_ATTRIBUTE_GROUPS));

		$joinClause = new JoinClause();
		$joinClause->setType(JoinClause::LEFT);
		$joinClause->setJoinTable(Tables::COMMISSION_TYPE_ATTRIBUTE_GROUPS);
		$joinClause->setLeftColumn(new Column("ID", Tables::COMMISSION_TYPE_ATTRIBUTE_GROUPS));
		$joinClause->setRightColumn(new Column("GROUP_ID", Tables::COMMISSION_TYPE_ATTRIBUTES));
		$stmt->addAdditionalCapability($joinClause);

		$orderByClause = new MultipleOrderByClause();

		$primaryOrderByClause = new OrderByClause();
		$primaryOrderByClause->setColumn(new Column("ID", Tables::COMMISSION_TYPE_ATTRIBUTE_GROUPS));
		$primaryOrderByClause->setOrder("ASC");
		$orderByClause->addClause($primaryOrderByClause);

		$secondOrderByClause = new OrderByClause();
		$secondOrderByClause->setColumn(new Column("SORT", Tables::COMMISSION_TYPE_ATTRIBUTES));
		$secondOrderByClause->setOrder("ASC");
		$orderByClause->addClause($secondOrderByClause);

		$stmt->addAdditionalCapability($orderByClause);

		$stmt->execute();

		$result = $stmt->getResult();

		$realResult = [];

		foreach ($result as $row) {
			if (!array_key_exists($row["LABEL"], $realResult)) {
				$realResult[$row["LABEL"]] = [];
			}
			$realResult[$row["LABEL"]][] = [$row["SET_KEY"],$row["NAME"],$row["DESCRIPTION"]];
		}

		return self::$attributes = $realResult;
	}

	/**
	 * Get the attributes in such a way that the resulting array can be used in a ToggleableButtonSetField
	 * @return array
	 */
	public static function getButtonSet() : array {
		return self::getAttributes();
	}
}
