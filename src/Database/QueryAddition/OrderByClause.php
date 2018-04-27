<?php

namespace Catalyst\Database\QueryAddition;

use \Catalyst\Database\Column;
use \InvalidArgumentException;

/**
 * Class which represents a ORDER BY clause in a MySQL query
 */
class OrderByClause implements QueryAdditionInterface {
	/**
	 * Column to order by
	 * @var null|Column
	 */
	protected $column=null;
	/**
	 * Order to sort
	 * @var string
	 */
	protected $order="ASC";

	/**
	 * Constructs a OrderByClause
	 * 
	 * @param Column|null $column Initial value for column
	 * @param string $order Order to order the result from, either ASC or DESC
	 */
	public function __construct(?Column $column=null, string $order="ASC") {
		$this->setColumn($column);
		$this->setOrder($order);
	}

	/**
	 * Returns the properly-formated clause
	 * 
	 * @return string the ORDER BY clause
	 */
	public function getQueryString() : string {
		$str = 'ORDER BY ';
		$str .= $this->getColumn();
		$str .= ' ';
		$str .= $this->getOrder();
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

	/**
	 * Get the clause's column
	 * 
	 * @return null|Column
	 */
	public function getColumn() : ?Column {
		return $this->column;
	}

	/**
	 * Set the clause's column
	 * 
	 * @param null|Column $column
	 */
	public function setColumn(?Column $column=null) : void {
		$this->column = $column;
	}

	/**
	 * Get the clause's order
	 * 
	 * @return string (one of ASC or DESC)
	 */
	public function getOrder() : string {
		return $this->order;
	}

	/**
	 * Set the clause's order
	 * 
	 * @param string $order
	 */
	public function setOrder(string $order) : void {
		if ($order != "ASC" && $order != "DESC") {
			throw new InvalidArgumentException($order." is not a valid order for a MySQL ORDER clause");
		}
		$this->order = $order;
	}
}
