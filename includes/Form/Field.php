<?php

namespace Catalyst\Form;

use \LogicException;

/**
 * Represents a form's field
 * @abstract
 */
abstract class Field {
	/**
	 * internal name for the field
	 * 
	 * @var string
	 */
	protected $distinguisher = "";
	/**
	 * self explanatory
	 * 
	 * @var string
	 */
	protected $label = "";
	/**
	 * If the field is required
	 * 
	 * @var bool
	 */
	protected $required = true;
	/**
	 * If the field should be primary, will be overridden by Form
	 * 
	 * @var bool
	 */
	protected $primary = true;

	/**
	 * Array of error_code => error_message
	 * 
	 * @var string[]
	 */
	protected $errors = [];

	/**
	 * Error code to send if the field is missing
	 * 
	 * @var int
	 */
	protected $missingErrorCode = -1;
	/**
	 * Error code to send if the field fails validation
	 * 
	 * @var int
	 */
	protected $invalidErrorCode = -1;

	/**
	 * Additional check for the field
	 * 
	 * Must take an argument $field of type Field, callable is responsible for returning errors.
	 * 
	 * @var callable
	 */
	protected $additionalCheck = function(Field $field) {};

	/**
	 * The form the field is associated to
	 * 
	 * This SHOULD be assigned only when added to a Form.  It calls setForm() then
	 * 
	 * @var Form|null
	 */
	protected $form = null;

	/**
	 * Construct a Field object
	 * 
	 * @param string $distinguisher The internal name of the field
	 * @param string $label The field's label
	 * @param bool $required If the field is required
	 */
	public function __construct(string $distinguisher="", string $label="", bool $required=true) {
		$this->setDistinguisher($distinguisher);
		$this->setLabel($label);
		$this->setRequired($required);
	}

	/**
	 * Get the current distinguisher
	 * 
	 * @return string the distinguisher
	 */
	public function getDistinguisher() : string {
		return $this->distinguisher;
	}

	/**
	 * Set the distinguisher
	 * 
	 * @param string $distinguisher The new distinguisher
	 */
	public function setDistinguisher(string $distinguisher) : void {
		$this->distinguisher = $distinguisher;
	}

	/**
	 * Get the current label
	 * 
	 * @return string the label
	 */
	public function getLabel() : string {
		return $this->label;
	}

	/**
	 * Set the label
	 * 
	 * @param string $label The new label
	 */
	public function setLabel(string $label) : void {
		$this->label = $label;
	}

	/**
	 * Return whether the field is required
	 * 
	 * @return bool If the field is required
	 */
	public function isRequired() : bool {
		return $this->required;
	}

	/**
	 * Control whether the field is required
	 * 
	 * @param bool $required If the field is required
	 */
	public function setRequired(bool $required) : void {
		$this->required = $required;
	}

	/**
	 * Return whether the field is primary
	 * 
	 * @return bool If the field is primary
	 */
	public function isPrimary() : bool {
		return $this->primary;
	}

	/**
	 * Control whether the field is primary
	 * 
	 * @param bool $primary If the field is primary
	 */
	public function setPrimary(bool $primary) : void {
		$this->primary = $primary;
	}

	/**
	 * Errors which this field is associated with
	 * 
	 * @return array Keyed array of $code => $message
	 */
	public function getErrors() : array {
		return $this->errors;
	}

	/**
	 * Get the error message associated with a code
	 * 
	 * @param int $code error code to get message for
	 * @return string Error mesasge
	 */
	public function getErrorMessage(int $code) : string {
		return $this->errors[$code];
	}

	/**
	 * Add an error to the array
	 * 
	 * @param int $code The error code
	 * @param string $message The error message, to be displayed inline with input
	 * 	Ex: displayed under text elements in red
	 */
	public function addError(int $code, string $message) : void {
		$this->errors[$code] = $message;
	}

	/**
	 * Update an existing error code/message
	 * 
	 * @param int $code The error code
	 * @param string $message The error message
	 */
	public function setError(int $code, string $message) : void {
		$this->errors[$code] = $message;
	}

	/**
	 * Set the error array to an entirely new one
	 * 
	 * @param array $errors The new array of errors, keyed $code => $message
	 */
	public function setErrors(array $errors) : void {
		$this->errors = [];
		array_map([$this, "addError"], array_keys($errors), $errors);
	}

	/**
	 * Remove an error/message association
	 * 
	 * @param int $code The error code to remove
	 */
	public function removeError(int $code) : void {
		if (array_key_exists($code, $this->errors)) {
			unset($this->errors[$code]);
		}
	}

	/**
	 * Get the error code for when the field is missing
	 * 
	 * @return int The error code sent when the field is missing
	 */
	public function getMissingErrorCode() : int {
		return $this->missingErrorCode;
	}

	/**
	 * Set the error code for when the field is missing
	 * 
	 * @param int $missingErrorCode The error code to set the missing code to
	 */
	public function setMissingErrorCode(int $missingErrorCode) : void {
		$this->missingErrorCode = $missingErrorCode;
	}

	/**
	 * Get the error code for when the field is missing
	 * 
	 * @return int The error code sent when the field is missing
	 */
	public function getInvalidErrorCode() : int {
		return $this->invalidErrorCode;
	}

	/**
	 * Set the error code for when the field is invalid
	 * 
	 * @param int $invalidErrorCode The error code to return on invalid data
	 */
	public function setInvalidErrorCode(int $invalidErrorCode) : void {
		$this->invalidErrorCode = $invalidErrorCode;
	}

	/**
	 * Get the current additional check to perform
	 * 
	 * @return callable The field's additional check
	 */
	public function getAdditionalCheck() : callable {
		return $this->additionalCheck;
	}

	/**
	 * Set the field's additional check to a new value
	 * 
	 * @param callable $additionalCheck Check to perform
	 */
	public function setAdditionalCheck(callable $additionalCheck) : void {
		$this->additionalCheck = $additionalCheck;
	}

	/**
	 * Get the Form associated with the Field
	 * 
	 * @return Form the associated Form
	 */
	public function getForm() : Form {
		if (is_null($this->form)) {
			throw new LogicException("Form has not been set");
		}
		return $this->form;
	}

	/**
	 * Set the Form associated with the Field
	 * 
	 * @param Form $form the Form to associate
	 */
	public function setForm(Form $form) : void {
		$this->form = $form;
	}

	/**
	 * Return the field's ID attribute
	 * 
	 * @return string The Field's ID
	 */
	public function getId() : string {
		return $this->getForm()->getDistinguisher()."-input-".$this->getDistinguisher();
	}

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	abstract public function getHtml() : string;

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	abstract public function getJsValidator() : string;

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	abstract public function getJsAggregator(string $formDataName) : string;

	/**
	 * Check the field's forms on the servers side
	 * 
	 * No parameters as the fields have concrete names, and no return as appropriate errors are returned
	 */
	abstract public function checkServerSide() : void;
}
