<?php

namespace Catalyst\Database;

use \InvalidArgumentException;

/**
 * Respresents a column in the DB
 */
class Column {
	/**
	 * Name of table
	 * 
	 * @var string|null
	 */
	protected $table = null;
	/**
	 * Name of column
	 * 
	 * @var string|null
	 */
	protected $column = null;

	/**
	 * Creates a Column object
	 * 
	 * @param string|null $column Column name
	 * @param string|null $table Table for the column, null to assume
	 */
	public function __construct(?string $column=null, ?string $table=null) {
		$this->setTable($table);
		$this->setColumn($column);
	}

	/**
	 * Returns the column's table, or null if ambiguous
	 * 
	 * @return string|null The currently targeted table, or null if unspecified
	 */
	public function getTable() : ?string {
		return $this->table;
	}

	/**
	 * Set the column table to a new value
	 * 
	 * @param string|null $table The new table for the column
	 */
	public function setTable(?string $table) : void {
		$this->table = $table;
	}

	/**
	 * Get the column name, or null if not specified
	 * 
	 * @return string|null The current column, or null if unspecified
	 */
	public function getColumn() : ?string {
		return $this->column;
	}

	/**
	 * Set the column name
	 * 
	 * @param string|null $column The new column name
	 */
	public function setColumn(?string $column) : void {
		$this->column = $column;
	}

	/**
	 * Returns the result from ->execute
	 * 
	 * @return string The `table`.`col` or `col` representation
	 * @throws InvalidArgumentException if col hasn't been defined
	 */
	public function __toString() : string {
		$str = '`';
		if (!is_null($this->table)) {
			$str .= $this->table.'`.`';
		}
		if (is_null($this->column)) {
			throw new InvalidArgumentException("Column not specified");
		}
		$str .= $this->column.'`';
		return $str;
	}
}
