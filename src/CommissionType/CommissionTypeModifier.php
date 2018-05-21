<?php

namespace Catalyst\CommissionType;

use \Catalyst\Database\{AbstractDatabaseModel, Column, Tables};
use \Catalyst\Database\Query\InsertQuery;

/**
 * Represents a commission type modifier
 *
 * Basic model class, nothing fancy
 *
 * @method string getName()
 * @method void setName(string $name)
 * @method string getBaseCost()
 * @method void setBaseCost(string $baseCost)
 * @method float getBaseUsdCost()
 * @method void setBaseUsdCost(float $baseUsdCost)
 */
class CommissionTypeModifier extends AbstractDatabaseModel {
	/**
	 * Columns to prefetch from constructor
	 *
	 * @return string[]
	 */
	public static function getPrefetchColumns() : array {
		return [
			"COMMISSION_TYPE_ID",
			"NAME",
			"PRICE",
			"USDEQ",
			"GROUP",
		];
	}

	/**
	 * Table in which data resides in
	 *
	 * @return string
	 */
	public static function getTable() : string {
		return Tables::COMMISSION_TYPE_MODIFIERS;
	}

	/**
	 * Get values to set upon deletion
	 *
	 * We don't delete any information such as name/etc because artist's will need this information in the future for previous commissions
	 * @return array
	 */
	public static function getDeletedValues() : array {
		return [
			"DELETED" => true,
		];
	}

	/**
	 * @return CommissionTypeModifierGroup
	 */
	public function getGroup() : CommissionTypeModifierGroup {
		return $this->getDataFromCallableOrCache("GROUP_OBJ", function() : CommissionTypeModifierGroup {
			return new CommissionTypeModifierGroup($this->getColumnFromDatabaseOrCache("GROUP"));
		});
	}

	/**
	 * @param CommissionTypeModifierGroup $group
	 */
	public function setGroup(CommissionTypeModifierGroup $group) : void {
		$this->updateColumnInDatabase("GROUP", $group->getId());
	}

	/**
	 * Create a new CommissionTypeModifier from the given info
	 *
	 * @param array $values
	 * @return self
	 */
	public static function create(array $values) : self {
		// no prefilling is available as this is such a low-level component
		$stmt = new InsertQuery();

		$stmt->setTable(self::getTable());

		foreach (["COMMISSION_TYPE_ID", "NAME", "PRICE", "USDEQ", "GROUP", "MULTIPLE"] as $column) {
			$stmt->addColumn(new Column($column, self::getTable()));
			$stmt->addValue($values[$column]);
		}

		$stmt->execute();

		return new self($stmt->getResult(), $values, false);
	}

	/**
	 * Easily modifiable properties of the object
	 *
	 * @return array
	 */
	public static function getModifiableProperties() : array {
		return [
			"Name" => ["NAME", null, null],
			"BaseCost" => ["PRICE", null, null],
			"BaseUsdCost" => ["USDEQ", null, null],
		];
	}
}
