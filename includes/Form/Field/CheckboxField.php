<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents a checkbox field
 */
class CheckboxField extends AbstractField {
	use LabelTrait;
	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';
		$str .= '<p';
		$str .= ' class="col s12">';

		$str .= '<input';
		$str .= ' type="checkbox"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';
		$str .= ' class="filled-in validate"';
		if ($this->isRequired()) {
			$str .= ' required="required"';
		}
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
		$str .= '</p>';
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
			$str .= '!$('.json_encode("#".$this->getId()).').is(":checked")';
			$str .= ') {';
			$str .= 'markInputInvalid('.json_encode('#'.$this->getId()).', '.json_encode($this->getErrorMessage($this->getInvalidErrorCode())).');';
			$str .= Form::CANCEL_SUBMISSION_JS;
			$str .= '}';
		}

		return $str;
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', $('.json_encode("#".$this->getId()).').is(":checked");';
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * No parameters as the fields have concrete names, and no return as appropriate errors are returned
	 */
	public function checkServerSide() : void {
		if (!isset($_REQUEST[$this->getDistinguisher()])) {
			$this->throwMissingError();
		}
		if ($this->isRequired()) {
			if ($_REQUEST[$this->getDistinguisher()] !== "true") {
				$this->throwMissingError();
			}
		}
	}
}
