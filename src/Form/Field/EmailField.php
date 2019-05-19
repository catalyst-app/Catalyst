<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents an email field
 */
class EmailField extends AbstractField {
	use LabelTrait, SupportsAutocompleteAttributeTrait, SupportsPrefilledValueTrait;
	/**
	 * Pattern to match user input against
	 */
	const PATTERN = '^.{1,}@.{1,}\..{1,}$';
	/**
	 * Per IETF's RFC (pay attention to the errata), this is 254 chars
	 */
	const MAX_LENGTH = 254;

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';
		$str .= '<div class="input-field col s12">';

		$inputClasses = ["form-field"];
		$str .= '<input';
		$str .= ' data-field-type="'.htmlspecialchars(self::class).'"';
		$str .= ' type="email"';
		$str .= ' autocomplete="'.htmlspecialchars($this->getAutocompleteAttribute()).'"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';

		// these attributes aren't actually interpreted, they only exist to help client software/browsers understand limitations
		$str .= ' pattern="'.htmlspecialchars(self::PATTERN).'"';
		$str .= ' maxlength="'.htmlspecialchars(self::MAX_LENGTH).'"';

		if ($this->isFieldPrefilled()) {
			if (!preg_match('/'.str_replace("/", "\\/", self::PATTERN).'/', $this->getPrefilledValue()) || (strlen($this->getPrefilledValue()) > self::MAX_LENGTH)) {
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
		if (empty($requestArr[$this->getDistinguisher()])) {
			if ($this->isRequired()) {
				$this->throwMissingError();
			} else {
				return; // not required and empty, don't do further checks
			}
		}
		if (self::MAX_LENGTH > 0) {
			if (strlen($requestArr[$this->getDistinguisher()]) > self::MAX_LENGTH) {
				$this->throwInvalidError();
			}
		}
		if (!preg_match('/'.str_replace("/", "\\/", self::PATTERN).'/', $requestArr[$this->getDistinguisher()])) {
			$this->throwInvalidError();
		}
	}

	/**
	 * Get the default autocomplete attribute value
	 *
	 * @return string
	 */
	public static function getDefaultAutocompleteAttribute() : string {
		return AutocompleteValues::EMAIL;
	}
}
