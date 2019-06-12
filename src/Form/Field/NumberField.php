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

		$inputClasses = [];
		$str .= '<input';
		$str .= ' type="number"';
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
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		$str = '';
		if ($this->isRequired()) {
			$str .= 'if (';
			$str .= '$('.json_encode("#".$this->getId()).').val().length === 0';
			$str .= ') {';
			$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - field is required, but empty").', true);';
			$str .= 'markInputInvalid('.json_encode('#'.$this->getId()).', '.json_encode($this->getErrorMessage($this->getMissingErrorCode())).');';
			$str .= Form::CANCEL_SUBMISSION_JS;
			$str .= '}';
		}

		$str .= 'if (';
		$str .= '$('.json_encode("#".$this->getId()).').val().length !== 0';
		$str .= ') {';

		$str .= 'if (';
		$str .= '$('.json_encode("#".$this->getId()).').val() < '.json_encode($this->getMin());
		$str .= ') {';
		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - field is smaller than minumum (".$this->getMin().")").', true);';
		$str .= 'markInputInvalid('.json_encode('#'.$this->getId()).', '.json_encode($this->getErrorMessage($this->getInvalidErrorCode())).');';
		$str .= Form::CANCEL_SUBMISSION_JS;
		$str .= '}';

		$str .= 'if (';
		$str .= '$('.json_encode("#".$this->getId()).').val() > '.json_encode($this->getMax());
		$str .= ') {';
		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - field is larger than maximum (".$this->getMax().")").', true);';
		$str .= 'markInputInvalid('.json_encode('#'.$this->getId()).', '.json_encode($this->getErrorMessage($this->getInvalidErrorCode())).');';
		$str .= Form::CANCEL_SUBMISSION_JS;
		$str .= '}';

		$str .= '}';

		return $str;
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', Math.round(parseFloat($('.json_encode("#".$this->getId()).').val()) * Math.pow(10, '.$this->getPrecision().')) / Math.pow(10, '.$this->getPrecision().'));';
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
			if (strtoupper($requestArr[$this->getDistinguisher()]) === "NAN" || $requestArr[$this->getDistinguisher()] === "") {
				$this->throwMissingError();
			}
		} else {
			if (strtoupper($requestArr[$this->getDistinguisher()]) === "NAN" || $requestArr[$this->getDistinguisher()] === "") {
				return; // not required and empty, don't do further checks
			}
		}
		if (!preg_match('/^[0-9]+(\.[0-9][0-9]?)?$/', $requestArr[$this->getDistinguisher()])) {
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
