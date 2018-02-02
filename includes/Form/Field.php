<?php

namespace Catalyst\Form;

/**
 * Represents a form's field
 * @abstract
 */
abstract class Field {
	/**
	 * internal name for the field
	 */
	protected $distinguisher = "";
	/**
	 * self explanatory
	 */
	protected $label = "";
	/**
	 * If the field is required
	 */
	protected $required = true;
	/**
	 * If the field should be primary, will be overridden by Form
	 */
	protected $primary = true;

	/**
	 * Array of error_code => error_message
	 */
	protected $errors = [];

	/**
	 * Construct a Field object
	 * 
	 * @param string $distinguisher The internal name of the field
	 * @param sting $label The field's label
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
	public function getRequired() : bool {
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
	 * Errors which this field is associated with
	 * 
	 * @return array Keyed array of $code => $message
	 */
	public function getErrors() : array {
		return $this->errors;
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
}
