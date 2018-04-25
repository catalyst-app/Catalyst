<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Represents a markdown field
 */
class MarkdownField extends AbstractField {
	use LabelTrait, SupportsAutocompleteAttributeTrait, SupportsPrefilledValueTrait;

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';

		$str .= '<p';
		$str .= ' class="col s12 no-bottom-margin"';
		$str .= '>';
		$str .= 'Catalyst uses a modified version of Markdown in this field.  Please see ';
		$str .= '<a';
		$str .= ' href="'.ROOTDIR.'Markdown"';
		$str .= ' tabindex="-1"';
		$str .= ' target="_blank"';
		$str .= '>';
		$str .= 'this page';
		$str .= '</a>';
		$str .= ' for help.';
		$str .= '</p>';

		$str .= '<div';
		$str .= ' class="col s12"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="row"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="input-field col s12 m6"';
		$str .= '>';

		$str .= '<textarea';
		$str .= ' autocomplete="'.htmlspecialchars($this->getAutocompleteAttribute()).'"';
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

		$str .= $this->getLabelHtml();

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
			$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - field is required, but empty").', true);';
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
		}
	}

	/**
	 * Get the default autocomplete attribute value
	 *
	 * @return string
	 */
	public static function getDefaultAutocompleteAttribute() : string {
		return SupportsAutocompleteAttributeTrait::$on;
	}
}
