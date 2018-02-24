<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents a markdown field
 */
class MarkdownField extends AbstractField {
	use LabelTrait, SupportsPrefilledValueTrait;

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';

		$str .= '<p';
		$str .= ' class="col s12"';
		$str .= '>';
		$str .= 'Catalyst uses a modified version of Markdown in this field.  Please see ';
		$str .= '<a';
		$str .= ' href="'.ROOTDIR.'Markdown"';
		$str .= ' tabindex="-1"';
		$str .= '>';
		$str .= 'this page';
		$str .= '</a>';
		$str .= ' for help.';
		$str .= '</p>';

		$str .= '<div';
		$str .= ' class="input-field col s12"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="row"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="col s12 m6"';
		$str .= '>';

		$str .= '<textarea';
		$str .= ' class="materialize-textarea markdown-field"';

		if ($this->isRequired()) {
			$str .= ' required="required"';
		}

		$str .= ' id="'.htmlspecialchars($this->getId()).'"';
		$str .= '>';

		if ($this->isFieldPrefilled()) {
			$str .= htmlspecialchars($this->getPrefilledValue());
		}

		$str .= '</textarea>';

		$str .= '<label';
		$str .= ' type="text"';
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

		$str .= '<div';
		$str .= ' class="col s12 m6 markdown-target markdown-preview raw-markdown"';
		$str .= ' data-field="'.htmlspecialchars($this->getId()).'"';
		$str .= '>';

		if ($this->isFieldPrefilled()) {
			$str .= htmlspecialchars($this->getPrefilledValue());
		}

		$str .= '</div>';
		
		$str .= '</div>';
		
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
		}
	}
}
