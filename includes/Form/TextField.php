<?php

namespace Catalyst\Form;

use \LogicException;

/**
 * Represents a text field
 */
class TextField extends Field {
	/**
	 * Pattern to match user input against
	 * 
	 * @var string
	 */
	protected $patten = '';
	/**
	 * Maximum string length, <= 0 means none
	 * 
	 * @var int
	 */
	protected $maxLength = 0;

	/**
	 * Get the current regex to match
	 * 
	 * @return string Current pattern
	 */
	public function getPatten() : string {
		return $this->patten;
	}

	/**
	 * Set the regex needed to match
	 * 
	 * @param string $patten New pattern
	 */
	public function setPatten(string $patten) : void {
		$this->patten = $patten;
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
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';
		$str .= '<div class="input-field col s12">';

		$inputClasses = [];
		$str .= '<input';
		$str .= ' type="text"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';

		if ($this->isRequired()) {
			$str .= ' required="required"';
		}

		if ($this->isPrimary()) {
			$str .= ' autofocus="autofocus"';
			$inputClasses[] = "active";
		}
		
		$inputAttributes[] = "validate";
		if ($this->getPatten() !== '') {
			$str .= ' pattern="'.htmlspecialchars($this->getPatten()).'"';
			$str .= ' title="Please follow the requested format"'; // required to not be ugly on some browsers
		}
		
		if ($this->getMaxLength() > 0) {
			$str .= ' maxlength="'.$this->getMaxLength().'"';
		}

		$str .= ' class="'.htmlspecialchars(implode(" ", $inputClasses)).'"';
		$str .= '>';
		
		$str .= '<label';
		$str .= ' for="'.htmlspecialchars($this->getId()).'"';
		$str .= '>';
		$str .= htmlspecialchars($this->getLabel());
		if ($this->isRequired()) {
			$str .= '<span class="red-text">';
			$str .= '&nbsp;*';
			$str .= '</span>';
		}
		$str .= '</label>';
		$str .= '</div>';
		return $str;
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string { /* TODO */ }

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string { /* TODO */ }

	/**
	 * Check the field's forms on the servers side
	 * 
	 * No parameters as the fields have concrete names, and no return as appropriate errors are returned
	 */
	public function checkServerSide() : void { /* TODO */ }
}
