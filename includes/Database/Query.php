<?php

namespace Catalyst\Database;

use \InvalidArgumentException;

/**
 * A standard class which will handle all database interaction
 * 
 * Contains a common interface for creating and executing PDOStatements
 * @abstract
 */
abstract class Query {
	/**
	 * String contianing table name
	 * 
	 * @var string
	 */
	protected $table = "";
	/**
	 * List of column names
	 * 
	 * @var Column[]
	 */
	protected $columns = [];
	/**
	 * List of values (if applicable)
	 * 
	 * @var array
	 */
	protected $values = [];
	/**
	 * An array of QueryAddition
	 * 
	 * @var QueryAddition[]
	 */
	protected $additionalCapabilities = [];
	/**
	 * Result
	 * 
	 * @var array
	 */
	protected $result = null;

	/**
	 * Creates a Query object
	 * 
	 * @param string $table Table to affect/target
	 * @param Column[] $columns Column list to affect/target
	 * @param array $values List of values to bind to the above columns
	 * @param QueryAddition[] $additionalCapabilities Single or multiple QueryAddition
	 */
	public function __construct(string $table="", array $columns=[], array $values=[], $additionalCapabilities=[]) {
		$this->setTable($table);
		$this->setColumns($columns);
		$this->setValues($values);
		$this->setAdditionalCapabilities($additionalCapabilities);
	}

	/**
	 * Returns the current table being targeted by the query
	 * 
	 * @return string The currently targeted table, or empty string if none yet
	 */
	public function getTable() : string {
		return $this->table;
	}

	/**
	 * Set the targeted table to a new value 
	 * 
	 * @param string $table The new table to target
	 */
	public function setTable(string $table) : void {
		$this->table = $table;
	}

	/**
	 * Get the currently targeted columns
	 * 
	 * @return Column[] List of columns being operated upon
	 */
	public function getColumns() : array {
		return $this->columns;
	}

	/**
	 * Add a column to the list being targeted
	 * 
	 * @param Column $column Column to add
	 */
	public function addColumn(Column $column) : void {
		$this->columns[] = $column;
	}

	/**
	 * Add a series of columns to the list
	 * 
	 * @param Column[] $columns Columns to add
	 */
	public function addColumns(array $columns) : void {
		array_map([$this, "addColumn"], $columns);
	}

	/**
	 * Set the current list of columns to a new value
	 * 
	 * @param Column[] $columns New list of columns
	 */
	public function setColumns(array $columns) : void {
		$this->columns = $columns;
	}

	/**
	 * Remove a column from the list of columns
	 * 
	 * @param Column $column The column to remove
	 * @return bool If the column was removed
	 */
	public function removeColumn(Column $column) : bool {
		$initialCount = count($this->columns);
		$this->columns = array_filter($this->columns, function($in) use ($column) {
			return $in != $column;
		});
		return count($this->columns) != $initialCount;
	}

	/**
	 * Remove sevaral columns from the list of columns
	 * 
	 * @param Column[] $columns The list of columns to remove
	 * @return bool If count($columns) columns were removed
	 */
	public function removeColumns(array $columns) : bool {
		$initialCount = count($this->columns);
		$this->columns = array_filter($this->columns, function($in) use ($columns) {
			return !in_array($in, $columns);
		});
		return $initialCount - count($this->columns) == count($columns);
	}

	/**
	 * Add a value to the array of values
	 * 
	 * @param mixed $value Value to add
	 */
	public function addValue($value) : void {
		$this->values[] = $value;
	}

	/**
	 * Add a series of values to the values array
	 * 
	 * $values must be an array in the order of values to bind
	 * @param array $values List of values to add to the value array
	 */
	public function addValues(array $values) : void {
		array_map([$this, "addValue"], $values);
	}

	/**
	 * Set the set of values to a new value
	 * 
	 * $values must be an array in the order of values to bind
	 * @param array $values New list of values
	 */
	public function setValues(array $values) : void {
		$this->values = $values;
	}

	/**
	 * Get the additional capabilities for the query
	 * 
	 * @return QueryAddition[] The additions for the query (joins, etc)
	 */
	public function getAdditionalCapabilities() : array {
		return $this->additionalCapabilities;
	}

	/**
	 * Add an additional capability for the query
	 * 
	 * @param QueryAddition $additionalCapability The addition to add to the query
	 */
	public function addAdditionalCapability(QueryAddition $additionalCapability) : void {
		$this->additionalCapabilities[] = $additionalCapability;
	}

	/**
	 * Set the additional capabilities for the query
	 * 
	 * @param QueryAddition[] $additionalCapabilities The new list of additions for the query
	 */
	public function setAdditionalCapabilities(array $additionalCapabilities) : void {
		$this->additionalCapabilities = $additionalCapabilities;
	}

	/**
	 * Verifies that all of the parameters for the query are valid
	 * 
	 * @return bool If it was valid
	 * @throws InvalidArgumentException on error
	 */
	public function verifyIntegrity() : bool {
		if (empty($this->table) || !is_string($this->table)) {
			throw new InvalidArgumentException("Invalid table for Query");
		}
		if (!is_array($this->columns)) {
			throw new InvalidArgumentException("Query columns is not an array");
		}
		foreach ($this->columns as $column) {
			if (!$column instanceof Column) {
				throw new InvalidArgumentException("Column is not a valid [table,column] array");
			}
		}
		if (!is_array($this->additionalCapabilities)) {
			throw new InvalidArgumentException("Additional capabilities is not a valid type");
		}
		foreach ($this->additionalCapabilities as $additionalCapability) {
			if (!($additionalCapability instanceof QueryAddition)) {
				throw new InvalidArgumentException("Additional capability is not a QueryAddition");
			}
		}
		return true;
	}

	/**
	 * Gets a list of parameters to bind
	 * 
	 * @return array Parameters to pass to PDOStatement->execute
	 */
	public function getParamtersToBind() : array {
		$params = $this->values;
		if (is_array($this->additionalCapabilities)) {
			foreach ($this->additionalCapabilities as $item) {
				$params = array_merge($params, $item->getParamtersToBind());
			}
		} elseif (!is_null($this->additionalCapabilities)) {
			$params = array_merge($params, $this->additionalCapabilities->getParamtersToBind());
		}
		return $params;
	}

	/**
	 * Executes the query
	 * 
	 * This is where all the real logic and execution occurs
	 * 
	 * @return bool if the query was successful
	 * @abstract
	 */
	public abstract function execute() : bool;

	/**
	 * Returns the result from ->execute
	 * 
	 * @return mixed bool for certain types, id, or data if applicable
	 * @throws LogicException if the query has not been executed
	 */
	public function getResult() {
		return $this->result;
	}
}
