<?php

namespace Catalyst\Database;

use \LogicException;
use \InvalidArgumentException;

/**
 * Class which represents a JOIN clause in a MySQL SELECT query
 */
class JoinClause implements \Catalyst\Database\QueryAddition {
	/**
	 * Represents an INNER JOIN
	 */
	public const INNER = 0;
	/**
	 * Represents a LEFT JOIN
	 */
	public const LEFT = 1;
	/**
	 * Represents a RIGHT JOIN
	 */
	public const RIGHT = 2;
	/**
	 * Represents a FULL (outer) JOIN
	 */
	public const FULL = 3;

	/**
	 * Type of JOIN to perform
	 */
	protected $type = self::INNER;
	/**
	 * Table which will be JOINed to the query
	 */
	protected $joinTable = "";
	/**
	 * Condtion on which to join the tables
	 * 
	 * This is an array with 2 values, each columns (may be array of 2)
	 */
	protected $condition = ["",""];

	/**
	 * Constructs a JoinClause from the array of clause items
	 * 
	 * @param int $type Type of join clause, see class constants for info
	 * @param string $joinTable Table to join
	 * @param string[]|string[][] $condition Condition on which to join the tables, array with 2 values, one for left column, one for right
	 */
	public function __construct(int $type=self::INNER,string $joinTable="",array $condition=["",""]) {
		if ($type < self::INNER || $type > self::FULL) {
			throw new InvalidArgumentException("Invalid value passed for JoinClause type");
		}
		$this->type = $type;
		$this->joinTable = $joinTable;
		$this->condition = $condition;
	}

	/**
	 * Get the current type of JoinClause
	 * 
	 * @return int The current type, as defined by class constants
	 */
	public function getType() : int {
		return $this->type;
	}

	/**
	 * Set the type of the JOIN clause to a new one
	 * 
	 * @param int $type the new type, as definied by the class constants
	 */
	public function setType(int $type) : void {
		if ($type < self::INNER || $type > self::FULL) {
			throw new InvalidArgumentException("Invalid value passed for JoinClause type");
		}
		$this->type = $type;
	}

	/**
	 * Get the current table that will be joined
	 * 
	 * @return string The name of the table to be joined
	 */
	public function getJoinTable() : string {
		return $this->joinTable;
	}

	/**
	 * Set the table that will be joined
	 * 
	 * @param string $joinTable The name of the table to be joined
	 */
	public function setJoinTable(string $joinTable) : void {
		$this->joinTable = $joinTable;
	}

	/**
	 * Get the current condition for joining
	 * 
	 * @return string[]|string[][] The condition
	 */
	public function getCondition() : array {
		return $this->condition;
	}

	/**
	 * Set the join condition
	 * 
	 * @param string[]|string[][] The new condition
	 */
	public function setCondition(array $condition) : void {
		if (count($condition) !== 2) {
			throw new InvalidArgumentException("Invalid condition for JoinClause");
		}
		$this->condition = $condition;
	}

	/**
	 * Returns the properly-formated clause
	 * 
	 * @return string the WHERE clause (not including the WHERE directive)
	 * @throws InvalidArgumentException on invalid type
	 */
	public function getQueryString() : string {
		$str = '';
		switch ($this->type) {
			case self::INNER:
				$str = 'INNER';
				break;
			case self::LEFT:
				$str = 'LEFT';
				break;
			case self::RIGHT:
				$str = 'RIGHT';
				break;
			case self::FULL:
				$str = 'FULL';
				break;
			default:
				throw new InvalidArgumentException("Bad type of join for JoinClause");
		}
		$str .= ' JOIN ';
		if (empty($this->joinTable)) {
			throw new InvalidArgumentException("Table to JOIN was not passed");
		}
		$str .= "`".$this->joinTable."` ";
		$str .= "ON ";

		// validation
		if (count(array_filter($this->condition)) != 2 || 
			(is_array($this->condition[0]) && count(array_filter($this->condition[0])) != 2) ||
			(is_array($this->condition[1]) && count(array_filter($this->condition[1])) != 2)) {
			throw new InvalidArgumentException("Condition for JOIN is invalid");
		}
		if (is_array($this->condition[0])) {
			$str .= "`".$this->condition[0][0]."`.`".$this->condition[0][1]."`";
		} else {
			$str .= "`".$this->condition[0]."`";
		}

		$str .= "=";

		if (is_array($this->condition[1])) {
			$str .= "`".$this->condition[1][0]."`.`".$this->condition[1][1]."`";
		} else {
			$str .= "`".$this->condition[1]."`";
		}

		return trim($str);
	}

	/**
	 * Get the paramaters to bind
	 * 
	 * @return array Array of paramters which should be bound
	 */
	public function getParamtersToBind() : array {
		return [];
	}
}
