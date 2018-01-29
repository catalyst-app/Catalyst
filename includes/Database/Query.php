<?php

namespace Catalyst\Database;

/**
 * A standard class which will handle all database interaction
 * 
 * Contains a common interface for creating and executing PDOStatements
 * @abstract
 */
abstract class Query {
	/**
	 * String contianing table name
	 */
	protected $table = "";
	/**
	 * List of column names
	 */
	protected $columns = [];
	/**
	 * List of values for column names (if applicable)
	 * 
	 * Should take the structure of value, in the same order as $this->columns
	 */
	protected $values = [];
	/**
	 * An array of \Catalyst\Database\QueryAddition
	 */
	protected $additionalCapabilities = [];
	/**
	 * Result
	 */
	protected $result = null;

	/**
	 * Creates a Query object
	 * 
	 * @param string $table Table to affect/target
	 * @param string[] $columns Column list to affect/target
	 * @param array $values List of values to bind to the above columns
	 * @param \Catalyst\Database\QueryAddition[] $additionalCapabilities Single or multiple \Catalyst\Database\QueryAddition
	 */
	public function __construct(string $table="", array $columns=[], array $values=[], $additionalCapabilities=[]) {
		$this->table = $table;
		$this->columns = $columns;
		$this->values = $values;
		$this->additionalCapabilities = $additionalCapabilities;
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
	 * Some examples may be a DELETE, SELECT, UPDATE, etc.
	 * 
	 * @return array List of columns being operated upon
	 */
	public function getColumns() : array {
		return $this->columns;
	}

	/**
	 * Add a column to the list being targeted
	 * 
	 * @param string $column Column to add
	 */
	public function addColumn(string $column) : void {
		$this->columns[] = $column;
	}

	/**
	 * Add a series of columns to the list
	 * 
	 * @param string[] $columns Columns to add
	 */
	public function addColumns(array $columns) : void {
		array_map([$this, "addColumn"], $columns);
	}

	/**
	 * Set the current list of columns to a new value
	 * 
	 * @param string[] $columns New list of columns
	 */
	public function setColumns(array $columns) : void {
		$this->columns = $columns;
	}

	/**
	 * Remove a column from the list of columns
	 * 
	 * @param string $column The column to remove
	 * @return bool If the column was removed
	 */
	public function removeColumn(string $column) : bool {
		$initialCount = count($this->columns);
		$this->columns = array_filter($this->columns, function($in) use ($column) {
			return $in !== $column;
		});
		return count($this->columns) != $initialCount;
	}

	/**
	 * Remove sevaral columns from the list of columns
	 * 
	 * @param string[] $columns The list of columns to remove
	 * @return bool If count($columns) columns were removed
	 */
	public function removeColumns(array $columns) : bool {
		$initialCount = count($this->columns);
		$this->columns = array_filter($this->columns, function($in) use ($columns) {
			return !in_array($in, $columns, true);
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
		array_map([$this, "addColumn"], $values);
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
	 * @return \Catalyst\Database\QueryAddition[] The additions for the query (joins, etc)
	 */
	public function getAdditionalCapabilities() : array {
		return $this->additionalCapabilities;
	}

	/**
	 * Add an additional capability for the query
	 * 
	 * @param \Catalyst\Database\QueryAddition $additionalCapability The addition to add to the query
	 */
	public function addAdditionalCapability(\Catalyst\Database\QueryAddition $additionalCapability) : void {
		$this->additionalCapabilities[] = $additionalCapability;
	}

	/**
	 * Set the additional capabilities for the query
	 * 
	 * @param \Catalyst\Database\QueryAddition[] $additionalCapabilities The new list of additions for the query
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
			throw new \InvalidArgumentException("Invalid table for Query");
		}
		if (!is_array($this->columns)) {
			throw new \InvalidArgumentException("Query columns is not an array");
		}
		foreach ($this->columns as $column) {
			if (!is_string($column)) {
				throw new \InvalidArgumentException("Column is not a string");
			}
		}
		if (!is_array($this->additionalCapabilities)) {
			throw new \InvalidArgumentException("Additional capabilities is not a valid type");
		}
		foreach ($this->additionalCapabilities as $additionalCapability) {
			if (!($additionalCapability instanceof \Catalyst\Database\QueryAddition)) {
				throw new \InvalidArgumentException("Additional capability is not a QueryAddition");
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
