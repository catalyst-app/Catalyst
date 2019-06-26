<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents a checkbox field
 */
class CheckboxField extends AbstractField {
	use LabelTrait, SupportsPrefilledValueTrait;
	/**
	 * The size of the checkbox field, as specified in units for materialize's grid system
	 *
	 * @var string
	 */
	protected $size = "col s12";

	/**
	 * @return string The name of the web component tag
	 */
	public static function getWebComponentName() : string {
		return "checkbox-field";
	}

	/**
	 * Get the DESCRIPTIVE error message types and default messages
	 * @return string[]
	 */
	protected function getDefaultErrorMessages() : array {
		return [
			"requiredButMissing" => "You must check this box to continue",
		] + parent::getDefaultErrorMessages();
	}

	/**
	 * @return string Current size
	 */
	public function getSize() : string {
		return $this->size;
	}

	/**
	 * @param string $size New size
	 */
	public function setSize(string $size) : void {
		$this->size = $size;
	}

	/**
	 * @return array Properties for the created field element
	 */
	public function getProperties() : array {
		return [
			"formDistinguisher" => $this->getForm()->getDistinguisher(),
			"distinguisher" => $this->getDistinguisher(),
			"size" => $this->getSize(),
			"required" => $this->isRequired(),
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
		return 'if (!document.getElementById('.json_encode($this->getId()).').parentNode.parentNode.parentNode.verify()) { return; }';
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', document.getElementById('.json_encode($this->getId()).').parentNode.parentNode.parentNode.getAggregationValue());';
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
		if ($this->isRequired()) {
			if ($requestArr[$this->getDistinguisher()] !== "true") {
				$this->throwMissingError();
			}
		}
	}
}
