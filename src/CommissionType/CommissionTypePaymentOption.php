<?php

namespace Catalyst\CommissionType;

use \Catalyst\Database\{AbstractDatabaseModel, Column, Tables};
use \Catalyst\Database\Query\InsertQuery;

/**
 * Represents a commission type payment option
 *
 * Basic model class, nothing fancy
 * @method int getCommissionTypeId()
 * @method void setCommissionTypeId(int $commissionTypeId)
 * @method string getType()
 * @method void setType(string $type)
 * @method string getAddress()
 * @method void setAddress(string $address)
 * @method string getInstructions()
 * @method void setInstructions(string $instructions)
 * @method int getSort()
 * @method void setSort(int $sort)
 * @method bool getDeleted()
 * @method void setDeleted(bool $deleted)
 */
class CommissionTypePaymentOption extends AbstractDatabaseModel {
	/**
	 * Columns to prefetch from constructor
	 *
	 * @return string[]
	 */
	public static function getPrefetchColumns() : array {
		return [
			"COMMISSION_TYPE_ID",
			"TYPE",
			"ADDRESS",
			"INSTRUCTIONS",
			"SORT",
			"DELETED",
		];
	}

	/**
	 * Table in which data resides in
	 *
	 * @return string
	 */
	public static function getTable() : string {
		return Tables::COMMISSION_TYPE_PAYMENT_OPTIONS;
	}

	/**
	 * Get values to set upon deletion
	 *
	 * We don't delete any information such as name/etc because artist's will need this information in the future for previous commissions
	 * @return array
	 */
	public function getDeletedValues() : array {
		return [
			"TYPE" => "[Deleted] ".substr($this->getType(), 0, 54),
			"DELETED" => true,
		];
	}

	/**
	 * Create a new CommissionTypePaymentOption from the given info
	 *
	 * @param array $values
	 * @return self
	 */
	public static function create(array $values) : self {
		// no prefilling is available as this is such a low-level component
		$stmt = new InsertQuery();

		$stmt->setTable(self::getTable());

		foreach (["COMMISSION_TYPE_ID", "TYPE", "ADDRESS", "INSTRUCTIONS", "SORT"] as $column) {
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
			"CommissionTypeId" => ["COMMISSION_TYPE_ID", null, null],
			"Type" => ["TYPE", null, null],
			"Address" => ["ADDRESS", null, null],
			"Instructions" => ["INSTRUCTIONS", null, null],
			"Sort" => ["SORT", null, null],
			"Deleted" => ["DELETED", "boolval", null],
		];
	}
}
