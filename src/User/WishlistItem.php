<?php

namespace Catalyst\User;

use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Database\{AbstractDatabaseRowModel, Column, Tables};
use \Catalyst\Database\Query\{InsertQuery, SelectQuery};
use \Catalyst\Database\QueryAddition\WhereClause;

/**
 * Represents a commission type stage
 *
 * Basic model class, nothing fancy
 * @method int getCommissionTypeId()
 * @method void setCommissionTypeId(int $commissionTypeId)
 * @method int getCommissionTypeId()
 * @method void setCommissionTypeId(int $commissionTypeId)
 */
class WishlistItem extends AbstractDatabaseRowModel {
	/**
	 * Columns to prefetch from constructor
	 *
	 * @return string[]
	 */
	public static function getPrefetchColumns() : array {
		return [
			"USER_ID",
			"COMMISSION_TYPE_ID",
		];
	}

	/**
	 * Table in which data resides in
	 *
	 * @return string
	 */
	public static function getTable() : string {
		return Tables::USER_WISHLISTS;
	}

	/**
	 * Get the wishlist for a user
	 *
	 * @return self[]
	 */
	public static function getUserWishlist(User $user) : array {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));
		$stmt->addColumn(new Column("COMISSION_TYPE_ID", self::getTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("USER_ID", Tables::USER_WISHLISTS), "=", $user->getId()]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		return array_map(function($row) use ($user) {
			new self($row["ID"], [
				"USER_ID" => $user->getId(),
				"COMMISSION_TYPE_ID" => $row["COMMISSION_TYPE_ID"],
			], false);
		}, $stmt->getResult());
	}

	/**
	 * Get the wishlist for a commission type
	 *
	 * @return self[]
	 */
	public static function getForCommissionType(CommissionType $commissionType) : array {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));
		$stmt->addColumn(new Column("USER_ID", self::getTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("COMISSION_TYPE_ID", Tables::USER_WISHLISTS), "=", $commissionType->getId()]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		return array_map(function($row) use ($commissionType) {
			new self($row["ID"], [
				"USER_ID" => $row["USER_ID"],
				"COMMISSION_TYPE_ID" => $commissionType->getId(),
			], false);
		}, $stmt->getResult());
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

		foreach (["USER_ID", "COMMISSION_TYPE_ID"] as $column) {
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
			"UserId" => ["USER_ID", null, null],
		];
	}
}
