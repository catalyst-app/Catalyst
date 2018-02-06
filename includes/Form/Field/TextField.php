<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents a text field
 */
class TextField extends AbstractField {
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
		if ($this->getPattern() !== '') {
			$str .= ' pattern="'.htmlspecialchars($this->getPattern()).'"';
			$str .= ' title="Please follow the requested format"'; // required to not be ugly on some browsers
		}
		
		if ($this->getMaxLength() > 0) {
			$str .= ' maxlength="'.$this->getMaxLength().'"';
		}

		$str .= ' class="'.htmlspecialchars(implode(" ", $inputClasses)).'"';
		$str .= '>';
		
		$str .= '<label';
		$str .= ' data-error="'.htmlspecialchars($this->getErrorMessage($this->getInvalidErrorCode())).'"';
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
	public function getJsValidator() : string {
		$str = '';
		if ($this->isRequired()) {
			$str .= 'if (';
			$str .= '$('.json_encode("#".$this->getId()).').val().length === 0';
			$str .= ') {';
			$str .= 'markInputInvalid('.json_encode('#'.$this->getId()).', '.json_encode($this->getErrorMessage($this->getMissingErrorCode())).');';
			$str .= Form::CANCEL_SUBMISSION_JS;
			$str .= '}';
		}
		if ($this->getMaxLength() > 0) {
			$str .= 'if (';
			$str .= '$('.json_encode("#".$this->getId()).').val().length > '.json_encode($this->getMaxLength());
			$str .= ') {';
			$str .= 'markInputInvalid('.json_encode('#'.$this->getId()).', '.json_encode($this->getErrorMessage($this->getMissingErrorCode())).');';
			$str .= Form::CANCEL_SUBMISSION_JS;
			$str .= '}';
		}
		$str .= 'if (';
		$str .= '!(new RegExp('.json_encode($this->getPattern()).').test($('.json_encode("#".$this->getId()).').val()))';
		$str .= ') {';
		$str .= 'markInputInvalid('.json_encode('#'.$this->getId()).', '.json_encode($this->getErrorMessage($this->getInvalidErrorCode())).');';
		$str .= Form::CANCEL_SUBMISSION_JS;
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
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', $('.json_encode("#".$this->getId()).').val());';
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * No parameters as the fields have concrete names, and no return as appropriate errors are returned
	 */
	public function checkServerSide() : void {
		if (!is_null($this->getAdditionalCheck())) {
			$this->getAdditionalCheck()($this);
		}
		if ($this->isRequired()) {
			if (!isset($_REQUEST[$this->getDistinguisher()]) || empty($_REQUEST[$this->getDistinguisher()])) {
				$this->throwMissingError();
			}
		} else {
			if (!isset($_REQUEST[$this->getDistinguisher()]) || empty($_REQUEST[$this->getDistinguisher()])) {
				return; // not required and empty, don't do further checks
			}
		}
		if ($this->getMaxLength() > 0) {
			if (strlen($_REQUEST[$this->getDistinguisher()]) > $this->getMaxLength()) {
				$this->throwInvalidError();
			}
		}
		if (!preg_match('/'.str_replace("/", "\\/", $this->getPattern()).'/', $_POST[$this->getDistinguisher()])) {
			$this->throwInvalidError();
		}
	}
}
