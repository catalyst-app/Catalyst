<?php

namespace Catalyst\Form\Field;

/**
 * Represents a checkbox field but with a raw label
 */
class RawLabelCheckboxField extends CheckboxField {
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
		if ($this->isFieldPrefilled()) {
			if (!is_bool($this->getPrefilledValue())) {
				$this->throwInvalidPrefilledValueError();
			}
			if ($this->getPrefilledValue()) {
				$str .= ' checked="checked"';
			}
		}
		if ($this->isRequired()) {
			$str .= ' required="required"';
		}
		$str .= '>';
		
		$str .= '<label';
		$str .= ' for="'.htmlspecialchars($this->getId()).'"';
		$str .= '>';
		$str .= $this->getLabel();
		if ($this->isRequired()) {
			$str .= '<span class="red-text">';
			$str .= '&nbsp;*';
			$str .= '</span>';
		}
		$str .= '</label>';
		
		// required due to the way materialize draws the checkbox
		$str .= '<span';
		$str .= ' data-error="'.htmlspecialchars($this->getErrorMessage($this->getInvalidErrorCode())).'"';
		$str .= '></span>';

		$str .= '</p>';
		return $str;
	}
}
