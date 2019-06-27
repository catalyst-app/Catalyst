<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents a numeric field
 */
class NumberField extends AbstractField {
	use LabelTrait, SupportsAutocompleteAttributeTrait, SupportsPrefilledValueTrait;
	/**
	 * Maximum number
	 * 
	 * @var int
	 */
	protected $min = 0;
	/**
	 * Maximum number
	 * 
	 * @var int
	 */
	protected $max = 2147483647; // no we cant use PHP_INT_MAX because not everyone's 64 bit >:/
	/**
	 * Maximum precision (in number of decimals)
	 * 
	 * @var int
	 */
	protected $precision = 0;

	/**
	 * @return string The name of the web component tag
	 */
	public static function getWebComponentName() : string {
		return "number-field";
	}

	/**
	 * Get the DESCRIPTIVE error message types and default messages
	 * @return string[]
	 */
	protected function getDefaultErrorMessages() : array {
		return [
			"exceedsMaximum" => "The maximum allowed value is ".$this->getMax(),
			"belowMinimum" => "The minimum allowed value is ".$this->getMin(),
			"notANumber" => "Please enter a valid number",
			"tooPrecise" => ($this->getPrecision() ? "Please use no more than ".$this->getPrecision()." decimal place".($this->getPrecision() > 1 ? "s" : "") : "Please enter a whole number"),
		] + parent::getDefaultErrorMessages();
	}

	/**
	 * Get the input's maximum
	 * 
	 * @return int Current maxmimum
	 */
	public function getMax() : int {
		return $this->max;
	}

	/**
	 * Set the maximum
	 * 
	 * @param int $max New max
	 */
	public function setMax(int $max) : void {
		$this->max = $max;
	}

	/**
	 * Get the input's minimum
	 * 
	 * @return int Current minmimum
	 */
	public function getMin() : int {
		return $this->min;
	}

	/**
	 * Set the minimum
	 * 
	 * @param int $min New min
	 */
	public function setMin(int $min) : void {
		$this->min = $min;
	}

	/**
	 * Get the input's precision
	 * 
	 * @return int Current precision
	 */
	public function getPrecision() : int {
		return $this->precision;
	}

	/**
	 * Set the precision
	 * 
	 * @param int $precision New precision
	 */
	public function setPrecision(int $precision) : void {
		$this->precision = $precision;
	}

	/**
	 * @return array Properties for the created field element
	 */
	public function getProperties() : array {
		return [
			"formDistinguisher" => $this->getForm()->getDistinguisher(),
			"distinguisher" => $this->getDistinguisher(),
			"autocomplete" => $this->getAutocompleteAttribute(),
			"min" => $this->getMin(),
			"max" => $this->getMax(),
			"precision" => $this->getPrecision(),
			"required" => $this->isRequired(),
			"primary" => $this->isPrimary(),
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
	 * Full JS validation code
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
		if ($requestArr[$this->getDistinguisher()] === "") {
			if ($this->isRequired()) {
				$this->throwError("requiredButMissing");
			} else {
				return;
			}
		}
		if (strtoupper($requestArr[$this->getDistinguisher()]) === "NAN") {
			$this->throwError("notANumber");
		}
		if (!preg_match('/^-?[0-9]+(\.[0-9]+)?$/', $requestArr[$this->getDistinguisher()])) {
			$this->throwError("notANumber");
		}
		$requestArr[$this->getDistinguisher()] = round((float)$requestArr[$this->getDistinguisher()], $this->getPrecision());
		if ($requestArr[$this->getDistinguisher()] > $this->getMax()) {
			$this->throwError("exceedsMaximum");
		}
		if ($requestArr[$this->getDistinguisher()] < $this->getMin()) {
			$this->throwError("belowMinimum");
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
