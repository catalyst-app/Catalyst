<?php

namespace Catalyst\CommissionType;

use \Catalyst\Database\{AbstractDatabaseModel, Column, Tables};
use \Catalyst\Database\Query\InsertQuery;

/**
 * Represents a commission type stage
 *
 * Basic model class, nothing fancy
 * @method int getCommissionTypeId()
 * @method void setCommissionTypeId(int $commissionTypeId)
 * @method string getStage()
 * @method void setStage(string $stage)
 * @method bool getDeleted()
 * @method void setDeleted(bool $deleted)
 */
class CommissionTypeStage extends AbstractDatabaseModel {
	/**
	 * Columns to prefetch from constructor
	 *
	 * @return string[]
	 */
	public static function getPrefetchColumns() : array {
		return [
			"COMMISSION_TYPE_ID",
			"STAGE",
			"DELETED",
		];
	}

	/**
	 * Table in which data resides in
	 *
	 * @return string
	 */
	public static function getTable() : string {
		return Tables::COMMISSION_TYPE_STAGES;
	}

	/**
	 * Get values to set upon deletion
	 *
	 * We don't delete any information such as name/etc because artist's will need this information in the future for previous commissions
	 * No [Deleted] because these are moved around and that may cause things to be weird
	 * @return array
	 */
	public function getDeletedValues() : array {
		return [
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

		foreach (["COMMISSION_TYPE_ID", "STAGE"] as $column) {
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
			"Stage" => ["STAGE", null, null],
			"Deleted" => ["DELETED", "boolval", null],
		];
	}
}
