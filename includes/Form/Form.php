<?php

namespace Catalyst\Form;

use \Catalyst\Form\CompletionAction\AbstractCompletionAction;
use \Catalyst\Form\Field\{AbstractField,ImageField};
use \Exception;
use \InvalidArgumentException;

/**
 * Represents a form
 */
class Form {
	public const GET = 0;
	public const POST = 1;

	public const BASE_URI = "api/";

	/**
	 * Appended to distinguisher for <form> ID
	 */
	public const FORM_ELEMENT_ID_SUFFIX = "-form-element";

	/**
	 * Appended to distinguisher for submit button wrapper
	 */
	public const SUBMIT_BUTTON_WRAPPER_SUFFIX = "-submit-wrapper";
	/**
	 * Appended to distinguisher for submit button
	 */
	public const SUBMIT_BUTTON_SUFFIX = "-submit-btn";
	/**
	 * Appended to distinguisher for progress div
	 */
	public const PROGRESS_ELEMENT_ID_SUFFIX = "-progress-wrapper";
	/**
	 * Appended to distinguisher for submit button wrapper
	 */
	public const SUBMIT_ELEMENT_ID_SUFFIX = "-submit-wrapper";

	/**
	 * Code to stop further action within the form submission JS
	 */
	public const CANCEL_SUBMISSION_JS = "return;";

	/**
	 * Name for the FormData variable in the JS
	 */
	public const FORM_DATA_VAR_NAME = "data";

	/**
	 * Name for the form, used to distinguish it from others
	 * 
	 * @var string
	 */
	protected $distinguisher = "";
	/**
	 * Action to perform upon form submission.  One of AbstractCompletionAction
	 * 
	 * @var AbstractCompletionAction|null
	 */
	protected $completionAction = null;
	/**
	 * Method used for request.  One of self::GET or self::POST
	 * 
	 * @var int
	 */
	protected $method = self::POST;
	/**
	 * Endpoint to handle request.  Will almost always be internal/..., relative to /api/
	 * 
	 * @var string
	 */
	protected $endpoint = "";
	/**
	 * Text for submit button
	 * 
	 * @var string
	 */
	protected $buttonText = "submit";
	/**
	 * If form is the only form on page (and thus should auto-focus)
	 * 
	 * @var bool
	 */
	protected $primary = true;

	/**
	 * Additional actions to perform upon certain error codes
	 * 
	 * @var AbstractCompletionAction[]
	 */
	protected $additionalCases = [];

	/**
	 * Array of AbstractField[] objects
	 * 
	 * @var AbstractField[]
	 */
	protected $fields = [];

	/**
	 * Create a new Form based on the given parameters
	 * 
	 * @param string $distinguisher
	 * @param AbstractCompletionAction|null $completionAction
	 * @param int $method
	 * @param string $endpoint
	 * @param string $buttonText
	 * @param AbstractField[] $fields
	 * @param bool $primary If the form is the only one on the page (it should be focused automatically if so)
	 */
	public function __construct(string $distinguisher="", ?AbstractCompletionAction $completionAction=null, int $method=self::POST, string $endpoint="", string $buttonText="", array $fields=[], bool $primary=true) {
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
	 * @return AbstractCompletionAction|null The current completion action
	 */
	public function getCompletionAction() : ?AbstractCompletionAction {
		return $this->completionAction;
	}

	/**
	 * Set the form completion action to a new value
	 * 
	 * @param AbstractCompletionAction|null $completionAction The new action to be called upon completion
	 */
	public function setCompletionAction(?AbstractCompletionAction $completionAction) : void {
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
	 * Get the current form method as a string (GET or POST)
	 * 
	 * @return string The form's method, either GET or POST
	 */
	public function getMethodString() : string {
		switch ($this->getMethod()) {
			case self::GET:
				return "GET";
				break;
			case self::POST:
				return "POST";
				break;
			default:
				throw new InvalidArgumentException("Unknown method type");
		}
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
	 * This should be relative to self::BASE_URI
	 * 
	 * @return string The current endpoint
	 */
	public function getEndpoint() : string {
		return $this->endpoint;
	}

	/**
	 * Set the form endpoint to a new value
	 * 
	 * This should be relative to self::BASE_URI
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
	 * @return AbstractField[] Current fields
	 */
	public function getFields() : array {
		return $this->fields;
	}

	/**
	 * Add a field
	 * 
	 * @param AbstractField $field New field to add
	 */
	public function addField(AbstractField $field) : void {
		$field->setForm($this);
		$this->fields[] = $field;
	}

	/**
	 * Add several fields
	 * 
	 * @param AbstractField[] $fields New fields to add
	 */
	public function addFields(array $fields) : void {
		array_map([$this, "addField"], $fields);
	}

	/**
	 * Set the field array to a new value
	 * 
	 * @param AbstractField[] $fields New fields to replace existing array
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
	public function isPrimary() : bool {
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

	/**
	 * Get the ID of the form
	 * 
	 * @return string Form's ID
	 */
	public function getId() : string {
		return $this->getDistinguisher().self::FORM_ELEMENT_ID_SUFFIX;
	}

	/**
	 * Get the ID of the progress bar wrapper
	 * 
	 * @return string The progress wrapper's ID
	 */
	public function getProgressWrapperId() : string {
		return $this->getDistinguisher().self::PROGRESS_ELEMENT_ID_SUFFIX;
	}

	/**
	 * Get the ID of the submit button wrapper
	 * 
	 * @return string The submit wrapper's ID
	 */
	public function getSubmitWrapperId() : string {
		return $this->getDistinguisher().self::SUBMIT_ELEMENT_ID_SUFFIX;
	}

	/**
	 * Get the form's header/opening tag
	 * 
	 * @return string the opening tag
	 */
	public function getFormHeader() : string {
		return '<form action="#'.htmlspecialchars($this->getId()).'" id="'.htmlspecialchars($this->getId()).'" method="'.htmlspecialchars($this->getMethodString()).'" enctype="multipart/form-data">';
	}

	/**
	 * Get the submission button HTML
	 * 
	 * @return string The ending html
	 */
	public function getSubmitButton() : string {
		$str = '';
		$str .= '<div class="row">';
		$str .= '<br>';
		$str .= '<div id="'.htmlspecialchars($this->getSubmitWrapperId()).'">';
		$str .= '<button id="'.htmlspecialchars($this->getDistinguisher().self::SUBMIT_BUTTON_SUFFIX).'" class="btn waves-effect waves-light col s12 m4 l2">';
		$str .= htmlspecialchars($this->getButtonText());
		$str .= '</button>';
		$str .= '</div>';
		$str .= '<div id="'.htmlspecialchars($this->getProgressWrapperId()).'" class="hide">';
		$str .= '<div class="progress">';
		$str .= '<div class="indeterminate"></div>';
		$str .= '</div>';
		$str .= '</div>';
		$str .= '</div>';
		return $str;
	}

	/**
	 * Get the form's HTML
	 * 
	 * @return string The form's HTML
	 */
	public function getHtml() : string {
		$str = '';
		$str .= '<div class="section">';
		$str .= $this->getFormHeader();
		$str .= '<div class="row">';
		$setPrimary = $this->isPrimary();
		foreach ($this->fields as $field) {
			$field->setPrimary($setPrimary);
			$setPrimary = false;
			$str .= $field->getHtml();
		}
		$str .= '</div>';
		$str .= '<br>';
		$str .= '<div class="divider">';
		$str .= '</div>';
		$str .= $this->getSubmitButton();
		$str .= '</form>';
		$str .= '</div>';
		return $str;
	}

	/**
	 * Get the code to show the progress bar
	 * 
	 * @return string JS to show progress bar
	 */
	public function getShowProgressBarJs() : string {
		$str = '';
		$str .= '$('.json_encode('#'.$this->getProgressWrapperId()).').removeClass("hide");';
		$str .= '$('.json_encode('#'.$this->getSubmitWrapperId()).').addClass("hide");';
		return $str;
	}

	/**
	 * Get the code to hide the progress bar
	 * 
	 * @return string JS to hide progress bar
	 */
	public function getHideProgressBarJs() : string {
		$str = '';
		$str .= '$('.json_encode('#'.$this->getSubmitWrapperId()).').removeClass("hide");';
		$str .= '$('.json_encode('#'.$this->getProgressWrapperId()).').addClass("hide");';
		return $str;
	}

	/**
	 * Return all the JS validation checks
	 * 
	 * @return string Form validation checks
	 */
	public function getJsValidation() : string {
		$str = '';
		foreach ($this->fields as $field) {
			$str .= $field->getJsValidator();
		}
		return $str;
	}

	/**
	 * Return code used to aggregate all form elements into self::FORM_DATA_VAR_NAME
	 * 
	 * @return string Aggregation code
	 */
	public function getJsAggregator() : string {
		$str = 'var '.self::FORM_DATA_VAR_NAME.' = new FormData();';
		foreach ($this->fields as $field) {
			$str .= $field->getJsAggregator(self::FORM_DATA_VAR_NAME);
		}
		return $str;
	}

	/**
	 * Return code used to send and process AJAX request
	 * 
	 * @return string AJAX request
	 */
	public function getJsAjaxRequest() : string {
		$str = '';
		$str .= '$.ajax(';
		$str .= '$("html").attr("data-rootdir")+'.json_encode(self::BASE_URI.$this->getEndpoint());
		$str .= ',';
		// ajax config
		$str .= '{';
		$str .= 'data: '.self::FORM_DATA_VAR_NAME.',';
		$str .= 'processData: false,';
		$str .= 'contentType: false,';
		$str .= 'method: '.json_encode($this->getMethodString());
		$str .= '})';

		// upload progress
		$str .= '.uploadProgress(function(e) {';
		$str .= 'updateUploadIndicator('.json_encode("#".$this->getProgressWrapperId()).', e);';
		$str .= '})';

		// success
		$str .= '.done(function(response) {';
		$str .= 'console.log(response);';
		$str .= 'var data = JSON.parse(response);';
		$str .= 'Materialize.toast("Success", 4000);';
		$str .= '$('.json_encode('#'.$this->getId()).')[0].reset();';
		$str .= '$('.json_encode('#'.$this->getId()).'+" input[type=text], textarea").removeClass("active").blur();';
		if (!is_null($this->getCompletionAction())) {
			$str .= $this->getCompletionAction()->getJs();
		}
		$str .= '})';

		// failure
		$str .= '.fail(function(response) {';
		$str .= 'console.log(response);';
		$str .= 'var data = JSON.parse(response.responseText);';
		$str .= 'switch (data.error_code) {';
		foreach ($this->getFields() as $field) {
			foreach ($field->getErrors() as $code => $message) {
				$str .= 'case '.json_encode($code).':';
				$str .= 'markInputInvalid('.'$('.json_encode("#".$field->getId()).'), data.message);';
				$str .= 'break;';
			}
		}
		foreach ($this->getAdditionalCases() as $code => $additionalCase) {
			$str .= 'case '.json_encode($code).':';
			$str .= $additionalCase->getJs();
			$str .= 'break;';
		}
		$str .= '}';
		$str .= 'showErrorMessageForCode(data.error_code);';
		$str .= '})';

		// always
		$str .= '.always(function() {';
		$str .= $this->getHideProgressBarJs();
		$str .= '})';

		$str .= ';';

		return $str;
	}

	/**
	 * Get all of the JavaScript for the form
	 * 
	 * @return string JS code
	 */
	public function getAllJs() : string {
		$str = '';
		$str .= '$(document).on("submit", '.json_encode("#".$this->getId()).', function(e) {';
		$str .= 'e.preventDefault();';
		$str .= $this->getJsValidation();
		$str .= $this->getJsAggregator();
		$str .= $this->getShowProgressBarJs();
		$str .= $this->getJsAjaxRequest();
		$str .= '});';
		return $str;
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * No parameters as the fields have concrete names, and no return as appropriate errors are returned
	 */
	public function checkServerSide() : void {
		if (strtoupper($_SERVER["REQUEST_METHOD"]) !== strtoupper($this->getMethodString())) {
			\Catalyst\Response::send405($this->getMethodString());
		}
		// https://stackoverflow.com/a/9908619/
		if ($this->getMethod() == self::POST && intval($_SERVER['CONTENT_LENGTH']) > 0 && count($_POST) === 0) {
			foreach ($this->getFields() as $field) {
				if ($field instanceof ImageField) {
					$field->throwTooLargeError();
				}
			}
			throw new Exception('PHP discarded POST data because of request exceeding post_max_size, but there are no ImageField\'s in the form');
		}
		foreach ($this->fields as $field) {
			$field->checkServerSide();
		}
	}

	/**
	 * Get the additional cases
	 * 
	 * @return AbstractCompletionAction[]
	 */
	public function getAdditionalCases() : array {
		return $this->additionalCases;
	}

	/**
	 * Set additional cases to a new value
	 * 
	 * @param int $code Error code to run this action for
	 * @param AbstractCompletionAction $additionalCase Action to run
	 */
	public function addAdditionalCases(int $code, AbstractCompletionAction $additionalCase) : void {
		$this->additionalCases[$code] = $additionalCase;
	}

	/**
	 * Set additional cases to a new value
	 * 
	 * @param AbstractCompletionAction[] $additionalCases new cases
	 */
	public function setAdditionalCases(array $additionalCases) : void {
		$this->additionalCases = [];
		array_map([$this, "addAdditionalCase"], array_keys($additionalCases), $additionalCases);
	}
}
