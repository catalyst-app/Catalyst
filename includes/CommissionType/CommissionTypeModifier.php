<?php

namespace Catalyst\CommissionType;

/**
 * Represents a commission type modifier
 *
 * Basic model class, nothing fancy
 */
class CommissionTypeModifier {
	/**
	 * @var int
	 */
	protected $id = 0;
	/**
	 * @var string
	 */
	protected $name = "";
	/**
	 * @var string
	 */
	protected $price = "";
	/**
	 * @var float
	 */
	protected $usdEquivalent = 0;

	/**
	 * Constructor
	 *
	 * @param int $id
	 * @param string $name
	 * @param string $price
	 * @param float $usdEquivalent
	 */
	public function __construct(int $id, string $name="", string $price="", float $usdEquivalent=0) {
		$this->id = $id;
		$this->setName($name);
		$this->setPrice($price);
		$this->setUsdEquivalent($usdEquivalent);
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
	public function getName() : string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name) : void {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getPrice() : string {
		return $this->price;
	}

	/**
	 * @param string $price
	 */
	public function setPrice(string $price) : void {
		$this->price = $price;
	}

	/**
	 * @return float
	 */
	public function getUsdEquivalent() : float {
		return $this->usdEquivalent;
	}

	/**
	 * @param float $usdEquivalent
	 */
	public function setUsdEquivalent(float $usdEquivalent) : void {
		$this->usdEquivalent = $usdEquivalent;
	}
}
