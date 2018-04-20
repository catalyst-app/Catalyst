<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Used for a field which takes a selector's val()
 */
class HiddenInputField extends AbstractField {
	/**
	 * The selector of the input to grab the `.val()` of
	 * 
	 * @var string
	 */
	protected $selector = "";

	/**
	 * Get the current selector
	 * 
	 * @return string the selector
	 */
	public function getSelector() : string {
		return $this->selector;
	}

	/**
	 * Set the selector
	 * 
	 * @param string $selector The new selector
	 */
	public function setSelector(string $selector) : void {
		$this->selector = $selector;
	}

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		return "";
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		$str = '';

		$str .= 'if (';
		if ($this->isRequired()) {
			$str .= '$('.json_encode($this->getSelector()).').length !== 1';
		} else {
			$str .= '$('.json_encode($this->getSelector()).').length > 1';
		}
		$str .= ') {';

		$str .= 'window.log('.json_encode(__CLASS__).', '.json_encode($this->getId()." - zero or multiple fields with selector ".$this->getSelector()." were found").', true);';
		$str .= 'M.escapeToast("An unknown error has occured.", 4000);';
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
		$str = '';

		$str .= 'if (';
		$str .= '$('.json_encode($this->getSelector()).').length !== 1';
		$str .= ') {';

		$str .= $formDataName.'.append(';
		$str .= json_encode($this->getDistinguisher());
		$str .= ', ';
		$str .= '""';
		$str .= ');';

		$str .= '} else {';

		$str .= $formDataName.'.append(';
		$str .= json_encode($this->getDistinguisher());
		$str .= ', ';
		$str .= '$('.json_encode($this->getSelector()).').val()';
		$str .= ');';

		$str .= '}';

		return $str;
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
}
