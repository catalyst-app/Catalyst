<?php

namespace Catalyst\Form\Field;

use \Catalyst\Color;
use \Catalyst\Form\Form;
use \Catalyst\Page\Values;

/**
 * Represents a field to pick a color from the allowed values
 */
class ColorField extends AbstractField {
	use LabelTrait, SupportsPrefilledValueTrait;
	/**
	 * @return string The name of the web component tag
	 */
	public static function getWebComponentName() : string {
		return "color-field";
	}

	/**
	 * Get the DESCRIPTIVE error message types and default messages
	 * @return string[]
	 */
	protected function getDefaultErrorMessages() : array {
		return [
			"invalidColor" => "We aren't quite sure how, but this color is not valid.  Please try selecting again",
		] + parent::getDefaultErrorMessages();
	}

	/**
	 * @return array Properties for the created field element
	 */
	public function getProperties() : array {
		return [
			"formDistinguisher" => $this->getForm()->getDistinguisher(),
			"distinguisher" => $this->getDistinguisher(),
			"colorMap" => Color::COLOR_BY_HEX,
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
		return 'if (!document.getElementById('.json_encode($this->getId()).').parentNode.parentNode.parentNode.parentNode.parentNode.verify()) { return; }';
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', document.getElementById('.json_encode($this->getId()).').parentNode.parentNode.parentNode.parentNode.parentNode.getAggregationValue());';
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
		} else {
			if (empty($requestArr[$this->getDistinguisher()])) {
				return; // not required and empty, don't do further checks
			}
		}
		if (!in_array($requestArr[$this->getDistinguisher()], array_keys(Color::HEX_MAP))) {
			$this->throwError("invalidColor");
		}
	}
}
