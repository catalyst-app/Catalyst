<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Used for a field which takes a selector's val()
 */
class HiddenInputField extends AbstractField {
	/**
	 * The selector of the input to grab the `.val()` of
	 * 
	 * @var string
	 */
	protected $hiddenInputId = "";

	/**
	 * Get the ID of the hidden input to get the value from
	 */
	public function getHiddenInputId() : string {
		return $this->hiddenInputId;
	}

	/**
	 * Set the hidden input ID
	 * 
	 * @param string $hiddenInputId The new hidden input ID
	 */
	public function setHiddenInputId(string $hiddenInputId) : void {
		$this->hiddenInputId = $hiddenInputId;
	}

	/**
	 * @return string the name of the web component tag
	 */
	public static function getWebComponentName() : string {
		return "hidden-input-field";
	}

	/**
	 * Get the DESCRIPTIVE error message types and default messages
	 * @return string[]
	 */
	protected function getDefaultErrorMessages() : array {
		return [
			"requiredButMissing" => "An unknown error occured.  Please contact the site administrators.",
		] + parent::getDefaultErrorMessages();
	}

	/**
	 * @return array Properties for the created field element
	 */
	public function getProperties() : array {
		return [
			"formDistinguisher" => $this->getForm()->getDistinguisher(),
			"distinguisher" => $this->getDistinguisher(),
			"hiddenInputId" => $this->getHiddenInputId(),
			"errors" => $this->getErrorMessages(),
		];
	}

	/**
	 * Return the field's HTML input
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
			$this->throwError("requiredButMissing");
		}
		if ($this->isRequired()) {
			if (empty($requestArr[$this->getDistinguisher()])) {
				$this->throwError("requiredButMissing");
			}
		}
	}
}
