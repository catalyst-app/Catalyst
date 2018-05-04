<?php

namespace Catalyst\Form\Field;

use \Catalyst\API\TransitEncryption;
use \Catalyst\Form\Form;
use \InvalidArgumentException;

/**
 * Represents a text field
 */
class ConfirmPasswordField extends PasswordField {
	/**
	 * Minimum length for the password
	 * @var PasswordField|null
	 */
	protected $linkedField = null;

	/**
	 * Get the field which must match
	 * 
	 * @return PasswordField|null Field to match
	 */
	public function getLinkedField() : ?PasswordField {
		return $this->linkedField;
	}

	/**
	 * Set the field which must match
	 * 
	 * @param PasswordField|null $linkedField New field to match
	 */
	public function setLinkedField(?PasswordField $linkedField) : void {
		$this->linkedField = $linkedField;
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		if (is_null($this->getLinkedField())) {
			throw new InvalidArgumentException("No field was linked to ConfirmPasswordField");
		}
		$str = parent::getJsValidator();

		$str .= 'if (';
		$str .= '$('.json_encode("#".$this->getId()).').val().length !== 0';
		$str .= ') {';

		$str .= 'if (';
		$str .= '$('.json_encode("#".$this->getId()).').val()';
		$str .= '!==';
		$str .= '$('.json_encode("#".$this->getLinkedField()->getId()).').val()';
		$str .= ') {';
		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - does not match linked field (".$this->getLinkedField()->getId().")").', true);';
		$str .= 'markInputInvalid('.json_encode('#'.$this->getId()).', '.json_encode($this->getErrorMessage($this->getInvalidErrorCode())).');';
		$str .= Form::CANCEL_SUBMISSION_JS;
		$str .= '}';

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
		if (is_null($this->getLinkedField())) {
			throw new InvalidArgumentException("No field was linked to ConfirmPasswordField");
		}
		if (!array_key_exists($this->getDistinguisher(), $requestArr)) {
			$this->throwMissingError();
		}
		$requestArr[$this->getDistinguisher()] = TransitEncryption::decryptAes($requestArr[$this->getDistinguisher()]);
		if ($this->isRequired()) {
			if (empty($requestArr[$this->getDistinguisher()])) {
				$this->throwMissingError();
			}
		} else {
			if (empty($requestArr[$this->getDistinguisher()])) {
				return; // not required and empty, don't do further checks
			}
		}
		if ($requestArr[$this->getDistinguisher()] !== $requestArr[$this->getLinkedField()->getDistinguisher()]) {
			$this->throwInvalidError();
		}
	}

	/**
	 * Get the default autocomplete attribute value
	 *
	 * @return string
	 */
	public static function getDefaultAutocompleteAttribute() : string {
		return AutocompleteValues::NEW_PASSWORD;
	}
}
