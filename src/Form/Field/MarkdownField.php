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
		$str .= '<a href="'.ROOTDIR.'Markdown" tabindex="-1" target="_blank">this page</a>';
		$str .= ' for help.';
		$str .= '</p>';

		$str .= '<div class="col s12">';

		$str .= '<div class="row">';

		$str .= '<div class="input-field col s12 m6">';

		$str .= '<textarea';
		$str .= ' autocomplete="'.htmlspecialchars($this->getAutocompleteAttribute()).'"';
		$str .= ' data-field-type="'.htmlspecialchars(self::class).'"';
		$str .= ' class="materialize-textarea markdown-field form-field"';

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
		return 'if (!(new window.formInputHandlers['.json_encode(self::class).'](document.getElementById('.json_encode($this->getId()).')).verify())) { return; }';
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
			}
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
