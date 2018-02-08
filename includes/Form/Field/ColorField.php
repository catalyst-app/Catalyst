<?php

namespace Catalyst\Form\Field;

use \Catalyst\Color;
use \Catalyst\Form\Form;
use \Catalyst\Page\Values;

/**
 * Represents a field to pick a color from the allowed values
 */
class ColorField extends AbstractField {
	use LabelTrait;
	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';
		$str .= '<div';
		$str .= ' data-for="'.htmlspecialchars($this->getId()).'"';
		$str .= ' class="color-field col s12">';

		$str .= '<div';
		$str .= ' class="chosen-color btn"';
		$str .= ' data-for="'.htmlspecialchars($this->getId()).'"';
		$str .= ' style="background-color: #'.(Values::DEFAULT_COLOR).'"';
		$str .= '>';
		$str .= '</div>';

		$str .= '<div';
		$str .= ' class="color-input-wrapper row">';

		$str .= '<div';
		$str .= ' class="input-field col s12">';

		$str .= '<input';
		$str .= ' readonly="readonly"';
		$str .= ' type="text"';
		$str .= ' class="active"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';
		$str .= ' value="'.Values::DEFAULT_COLOR.'"';
		$str .= '>';

		$str .= '<label';
		$str .= ' for="'.htmlspecialchars($this->getId()).'"';
		$str .= ' data-error="'.htmlspecialchars($this->getErrorMessage($this->getInvalidErrorCode())).'"';
		$str .= '>';
		$str .= htmlspecialchars($this->getLabel());
		if ($this->isRequired()) {
			$str .= '<span class="red-text">';
			$str .= '&nbsp;*';
			$str .= '</span>';
		}
		$str .= '</label>';
		
		$str .= '</div>';
		
		$str .= '</div>';
		
		$str .= '</div>';

		$str .= '<div';
		$str .= ' class="color-picker-modal modal bottom-sheet">';
		$str .= '<div';
		$str .= ' class="modal-content">';
		$str .= '<h3>Color</h3>';
		$str .= '<h5>Choose a color</h5>';
		$str .= '<div';
		$str .= ' class="row">';

		// get the maximum swatches, either of a category or of categories themselves
		$numColorSwatches = max(count(Color::COLOR_BY_CATEGORY), max(array_map("count", Color::COLOR_BY_CATEGORY)));

		for ($i = 0; $i < $numColorSwatches; $i++) {
			$str .= '<div';
			$str .= ' class="color-swatch col l2 m3 s12"';
			$str .= '>';
			$str .= '</div>';
		}

		$str .= '</div>';
		$str .= '</div>';
		$str .= '</div>';
		return $str;

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

		$str .= 'if (';
		$str .= '!'.json_encode(array_keys(Color::HEX_MAP)).'.includes($('.json_encode("#".$this->getId()).').val())';
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
	 * No parameters as the fields have concrete names, and no return as appropriate errors are returned
	 */
	public function checkServerSide() : void {
		if (!isset($_REQUEST[$this->getDistinguisher()])) {
			$this->throwMissingError();
		}
		if ($this->isRequired()) {
			if (empty($_REQUEST[$this->getDistinguisher()])) {
				$this->throwMissingError();
			}
		} else {
			if (empty($_REQUEST[$this->getDistinguisher()])) {
				return; // not required and empty, don't do further checks
			}
		}
		if (!in_array($_POST[$this->getDistinguisher()], array_keys(Color::HEX_MAP))) {
			$this->throwInvalidError();
		}
	}
}
