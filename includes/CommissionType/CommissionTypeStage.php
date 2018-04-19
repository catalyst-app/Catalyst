<?php

namespace Catalyst\CommissionType;

/**
 * Represents a commission type stage
 *
 * Basic model class, nothing fancy
 */
class CommissionTypeStage {
	/**
	 * @var int
	 */
	protected $id = 0;
	/**
	 * @var string
	 */
	protected $stage = "";

	/**
	 * Constructor
	 *
	 * @param int $id
	 * @param string $stage
	 */
	public function __construct(int $id, string $stage="") {
		$this->id = $id;
		$this->setStage($stage);
	}


	/**
	 * @return int
	 */
	public function getId() : int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getStage() : string {
		return $this->stage;
	}

	/**
	 * @param string $stage
	 */
	public function setStage(string $stage) : void {
		$this->stage = $stage;
	}
}
