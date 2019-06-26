<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents a select (dropdown) field
 */
class SelectField extends AbstractField {
	use LabelTrait, SupportsAutocompleteAttributeTrait, SupportsPrefilledValueTrait;
	/**
	 * Options, [value => label]
	 * 
	 * @var string[]
	 */
	protected $options = [];

	/**
	 * Get the current options
	 * 
	 * @return string[] Current option array
	 */
	public function getOptions() : array {
		return $this->options;
	}

	/**
	 * Set the current option set
	 * 
	 * @param string[] $options New options
	 */
	public function setOptions(array $options) : void {
		$this->options = $options;
	}

	/**
	 * @return string The name of the web component tag
	 */
	public static function getWebComponentName() : string {
		return "select-field";
	}

	/**
	 * Get the DESCRIPTIVE error message types and default messages
	 * @return string[]
	 */
	protected function getDefaultErrorMessages() : array {
		return [
			"invalidResponse" => "This select box is having some internal issues, please contact support",
		] + parent::getDefaultErrorMessages();
	}

	/**
	 * @return array Properties for the created field element
	 */
	public function getProperties() : array {
		return [
			"formDistinguisher" => $this->getForm()->getDistinguisher(),
			"distinguisher" => $this->getDistinguisher(),
			"autocomplete" => $this->getAutocompleteAttribute(),
			"required" => $this->isRequired(),
			"options" => $this->getOptions(),
			"errors" => $this->getErrorMessages(),
		] + $this->getLabelProperties() + $this->getPrefilledValueProperties();
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
		if (empty($requestArr[$this->getDistinguisher()])) {
			if ($this->isRequired()) {
				$this->throwError("requiredButMissing");
			} else {
				return;
			}
		}
		if (!in_array($requestArr[$this->getDistinguisher()], array_keys($this->getOptions()))) {
			$this->throwError("invalidResponse");
		}
	}

	/**
	 * Get the default autocomplete attribute value
	 *
	 * @return string
	 */
	public static function getDefaultAutocompleteAttribute() : string {
		return AutocompleteValues::ON;
	}
}
