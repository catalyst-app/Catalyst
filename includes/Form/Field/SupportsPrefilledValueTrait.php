<?php

namespace Catalyst\Form\Field;

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
	public function isFieldPrefilled() : bool {
		return $this->fieldIsPrefilled;
	}
}
