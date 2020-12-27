<?php

namespace Catalyst\Form\Field;

use \InvalidArgumentException;

/**
 * Used by fields which allow pre-filled values
 */
trait SupportsPrefilledValueTrait {
	/**
	 * Preset field value
	 * 
	 * @var mixed
	 */
	protected $prefilledValue = null;
	/**
	 * If the default value has been set
	 * 
	 * @var bool
	 */
	protected $fieldIsPrefilled = false;

	/**
	 * Get the prefilled value
	 * 
	 * @return mixed the prefilled value, null if not set (or maybe set to null?, ambiguous)
	 */
	public function getPrefilledValue() {
		return $this->prefilledValue;
	}

	/**
	 * Set the prefilled value
	 * 
	 * @param mixed $prefilledValue The new prefilled value
	 */
	public function setPrefilledValue($prefilledValue) : void {
		$this->prefilledValue = $prefilledValue;
		$this->fieldIsPrefilled = true;
	}

	/**
	 * Get if the field has a prefilled value
	 * 
	 * @return bool if the field has been prefilled
	 */
	protected function isFieldPrefilled() : bool {
		return $this->fieldIsPrefilled;
	}

	/**
	 * Return properties to be sent to the client webcomponent
	 * 
	 * @return array
	 */
	protected function getPrefilledValueProperties() : array {
		return [
			"value" => $this->getPrefilledValue(),
			"valueIsPrefilled" => $this->isFieldPrefilled(),
		];
	}

	/**
	 * Throws an error regarding an invalid prefilled value
	 * 
	 * @throws InvalidArgumentException
	 */
	protected function throwInvalidPrefilledValueError() : void {
		throw new InvalidArgumentException("Invalid default ".__CLASS__." value (".serialize($this->getPrefilledValue()).")");
	}
}
