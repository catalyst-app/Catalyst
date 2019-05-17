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
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';
		$str .= ' type="text"';
		$str .= ' data-field-type="'.htmlspecialchars(self::class).'"';
		$str .= ' autocomplete="'.htmlspecialchars($this->getAutocompleteAttribute()).'"';
		$str .= ' pattern="'.htmlspecialchars($this->getPattern()).'"';
		$str .= ' data-disallowed="'.htmlspecialchars(json_encode($this->getDisallowed())).'"';
		if ($this->getMaxLength()) {
			$str .= ' maxlength="'.$this->getMaxLength().'"';
		}

		if ($this->isFieldPrefilled()) {
			if (!preg_match('/'.str_replace("/", "\\/", $this->getPattern()).'/', $this->getPrefilledValue()) || ($this->getMaxLength() && strlen($this->getPrefilledValue()) > $this->getMaxLength())) {
				$this->throwInvalidPrefilledValueError();
			}
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
		$str = '';

		$str .= 'if (!(new window.formInputHandlers['.json_encode(self::class).'](document.getElementById('.json_encode($this->getId()).')).verify())) { return; };';

		return $str;
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
		if ($this->isRequired()) {
			if (empty($requestArr[$this->getDistinguisher()])) {
				$this->throwMissingError();
			}
		} else {
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
