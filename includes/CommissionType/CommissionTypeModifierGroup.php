<?php

namespace Catalyst\CommissionType;

/**
 * Represents a group of multiple commission type modifiers
 *
 * Basic model class, nothing fancy
 */
class CommissionTypeModifierGroup {
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

	public function __construct(int $id, array $modifiers=[], bool $allowingMultiple=false) {
		$this->setId($id);
		foreach ($modifiers as $modifier) {
			$this->addModifier($modifier);
		}
		$this->setAllowingMultiple($allowingMultiple);
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
}
