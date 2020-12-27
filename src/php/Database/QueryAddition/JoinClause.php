<?php

namespace Catalyst\Database\QueryAddition;

use \Catalyst\Database\Column;
use \LogicException;
use \InvalidArgumentException;

/**
 * Class which represents a JOIN clause in a MySQL SELECT query
 */
class JoinClause implements QueryAdditionInterface {
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
	 * 
	 * @var int
	 */
	protected $type = self::INNER;
	/**
	 * Table which will be JOINed to the query
	 * 
	 * @var string
	 */
	protected $joinTable = "";
	/**
	 * Condtion on which to join the tables
	 * 
	 * This is an array with 2 values, each columns
	 * 
	 * @var Column[]|null[]
	 */
	protected $condition = [null,null];

	/**
	 * Constructs a JoinClause from the array of clause items
	 * 
	 * @param int $type Type of join clause, see class constants for info
	 * @param string $joinTable Table to join
	 * @param Column|null $leftColumn Left column to test for equality to right
	 * @param Column|null $rightColumn Right column to test for equality to left
	 */
	public function __construct(int $type=self::INNER,string $joinTable="",?Column $leftColumn=null,?Column $rightColumn=null) {
		if ($type < self::INNER || $type > self::FULL) {
			throw new InvalidArgumentException("Invalid value passed for JoinClause type");
		}
		$this->type = $type;
		$this->joinTable = $joinTable;
		$this->condition = [$leftColumn,$rightColumn];
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
	 * Get the current left column for comparison
	 * 
	 * @return Column|null The left column
	 */
	public function getLeftColumn() : ?Column {
		return $this->condition[0];
	}

	/**
	 * Get the current right column for comparison
	 * 
	 * @return Column|null The right column
	 */
	public function getRightColumn() : ?Column {
		return $this->condition[1];
	}

	/**
	 * Set the left column
	 * 
	 * @param Column|null $column New value for the left column
	 */
	public function setLeftColumn(?Column $column) : void {
		$this->condition[0] = $column;
	}

	/**
	 * Set the right column
	 * 
	 * @param Column|null $column New value for the right column
	 */
	public function setRightColumn(?Column $column) : void {
		$this->condition[1] = $column;
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

		if (count(array_filter($this->condition)) !== 2) {
			throw new InvalidArgumentException("Bad condition for JOIN clause");
		}

		$str .= '('.((string)$this->condition[0])."=".((string)$this->condition[1]).')';

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
