<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents an email field
 */
class EmailField extends AbstractField {
	use LabelTrait, SupportsAutocompleteAttributeTrait, SupportsPrefilledValueTrait;
	/**
	 * Pattern to match user input against
	 */
	const PATTERN = '^.{1,}@.{1,}\..{1,}$';
	/**
	 * Per IETF's RFC (pay attention to the errata), this is 254 chars
	 */
	const MAX_LENGTH = 254;

	/**
	 * @return string The name of the web component tag
	 */
	public static function getWebComponentName() : string {
		return "email-field";
	}

	/**
	 * @return array Properties for the created field element
	 */
	public function getProperties() : array {
		return [
			"formDistinguisher" => $this->getForm()->getDistinguisher(),
			"distinguisher" => $this->getDistinguisher(),
			"autocomplete" => $this->getAutocompleteAttribute(),
			"pattern" => self::PATTERN,
			"maxlength" => self::MAX_LENGTH,
			"value" => $this->getPrefilledValue(),
			"required" => $this->isRequired(),
			"primary" => $this->isPrimary(),
		] + $this->getLabelProperties();
	}

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		return $this->getWebComponentHtml();
	}

	/**
	 * Full JS validation code
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		return 'if (!document.getElementById('.json_encode($this->getId()).').verify()) { return; }';
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', document.getElementById('.json_encode($this->getId()).').getAggregationValue());';
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * @param array $requestArr Array to find the form data in
	 */
	public function checkServerSide(?array &$requestArr=null) : void {
		if (is_null($requestArr)) {
			if ($this->getForm()->getMethod() == Form::POST) {
				$requestArr = &$_POST;
			} else {
				$requestArr = &$_GET;
			}
		}
		if (!array_key_exists($this->getDistinguisher(), $requestArr)) {
			$this->throwMissingError();
		}
		if (empty($requestArr[$this->getDistinguisher()])) {
			if ($this->isRequired()) {
				$this->throwMissingError();
			} else {
				return; // not required and empty, don't do further checks
			}
		}
		if (self::MAX_LENGTH > 0) {
			if (strlen($requestArr[$this->getDistinguisher()]) > self::MAX_LENGTH) {
				$this->throwInvalidError();
			}
		}
		if (!preg_match('/'.str_replace("/", "\\/", self::PATTERN).'/', $requestArr[$this->getDistinguisher()])) {
			$this->throwInvalidError();
		}
	}

	/**
	 * Get the default autocomplete attribute value
	 *
	 * @return string
	 */
	public static function getDefaultAutocompleteAttribute() : string {
		return AutocompleteValues::EMAIL;
	}
}
