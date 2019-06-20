<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;
use \Catalyst\API\TransitEncryption;

/**
 * Represents a text field
 */
class PasswordField extends AbstractField {
	use LabelTrait, SupportsAutocompleteAttributeTrait;
	/**
	 * Minimum length for the password
	 * @var int
	 */
	protected $minLength = 8;

	/**
	 * Get the password's minimum length
	 * 
	 * @return int Minimum password length
	 */
	public function getMinLength() : int {
		return $this->minLength;
	}

	/**
	 * Set the password's minimum length
	 * 
	 * @param int $minLength New minimum password length
	 */
	public function setMinLength(int $minLength) : void {
		$this->minLength = $minLength;
	}

	/**
	 * @return string the name of the web component tag
	 */
	public static function getWebComponentName() : string {
		return "password-field";
	}

	/**
	 * Get the DESCRIPTIVE error message types and default messages
	 * @return string[]
	 */
	protected function getDefaultErrorMessages() : array {
		return parent::getDefaultErrorMessages() + [
			"belowMinLength" => "Please use at least ".$this->getMinLength()." character".($this->getMinLength() != 1 ? "s" : ""),
			// not used by class, however, frequently used by implementing clients, therefore, it seems best to include it for the sake of a standard naming if nothing else
			"incorrectPassword" => "This password is incorrect.  Please contact support if you need to reset it",
		];
	}

	/**
	 * @return array Properties for the created field element
	 */
	public function getProperties() : array {
		return [
			"formDistinguisher" => $this->getForm()->getDistinguisher(),
			"distinguisher" => $this->getDistinguisher(),
			"autocomplete" => $this->getAutocompleteAttribute(),
			"minlength" => $this->getMinLength(),
			"required" => $this->isRequired(),
			"primary" => $this->isPrimary(),
			"errors" => $this->getErrorMessages(),
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
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		return 'if (!document.getElementById('.json_encode($this->getId()).').parentNode.parentNode.verify()) { return; }';
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
  		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', document.getElementById('.json_encode($this->getId()).').parentNode.parentNode.getAggregationValue());';
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
			$this->throwError("requiredButMissing");
		}
		$requestArr[$this->getDistinguisher()] = TransitEncryption::decryptAes($requestArr[$this->getDistinguisher()]);
		if (empty($requestArr[$this->getDistinguisher()])) {
			if ($this->isRequired()) {
				$this->throwError("requiredButMissing");
			} else {
				return;
			}
		}
		if (strlen($requestArr[$this->getDistinguisher()]) < $this->getMinLength()) {
			$this->throwError("belowMinLength");
		}
	}

	/**
	 * Get the default autocomplete attribute value
	 *
	 * @return string
	 */
	public static function getDefaultAutocompleteAttribute() : string {
		return AutocompleteValues::CURRENT_PASSWORD;
	}
}
