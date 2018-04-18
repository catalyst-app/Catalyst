<?php

namespace Catalyst\CommissionType;

/**
 * Represents a commission type payment option
 *
 * Basic model class, nothing fancy
 */
class CommissionTypePaymentOption {
	/**
	 * @var int
	 */
	protected $id = 0;
	/**
	 * @var string
	 */
	protected $type = "";
	/**
	 * @var string
	 */
	protected $address = "";
	/**
	 * @var string
	 */
	protected $instructions = "";

	/**
	 * Constructor
	 *
	 * @param int $id
	 * @param string $type
	 * @param string $address
	 * @param string $instructions
	 */
	public function __construct(int $id, string $type="", string $address="", string $instructions="") {
		$this->id = $id;
		$this->setType($type);
		$this->setAddress($address);
		$this->setInstructions($instructions);
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
	public function getType() : string {
		return $this->name;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type) : void {
		$this->name = $type;
	}

	/**
	 * @return string
	 */
	public function getAddress() : string {
		return $this->price;
	}

	/**
	 * @param string $address
	 */
	public function setAddress(string $address) : void {
		$this->price = $address;
	}

	/**
	 * @return string
	 */
	public function getInstructions() : string {
		return $this->instructions;
	}

	/**
	 * @param string $instructions
	 */
	public function setInstructions(string $instructions) : void {
		$this->instructions = $instructions;
	}
}
