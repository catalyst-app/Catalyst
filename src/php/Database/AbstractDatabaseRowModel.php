<?php

namespace Catalyst\Database;

use \Catalyst\Database\Query\DeleteQuery;
use \Catalyst\Database\QueryAddition\WhereClause;
use \LogicException;

/**
 * Used by database models that need to get stuff and ARE THEIR OWN ROW
 */
abstract class AbstractDatabaseRowModel extends AbstractDatabaseModel {
	/**
	 * IDs to delete
	 * @var int[][]
	 */
	static $toDelete = [];

	/**
	 * Deletes the item (deletes images, deletes cache, fills row with deleted values)
	 */
	public function delete() : void {
		$this->additionalDeletion();

		$this->_clearCache();

		if (!array_key_exists(static::getTable(), self::$toDelete)) {
			self::$toDelete[static::getTable()] = [];
		}

		self::$toDelete[static::getTable()][] = $this->getId();

		if (method_exists($this, "deleteSocialChipsFromDatabase")) {
			$this->deleteSocialChipsFromDatabase();
		}

		if (method_exists($this, "getImage")) {
			$this->getImage()->delete();
		}
		if (method_exists($this, "getImageSet")) {
			foreach ($this->getImageSet() as $image) {
				$image->delete();
			}
		}
	}

	public function getDeletedValues() : array {
		throw new LogicException("No using getDeletedValues on AbstractDatabaseRowModel");
	}

	/**
	 * Write the queued deletions to DB
	 */
	public static function writeAllDeletions() : void {
		if (empty(self::$toDelete)) {
			return;
		}

		foreach (self::$toDelete as $table => $ids) {
			if (empty($ids)) {
				continue;
			}

			$stmt = new DeleteQuery();

			$stmt->setTable($table);

			$whereClause = new WhereClause();
			$whereClause->addToClause([new Column("ID", $table), 'IN', array_unique($ids)]);
			$stmt->addAdditionalCapability($whereClause);

			$stmt->execute();
		}

		self::$toDelete = [];
	}
}
