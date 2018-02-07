<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;
use \InvalidArgumentException;

/**
 * Represents a text field
 */
class ConfirmPasswordField extends PasswordField {
	use LabelTrait;
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
		$str .= 'markInputInvalid('.json_encode('#'.$this->getId()).', '.json_encode($this->getErrorMessage($this->getInvalidErrorCode())).');';
		$str .= Form::CANCEL_SUBMISSION_JS;
		$str .= '}';

		$str .= '}';

		return $str;
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * No parameters as the fields have concrete names, and no return as appropriate errors are returned
	 */
	public function checkServerSide() : void {
		if (is_null($this->getLinkedField())) {
			throw new InvalidArgumentException("No field was linked to ConfirmPasswordField");
		}
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
		if ($_REQUEST[$this->getDistinguisher()] !== $_REQUEST[$this->getLinkedField()->getDistinguisher()]) {
			$this->throwInvalidError();
		}
	}
}
