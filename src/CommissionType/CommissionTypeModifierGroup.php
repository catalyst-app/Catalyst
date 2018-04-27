<?php

namespace Catalyst\CommissionType;

use \Iterator;

/**
 * Represents a group of multiple commission type modifiers
 *
 * Basic model class, nothing fancy
 */
class CommissionTypeModifierGroup implements Iterator {
	/**
	 * @var int
	 */
	protected $id = 0;
	/**
	 * @var CommissionTypeModifier[]
	 */
	protected $modifiers = [];
	/**
	 * @var bool
	 */
	protected $allowingMultiple = false;

	/**
	 * For Iterable implementation
	 * 
	 * @var int
	 */
	private $position = 0;

	/**
	 * Basic constructor
	 *
	 * @param int $id
	 * @param bool $allowingMultiple
	 * @param CommissionTypeModifier[] $modifiers
	 */
	public function __construct(int $id, bool $allowingMultiple=false, array $modifiers=[]) {
		$this->setId($id);
		foreach ($modifiers as $modifier) {
			$this->addModifier($modifier);
		}
		$this->setAllowingMultiple($allowingMultiple);

		// iterator
		$this->position = 0;
	}

	/**
	 * @return int
	 */
	public function getId() : int {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id) : void {
		$this->id = $id;
	}

	/**
	 * @return CommissionTypeModifier[]
	 */
	public function getModifiers() : array {
		return $this->modifiers;
	}

	/**
	 * @param CommissionTypeModifier $modifier
	 */
	public function addModifier(CommissionTypeModifier $modifier) : void {
		$this->modifiers[] = $modifier;
	}

	/**
	 * @return bool
	 */
	public function isAllowingMultiple() : bool {
		return $this->allowingMultiple;
	}

	/**
	 * @param bool $allowingMultiple
	 */
	public function setAllowingMultiple(bool $allowingMultiple) : void {
		$this->allowingMultiple = $allowingMultiple;
	}

	/* ITERATOR IMPLEMENTATION */

	/**
	 * @see Iterator
	 */
	public function rewind() : void {
		$this->position = 0;
	}

	/**
	 * @see Iterator
	 * @return CommissionTypeModifier
	 */
	public function current() : CommissionTypeModifier {
		return $this->modifiers[$this->position];
	}

	/**
	 * @see Iterator
	 */
	public function next() : void {
		$this->position++;
	}

	/**
	 * @see Iterator
	 * @return int
	 */
	public function key() : int {
		return $this->position;
	}

	/**
	 * @see Iterator
	 */
	public function valid() : bool {
		return array_key_exists($this->position, $this->position);
	}
}
