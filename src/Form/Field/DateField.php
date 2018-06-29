<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents an date field with picker
 */
class DateField extends AbstractField {
	use LabelTrait, SupportsAutocompleteAttributeTrait, SupportsPrefilledValueTrait;

	/**
	 * Pattern to match a valid date
	 */
	public function getPattern() : string {
		return '^((Jan|Mar|May|Jul|Aug|Oct|Dec)\s(0[1-9]|[1-2][0-9]|3[0-1]),\s2[0-9]{3}|(Apr|Jun|Sep|Nov)\s(0[1-9]|[1-2][0-9]|30),\s2[0-9]{3}|Feb\s(0[1-9]|[1-2][0-8]),\s2[0-9]{3}|Feb\s29,\s2([048]00|[0-9][02468][48]|[0-9][13579][26]))$';
	}

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';
		$str .= '<div class="input-field col s12">';

		$inputClasses = ["datepicker"];
		$str .= '<input';
		$str .= ' type="text"';
		$str .= ' autocomplete="'.htmlspecialchars($this->getAutocompleteAttribute()).'"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';

		if ($this->isFieldPrefilled()) {
			if (!preg_match('/'.str_replace("/", "\\/", $this->getPattern()).'/', $this->getPrefilledValue())) {
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
		$str .= '!(new RegExp('.json_encode($this->getPattern()).').test($('.json_encode("#".$this->getId()).').val()))';
		$str .= ') {';
		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - field value did not pass regexp").', true);';
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
			if (empty($requestArr[$this->getDistinguisher()])) {
				return; // not required and empty, don't do further checks
			}
		}
		if (!preg_match('/'.str_replace("/", "\\/", $this->getPattern()).'/', $requestArr[$this->getDistinguisher()])) {
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
<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents an date field with picker
 */
class DateField extends AbstractField {
	use LabelTrait, SupportsAutocompleteAttributeTrait, SupportsPrefilledValueTrait;

	/**
	 * Pattern to match a valid date
	 */
	public function getPattern() : string {
		return '^((Jan|Mar|May|Jul|Aug|Oct|Dec)\s(0[1-9]|[1-2][0-9]|3[0-1]),\s2[0-9]{3}|(Apr|Jun|Sep|Nov)\s(0[1-9]|[1-2][0-9]|30),\s2[0-9]{3}|Feb\s(0[1-9]|[1-2][0-8]),\s2[0-9]{3}|Feb\s29,\s2([048]00|[0-9][02468][48]|[0-9][13579][26]))$';
	}

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';
		$str .= '<div class="input-field col s12">';

		$inputClasses = ["datepicker"];
		$str .= '<input';
		$str .= ' type="text"';
		$str .= ' autocomplete="'.htmlspecialchars($this->getAutocompleteAttribute()).'"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';

		if ($this->isFieldPrefilled()) {
			if (!preg_match('/'.str_replace("/", "\\/", $this->getPattern()).'/', $this->getPrefilledValue())) {
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
		$str .= '!(new RegExp('.json_encode($this->getPattern()).').test($('.json_encode("#".$this->getId()).').val()))';
		$str .= ') {';
		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - field value did not pass regexp").', true);';
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
			if (empty($requestArr[$this->getDistinguisher()])) {
				return; // not required and empty, don't do further checks
			}
		}
		if (!preg_match('/'.str_replace("/", "\\/", $this->getPattern()).'/', $requestArr[$this->getDistinguisher()])) {
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
