<?php

namespace Catalyst\Database;

use \InvalidArgumentException;

/**
 * A raw column value
 * Extends Column only for type reasons
 * 
 * to me: know what the fuck you're doing before using this
 * Like actually
 */
class RawColumn extends Column {
	/**
	 * raw column to insert
	 * 
	 * @var string|null
	 */
	protected $val = null;

	/**
	 * Creates a RawColumn object
	 * 
	 * @param string|null $val Value to use
	 */
	public function __construct(?string $val) {
		$this->setVal($val);
		parent::__construct('','');
	}

	/**
	 * Returns the column's val, or null if ambiguous
	 * 
	 * @return string|null The currently targeted val, or null if unspecified
	 */
	public function getVal() : ?string {
		return $this->val;
	}

	/**
	 * Set the column val to a new value
	 * 
	 * @param string|null $val The new val for the column
	 */
	public function setVal(?string $val) : void {
		$this->val = $val;
	}

	/**
	 * @return string The value to use for the column
	 * @throws InvalidArgumentException if col hasn't been defined
	 */
	public function __toString() : string {
		if (is_null($this->getVal())) {
			throw new InvalidArgumentException("Column value not filled in");
		}
		return $this->getVal();
	}
}
