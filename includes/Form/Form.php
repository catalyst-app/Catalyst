<?php

namespace Catalyst\Form;

use \InvalidArgumentException;

/**
 * Represents a form
 */
class Form {
	public const GET = 0;
	public const POST = 1;

	public const BASE_URI = "api/";

	/**
	 * Name for the form, used to distinguish it from others
	 */
	protected $distinguisher = "";
	/**
	 * Action to perform upon form submission.  One of CompletionAction
	 */
	protected $completionAction = null;
	/**
	 * Method used for request.  One of self::GET or self::POST
	 */
	protected $method = self::POST;
	/**
	 * Endpoint to handle request.  Will almost always be internal/..., relative to /api/
	 */
	protected $endpoint = "";
	/**
	 * Text for submit button
	 */
	protected $buttonText = "submit";
	/**
	 * If form is the only form on page (and thus should auto-focus)
	 */
	protected $primary = true;

	/**
	 * Array of Field[] objects
	 */
	protected $fields = [];

	/**
	 * Create a new Form based on the given parameters
	 * 
	 * @param string $distinguisher
	 * @param CompletionAction|null $completionAction
	 * @param int $method
	 * @param string $endpoint
	 * @param string $buttonText
	 * @param Field[] $fields
	 * @param bool $primary If the form is the only one on the page (it should be focused automatically if so)
	 */
	public function __construct(string $distinguisher="", ?CompletionAction $completionAction=null, int $method=self::POST, string $endpoint="", string $buttonText="", array $fields=[], bool $primary=true) {
		$this->setDistinguisher($distinguisher);
		$this->setCompletionAction($completionAction);
		$this->setMethod($method);
		$this->setEndpoint($endpoint);
		$this->setButtonText($buttonText);
		$this->setFields($fields);
		$this->setPrimary($primary);
	}

	/**
	 * Get the current form distinguisher
	 * 
	 * @return string The distinguisher
	 */
	public function getDistinguisher() : string {
		return $this->distinguisher;
	}

	/**
	 * Set the form distinguisher to a new value
	 * 
	 * @param string $distinguisher The new form distinguisher
	 */
	public function setDistinguisher(string $distinguisher) : void {
		$this->distinguisher = $distinguisher;
	}

	/**
	 * Get the current form completion action (null if none)
	 * 
	 * @return CompletionAction|null The current completion action
	 */
	public function getCompletionAction() : ?CompletionAction {
		return $this->completionAction;
	}

	/**
	 * Set the form completion action to a new value
	 * 
	 * @param CompletionAction|null $completionAction The new action to be called upon completion
	 */
	public function setCompletionAction(?CompletionAction $completionAction) : void {
		$this->completionAction = $completionAction;
	}

	/**
	 * Get the current form method (self::GET or self::POST)
	 * 
	 * @return int The form's method, either self::GET or self::POST
	 */
	public function getMethod() : int {
		return $this->method;
	}

	/**
	 * Set the form method
	 * 
	 * @param int $method New method for form
	 */
	public function setMethod(int $method) : void {
		if ($method !== self::GET && $method !== self::POST) {
			throw new InvalidArgumentException("Method not one of POST or GET (are you using Form class constants?)");
		}
		$this->method = $method;
	}

	/**
	 * Get the current form endpoint (where the AJAX call is sent)
	 * 
	 * This should be relative to self::BASE_URL
	 * 
	 * @return string The current endpoint
	 */
	public function getEndpoint() : string {
		return $this->endpoint;
	}

	/**
	 * Set the form endpoint to a new value
	 * 
	 * This should be relative to self::BASE_URL
	 * 
	 * @param string $endpoint The new endpoint
	 */
	public function setEndpoint(string $endpoint) : void {
		$this->endpoint = $endpoint;
	}

	/**
	 * Get the current submit button text
	 * 
	 * @return string The submission button text
	 */
	public function getButtonText() : string {
		return $this->buttonText;
	}

	/**
	 * Set the submit button text to a new value
	 * 
	 * @param string $buttonText New text to display on the button
	 */
	public function setButtonText(string $buttonText) : void {
		$this->buttonText = $buttonText;
	}

	/**
	 * Get the current array of Fields
	 * 
	 * @return Field[] Current fields
	 */
	public function getFields() : array {
		return $this->fields;
	}

	/**
	 * Add a field
	 * 
	 * @param Field $field New field to add
	 */
	public function addField(Field $field) : void {
		$this->fields[] = $field;
	}

	/**
	 * Add several fields
	 * 
	 * @param Field[] $fields New fields to add
	 */
	public function addFields(array $fields) : void {
		array_map([$this, "addField"], $fields);
	}

	/**
	 * Set the field array to a new value
	 * 
	 * @param Field[] $fields New fields to replace existing array
	 */
	public function setFields(array $fields) : void {
		$this->fields = [];
		array_map([$this, "addField"], $fields); // lazy way of checking all fields
	}

	/**
	 * Get the form's primary status
	 * 
	 * @return bool If the form is primary
	 */
	public function getPrimary() : bool {
		return $this->primary;
	}

	/**
	 * Set the form's primary status
	 * 
	 * @param bool $primary If the form is primary
	 */
	public function setPrimary(bool $primary) : void {
		$this->primary = $primary;
	}
}
