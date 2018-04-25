<?php

namespace Catalyst\Form\Field;

/**
 * Used by fields which support autocomplete attributes
 */
trait SupportsAutocompleteAttributeTrait {
	/**
	 * attribute value
	 * 
	 * @var string|null
	 */
	protected $autocompleteAttribute = null;

	/**
	 * Get the current autocomplete attribute
	 * 
	 * @return string the attribute
	 */
	public function getAutocompleteAttribute() : string {
		return is_null($this->autocompleteAttribute) ? self::getDefaultAutocompleteAttribute() : $this->autocompleteAttribute;
	}

	/**
	 * Set the autocomplete attribute
	 * 
	 * @param string $autocompleteAttribute The new value
	 */
	public function setAutocompleteAttribute(string $autocompleteAttribute) : void {
		$this->autocompleteAttribute = $autocompleteAttribute;
	}

	/**
	 * Get the default autocomplete attribute value
	 *
	 * @return string
	 * @abstract
	 */
	public static abstract function getDefaultAutocompleteAttribute() : string;
}
