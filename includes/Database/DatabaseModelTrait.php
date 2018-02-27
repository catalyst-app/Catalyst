<?php

namespace Catalyst\Database;

/**
 * Used by database models that need to get stuff 
 */
trait DatabaseModelTrait {
	/**
	 * Get the class' ID
	 * 
	 * @return int
	 */
	abstract public function getId() : int;

	/**
	 * Get the table in which the object's data is stored in
	 * 
	 * @return string table name
	 */
	abstract public static function getTable() : string;

	/**
	 * Returns the column's value from the database
	 * 
	 * @param string $column Column to get
	 * @return mixed
	 */
	public function getColumnFromDatabase(string $column) {
		$stmt = new SelectQuery();

		$stmt->setTable($this->getTable());
		$stmt->addColumn(new Column($column, $this->getTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("ID", $this->getTable()), "=", $this->getId()]);

		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		return $stmt->getResult()[0][$column];
	}
}
