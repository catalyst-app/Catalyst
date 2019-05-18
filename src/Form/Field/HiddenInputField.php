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
	protected $hiddenInputId = "";

	/**
	 * Get the ID of the hidden input to get the value from
	 */
	public function getHiddenInputId() : string {
		return $this->hiddenInputId;
	}

	/**
	 * Set the hidden input ID
	 * 
	 * @param string $hiddenInputId The new hidden input ID
	 */
	public function setHiddenInputId(string $hiddenInputId) : void {
		$this->hiddenInputId = $hiddenInputId;
	}

	/**
	 * Return the field's HTML input
	 */
	public function getHtml() : string {
		$str = '';

		// all this serves to do is provide a placeholder for the client JS
		$str .= '<input';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';
		$str .= ' type="hidden"';
		$str .= ' class="form-field"';
		$str .= ' data-field-type="'.htmlspecialchars(self::class).'"';
		$str .= ' data-hidden-input-id="'.htmlspecialchars($this->getHiddenInputId()).'"';
		$str .= '>';

		return $str;
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
			$str .= '$('.json_encode("#".$this->getHiddenInputId()).').length !== 1';
		} else {
			$str .= '$('.json_encode("#".$this->getHiddenInputId()).').length > 1';
		}
		$str .= ') {';

		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - zero or multiple fields with ID ".$this->getHiddenInputId()." were found").', true);';
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
		$str .= '$('.json_encode($this->getHiddenInputId()).').length';
		$str .= ') {';

		$str .= $formDataName.'.append(';
		$str .= json_encode($this->getDistinguisher());
		$str .= ', ';
		$str .= '$('.json_encode("#".$this->getHiddenInputId()).').val()';
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
		}
	}
}
