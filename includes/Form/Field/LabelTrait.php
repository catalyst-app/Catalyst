<?php

namespace Catalyst\Form\Field;

/**
 * Used by fields which contains labels
 */
trait LabelTrait {
	/**
	 * self explanatory
	 * 
	 * @var string
	 */
	protected $label = "";

	/**
	 * Get the current label
	 * 
	 * @return string the label
	 */
	public function getLabel() : string {
		return $this->label;
	}

	/**
	 * Set the label
	 * 
	 * @param string $label The new label
	 */
	public function setLabel(string $label) : void {
		$this->label = $label;
	}
}
