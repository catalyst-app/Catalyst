<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents a text field
 */
class TextField extends AbstractField {
	use LabelTrait, SupportsAutocompleteAttributeTrait, SupportsPrefilledValueTrait;
	/**
	 * Pattern to match user input against
	 * 
	 * @var string
	 */
	protected $pattern = '';
	/**
	 * Maximum string length, <= 0 means none
	 * 
	 * @var int
	 */
	protected $maxLength = 0;
	/**
	 * Disallowed values
	 * 
	 * @var string[]
	 */
	protected $disallowed = [];

	/**
	 * @return string The name of the web component tag
	 */
	public static function getWebComponentName() : string {
		return "text-field";
	}

	/**
	 * Get the current regex to match
	 * 
	 * @return string Current pattern
	 */
	public function getPattern() : string {
		return $this->pattern;
	}

	/**
	 * Set the regex needed to match
	 * 
	 * @param string $pattern New pattern
	 */
	public function setPattern(string $pattern) : void {
		$this->pattern = $pattern;
	}

	/**
	 * Get the input's maximum length
	 * 
	 * @return int Current maxmimum length
	 */
	public function getMaxLength() : int {
		return $this->maxLength;
	}

	/**
	 * Set the maximum length to a new value
	 * 
	 * <= 0 means that there is none
	 * @param int $maxLength New maxlength
	 */
	public function setMaxLength(int $maxLength) : void {
		$this->maxLength = $maxLength;
	}

	/**
	 * @return string[]
	 */
	public function getDisallowed() : array {
		return $this->disallowed;
	}

	/**
	 * @param string[] $disallowed
	 */
	public function setDisallowed(array $disallowed) : void {
		$this->disallowed = $disallowed;
	}

	/**
	 * @return array Properties for the created field element
	 */
	public function getProperties() : array {
		return [
			"formDistinguisher" => $this->getForm()->getDistinguisher(),
			"distinguisher" => $this->getDistinguisher(),
			"autocomplete" => $this->getAutocompleteAttribute(),
			"pattern" => $this->getPattern(),
			"disallowed" => $this->getDisallowed(),
			"maxlength" => $this->getMaxLength(),
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
		$str  = '';

		$str .= '<'.self::getWebComponentName();
		$str .= ' data-properties="'.htmlspecialchars(json_encode($this->getProperties())).'">';

		$str .= '</'.self::getWebComponentName().'>';

		return $str;
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
				return;
			}
		}
		if ($this->getMaxLength() > 0) {
			if (strlen($requestArr[$this->getDistinguisher()]) > $this->getMaxLength()) {
				$this->throwInvalidError();
			}
		}
		if (!preg_match('/'.str_replace("/", "\\/", $this->getPattern()).'/', $requestArr[$this->getDistinguisher()])) {
			$this->throwInvalidError();
		}
		if (in_array($requestArr[$this->getDistinguisher()], $this->getDisallowed())) {
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
