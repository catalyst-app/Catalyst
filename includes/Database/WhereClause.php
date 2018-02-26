<?php

namespace Catalyst\Database;

use \LogicException;
use \InvalidArgumentException;

/**
 * Class which represents a WHERE clause in a MySQL query
 */
class WhereClause implements QueryAdditionInterface {
	/**
	 * Represents a boolean AND operation
	 */
	public const AND = 0;
	/**
	 * Represents a boolean OR operation
	 */
	public const OR = 1;

	/**
	 * Array which contains AND/OR operands delimiting [column, equality, value]
	 */
	protected $clause = [];

	/**
	 * Constructs a WhereClause from the array of clause items
	 * 
	 * @param array $clause Items for the clause.  [column, equality, value] delimited by WhereClause::AND or WhereClause::OR
	 */
	public function __construct(array $clause=[]) {
		$this->setClause($clause);
	}

	/**
	 * Gets the current WHERE clause array
	 * 
	 * @return array Items for the clause.  [column, equality, value] delimited by WhereClause::AND or WhereClause::OR
	 */
	public function getClause() : array {
		return $this->clause;
	}

	/**
	 * Gets the current WHERE clause array
	 * 
	 * @param int|mixed[] $item Item to add to the clause, either [column, equality, value] or WhereClause::AND or WhereClause::OR
	 */
	public function addToClause($item) : void {
		$this->clause[] = $item;
	}

	/**
	 * Sets the current WHERE clause array
	 * 
	 * @param int[]|mixed[]|mixed[][] $items New items for the clause, either [column, equality, value] or WhereClause::AND or WhereClause::OR
	 */
	public function setClause(array $items) : void {
		$this->clause = $items;
	}

	/**
	 * Returns the properly-formated clause
	 * 
	 * @return string the WHERE clause
	 * @throws LogicException on bad values
	 * @throws InvalidArgumentException on other bad values
	 */
	public function getQueryString() : string {
		if (empty($this->clause)) {
			return '';
		}
		$str = 'WHERE ';
		foreach ($this->clause as $value) {
			if (!is_array($value)) {
				switch ($value) {
					case self::AND:
						$str .= ' AND ';
						break;
					case self::OR:
						$str .= ' OR ';
						break;
					default:
						throw new LogicException("Where clause item has no associated column and is not equal to WhereClause::AND or WhereClause::OR");
				}
			} else {
				if (count($value) != 3) {
					throw new InvalidArgumentException("Invalid where clause (".serialize($value).")");
				}
				if (!$value[0] instanceof Column) {
					throw new InvalidArgumentException("Column expected in WhereClause, found ".(gettype($value[0]) == "object" ? get_class($value[0]) : gettype($value[0])));
				}
				$str .= $value[0].' '.$value[1].' ';
				if (is_array($value[2])) {
					$str .= '('.implode(",",array_fill(0, count($value[2]), '?')).')';
				} else {
					$str .= '?';
				}
			}
		}
		return trim($str);
	}

	/**
	 * Get the paramaters to bind
	 * 
	 * @return array Array of paramters which should be bound
	 */
	public function getParamtersToBind() : array {
		$params = [];
		foreach ($this->clause as $item) {
			if (is_array($item)) {
				if (is_array($item[2])) {
					foreach ($item[2] as $i) {
						$params[] = $i;
					}
				} else {
					$params[] = $item[2];
				}
			}
		}
		return $params;
	}
}
