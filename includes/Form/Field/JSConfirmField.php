<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Requires a user to confirm through js confirm function
 */
class JSConfirmField extends AbstractField {
	/**
	 * The string to prompt the user with
	 * @var string
	 */
	protected $prompt='';

	/**
	 * Get the field's prompt
	 * 
	 * @return string
	 */
	public function getPrompt() : string {
		return $this->prompt;
	}

	/**
	 * Set the field's prompt
	 * 
	 * @param string $prompt
	 */
	public function setPrompt(string $prompt) : void {
		$this->prompt = $prompt;
	}

	/**
	 * Return the field's HTML input
	 * 
	 * No HTML for this
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		return '';
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
			$str .= '!confirm('.json_encode($this->getPrompt()).')';
			$str .= ') {';
			$str .= 'Materialize.toast("Action canceled", 4000);';
			$str .= Form::CANCEL_SUBMISSION_JS;
			$str .= '}';
		}

		return $str;
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * No aggregation as no value
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return '';
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * No validation
	 */
	public function checkServerSide() : void {
		return;
	}
}
