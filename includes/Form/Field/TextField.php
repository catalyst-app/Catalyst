<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents a text field
 */
class TextField extends AbstractField {
	use LabelTrait, SupportsPrefilledValueTrait;
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

		$inputClasses = [];
		$str .= '<input';
		$str .= ' type="text"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';

		if ($this->isFieldPrefilled()) {
			if (!preg_match('/'.str_replace("/", "\\/", $this->getPattern()).'/', $this->getPrefilledValue()) || strlen($this->getPrefilledValue()) > $this->getMaxLength()) {
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
		
		if ($this->getPattern() !== '') {
			$str .= ' pattern="'.htmlspecialchars($this->getPattern()).'"';
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
			$str .= '<span';
			$str .= ' class="red-text">';
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

		$str .= 'if (';
		$str .= '$('.json_encode("#".$this->getId()).').val().length !== 0';
		$str .= ') {';

		if ($this->getMaxLength() > 0) {
			$str .= 'if (';
			$str .= '$('.json_encode("#".$this->getId()).').val().length > '.json_encode($this->getMaxLength());
			$str .= ') {';
			$str .= 'markInputInvalid('.json_encode('#'.$this->getId()).', '.json_encode($this->getErrorMessage($this->getInvalidErrorCode())).');';
			$str .= Form::CANCEL_SUBMISSION_JS;
			$str .= '}';
		}

		$str .= 'if (';
		$str .= json_encode($this->getDisallowed()).'.includes($('.json_encode("#".$this->getId()).').val())';
		$str .= ') {';
		$str .= 'markInputInvalid('.json_encode('#'.$this->getId()).', '.json_encode($this->getErrorMessage($this->getInvalidErrorCode())).');';
		$str .= Form::CANCEL_SUBMISSION_JS;
		$str .= '}';

		$str .= 'if (';
		$str .= '!(new RegExp('.json_encode($this->getPattern()).').test($('.json_encode("#".$this->getId()).').val()))';
		$str .= ') {';
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
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', $('.json_encode("#".$this->getId()).').val());';
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * @param array $requestArr Array to find the form data in
	 */
	public function checkServerSide(?array &$requestArr=null) : void {
		if (is_null($requestArr)) {
			$requestArr = &$_REQUEST;
		}
		if (!array_key_exists($this->getDistinguisher(), $requestArr)) {
			$this->throwMissingError();
		}
		if ($this->isRequired()) {
			if (empty($requestArr[$this->getDistinguisher()])) {
				$this->throwMissingError();
			}
		} else {
			if (empty($requestArr[$this->getDistinguisher()])) {
				return; // not required and empty, don't do further checks
			}
		}
		if ($this->getMaxLength() > 0) {
			if (strlen($requestArr[$this->getDistinguisher()]) > $this->getMaxLength()) {
				$this->throwInvalidError();
			}
		}
		if (!preg_match('/'.str_replace("/", "\\/", $this->getPattern()).'/', $_POST[$this->getDistinguisher()])) {
			$this->throwInvalidError();
		}
		if (in_array($requestArr[$this->getDistinguisher()], $this->getDisallowed())) {
			$this->throwInvalidError();
		}
	}
}
