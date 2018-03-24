<?php

namespace Catalyst\Database\QueryAddition;

/**
 * Multiple ORDER BYs, made from OrderByClauses
 */
class MultipleOrderByClause implements QueryAdditionInterface {
	/**
	 * array of clauses to order by
	 * @var OrderByClause[]|array
	 */
	protected $clauses=[];

	/**
	 * Constructs a MultipleOrderByClause, noarg
	 */
	public function __construct() {}

	/**
	 * Get the clauses
	 * @return OrderByClause[]
	 */
	public function getClauses() : array {
		return $this->clauses;
	}

	/**
	 * Add a clauses
	 * @param OrderByClause $clause
	 */
	public function addClause(OrderByClause $clause) : void {
		$this->clauses[] = $clause;
	}

	/**
	 * Empty clauses list
	 */
	public function emptyClauses() : void {
		$this->clauses = [];
	}

	/**
	 * Returns the properly-formated clause
	 * 
	 * @return string the ORDER BY clause
	 */
	public function getQueryString() : string {
		$str = 'ORDER BY ';
		foreach ($this->getClauses() as $clause) {
			$str .= substr($clause->getQueryString(), 9).", ";
		}
		return rtrim(trim($str),",");
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
