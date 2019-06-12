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
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';
		$str .= '<div';
		$str .= ' class="input-field col s12">';

		$inputClasses = ["form-field"];
		$str .= '<input';
		$str .= ' type="number"';
		$str .= ' data-field-type="'.htmlspecialchars(self::class).'"';
		$str .= ' step="'.(10**-$this->getPrecision()).'"';
		$str .= ' inputmode="numeric"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';

		if ($this->isFieldPrefilled()) {
			$str .= ' value="'.htmlspecialchars($this->getPrefilledValue()).'"';
			$inputClasses[] = "active";
		}

		if ($this->isRequired()) {
			$str .= ' required="required"';
		}

		if ($this->isPrimary()) {
			$str .= ' autofocus="autofocus"';
			$inputClasses[] = "active";
		}
		
		if ($this->getPrecision() <= 0) {
			$str .= ' pattern="[0-9]*"';
		} else {
			$str .= ' pattern="[0-9]+(\.[0-9][0-9]?)?"';
		}
		
		$str .= ' min="'.$this->getMin().'"';
		$str .= ' max="'.$this->getMax().'"';

		$str .= ' class="'.htmlspecialchars(implode(" ", $inputClasses)).'"';
		$str .= '>';
		
		$str .= $this->getLabelHtml();
		
		$str .= '</div>';
		return $str;
	}

	/**
	 * Full JS validation code
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		return 'if (!(new window.formInputHandlers['.json_encode(self::class).'](document.getElementById('.json_encode($this->getId()).')).verify())) { return; }';
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', (new window.formInputHandlers['.json_encode(self::class).'](document.getElementById('.json_encode($this->getId()).')).getValue()));';
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
		if (strtoupper($requestArr[$this->getDistinguisher()]) === "NAN" || $requestArr[$this->getDistinguisher()] === "") {
			if ($this->isRequired()) {
				$this->throwMissingError();
			} else {
				return;
			}
		}
		if (!preg_match('/^[0-9]+(\.[0-9]+)?$/', $requestArr[$this->getDistinguisher()])) {
			$this->throwInvalidError();
		}
		$requestArr[$this->getDistinguisher()] = round((float)$requestArr[$this->getDistinguisher()], $this->getPrecision());
		if ($requestArr[$this->getDistinguisher()] > $this->getMax() || $requestArr[$this->getDistinguisher()] < $this->getMin()) {
			$this->throwInvalidError();
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
