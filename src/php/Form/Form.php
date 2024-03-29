<?php

namespace Catalyst\Form;

use \Catalyst\API\{ErrorCodes, Response};
use \Catalyst\Form\CompletionAction\AbstractCompletionAction;
use \Catalyst\Form\Field\{AbstractField, ImageField, StaticHTMLField};
use \Catalyst\{Controller, HTTPCode};
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
	 * If form is should clear itself on successful submit
	 *
	 * @var bool
	 */
	protected $resetOnSuccess = false;

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
	 * @param bool $resetOnSuccess If form is should clear itself on successful submit
	 */
	public function __construct(string $distinguisher = "", ?AbstractCompletionAction $completionAction = null, int $method = self::POST, string $endpoint = "", string $buttonText = "", array $fields = [], bool $primary = true, bool $resetOnSuccess = false) {
		$this->setDistinguisher($distinguisher);
		$this->setCompletionAction($completionAction);
		$this->setMethod($method);
		$this->setEndpoint($endpoint);
		$this->setButtonText($buttonText);
		$this->setFields($fields);
		$this->setPrimary($primary);
		$this->setResetOnSuccess($resetOnSuccess);
	}

	/**
	 * Get the current form distinguisher
	 *
	 * @return string The distinguisher
	 */
	public function getDistinguisher(): string {
		return $this->distinguisher;
	}

	/**
	 * Set the form distinguisher to a new value
	 *
	 * @param string $distinguisher The new form distinguisher
	 */
	public function setDistinguisher(string $distinguisher): void {
		$this->distinguisher = $distinguisher;
	}

	/**
	 * Get the current form completion action (null if none)
	 *
	 * @return AbstractCompletionAction|null The current completion action
	 */
	public function getCompletionAction(): ?AbstractCompletionAction {
		return $this->completionAction;
	}

	/**
	 * Set the form completion action to a new value
	 *
	 * @param AbstractCompletionAction|null $completionAction The new action to be called upon completion
	 */
	public function setCompletionAction(?AbstractCompletionAction $completionAction): void {
		$this->completionAction = $completionAction;
	}

	/**
	 * Get the current form method (self::GET or self::POST)
	 *
	 * @return int The form's method, either self::GET or self::POST
	 */
	public function getMethod(): int {
		return $this->method;
	}

	/**
	 * Get the current form method as a string (GET or POST)
	 *
	 * @return string The form's method, either GET or POST
	 */
	public function getMethodString(): string {
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
	public function setMethod(int $method): void {
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
	public function getEndpoint(): string {
		return $this->endpoint;
	}

	/**
	 * Set the form endpoint to a new value
	 *
	 * This should be relative to self::BASE_URI
	 *
	 * @param string $endpoint The new endpoint
	 */
	public function setEndpoint(string $endpoint): void {
		$this->endpoint = $endpoint;
	}

	/**
	 * Get the current submit button text
	 *
	 * @return string The submission button text
	 */
	public function getButtonText(): string {
		return $this->buttonText;
	}

	/**
	 * Set the submit button text to a new value
	 *
	 * @param string $buttonText New text to display on the button
	 */
	public function setButtonText(string $buttonText): void {
		$this->buttonText = $buttonText;
	}

	/**
	 * Get the current array of Fields
	 *
	 * @return AbstractField[] Current fields
	 */
	public function getFields(): array {
		return $this->fields;
	}

	/**
	 * Add a field
	 *
	 * @param AbstractField $field New field to add
	 */
	public function addField(AbstractField $field): void {
		$field->setForm($this);
		$this->fields[] = $field;
	}

	/**
	 * Add a block of static HTML (via StaticHTMLField)
	 *
	 * @param string $html HTML block to add
	 */
	public function addStaticHtml(string $html): void {
		$staticField = new StaticHTMLField();
		$staticField->setStaticHtml($html);
		$this->addField($staticField);
	}

	/**
	 * Add several fields
	 *
	 * @param AbstractField[] $fields New fields to add
	 */
	public function addFields(array $fields): void {
		array_map([$this, "addField"], $fields);
	}

	/**
	 * Set the field array to a new value
	 *
	 * @param AbstractField[] $fields New fields to replace existing array
	 */
	public function setFields(array $fields): void {
		$this->fields = [];
		array_map([$this, "addField"], $fields); // lazy way of checking all fields
	}

	/**
	 * Get the form's primary status
	 *
	 * @return bool If the form is primary
	 */
	public function isPrimary(): bool {
		return $this->primary;
	}

	/**
	 * Set the form's primary status
	 *
	 * @param bool $primary If the form is primary
	 */
	public function setPrimary(bool $primary): void {
		$this->primary = $primary;
	}

	/**
	 * Get the form's resetOnSuccess status
	 *
	 * @return bool If the form is resetOnSuccess
	 */
	public function isResetOnSuccess(): bool {
		return $this->resetOnSuccess;
	}

	/**
	 * Set the form's resetOnSuccess status
	 *
	 * @param bool $resetOnSuccess If the form is resetOnSuccess
	 */
	public function setResetOnSuccess(bool $resetOnSuccess): void {
		$this->resetOnSuccess = $resetOnSuccess;
	}

	/**
	 * Get the ID of the form
	 *
	 * @return string Form's ID
	 */
	public function getId(): string {
		return $this->getDistinguisher() . self::FORM_ELEMENT_ID_SUFFIX;
	}

	/**
	 * Get the ID of the progress bar wrapper
	 *
	 * @return string The progress wrapper's ID
	 */
	public function getProgressWrapperId(): string {
		return $this->getDistinguisher() . self::PROGRESS_ELEMENT_ID_SUFFIX;
	}

	/**
	 * Get the ID of the submit button wrapper
	 *
	 * @return string The submit wrapper's ID
	 */
	public function getSubmitWrapperId(): string {
		return $this->getDistinguisher() . self::SUBMIT_ELEMENT_ID_SUFFIX;
	}

	/**
	 * Get the form's header/opening tag
	 *
	 * @deprecated
	 * @return string the opening tag
	 */
	public function getFormHeader(): string {
		$str = '';

		$str .= '<form';
		$str .= ' action="#' . htmlspecialchars($this->getId()) . '"';
		$str .= ' id="' . htmlspecialchars($this->getId()) . '"';
		$str .= ' method="' . htmlspecialchars($this->getMethodString()) . '"';
		$str .= ' enctype="multipart/form-data"';
		$str .= ' novalidate="novalidate"';
		$str .= '>';

		return $str;
	}

	/**
	 * Get the submission button HTML
	 *
	 * @deprecated
	 * @return string The ending html
	 */
	public function getSubmitButton(): string {
		$str = '';
		$str .= '<div';
		$str .= ' class="row"';
		$str .= '>';

		$str .= '<br>';

		$str .= '<div';
		$str .= ' id="' . htmlspecialchars($this->getSubmitWrapperId()) . '"';
		$str .= '>';

		$str .= '<button';
		$str .= ' id="' . htmlspecialchars($this->getDistinguisher() . self::SUBMIT_BUTTON_SUFFIX) . '"';
		$str .= ' type="submit"';
		$str .= ' class="';
		$str .= 'btn waves-effect waves-light';
		$str .= ' col s12 m4 l2';
		if ($this->getButtonText() == "DELETE") {
			$str .= ' red darken-1';
		}
		$str .= '"';
		$str .= '>';

		$str .= htmlspecialchars($this->getButtonText());

		$str .= '</button>';

		$str .= '</div>';

		$str .= '<div';
		$str .= ' id="' . htmlspecialchars($this->getProgressWrapperId()) . '"';
		$str .= ' class="hide"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="progress"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="indeterminate"';
		$str .= '>';

		$str .= '</div>';

		$str .= '</div>';

		$str .= '</div>';

		$str .= '</div>';

		return $str;
	}

	/**
	 * Get the form's HTML
	 *
	 * @deprecated Until rewrote in webcomponents
	 * @return string The form's HTML
	 */
	public function getHtml(bool $section = true): string {
		$str = '';

		if ($section) {
			$str .= '<div';
			$str .= ' class="section"';
			$str .= '>';
		}

		$str .= $this->getFormHeader();

		$str .= '<div';
		$str .= ' class="row"';
		$str .= '>';

		$setPrimary = $this->isPrimary();

		foreach ($this->fields as $field) {
			$field->setPrimary($setPrimary);
			$setPrimary = false;
			$str .= $field->getHtml();
		}

		$str .= '</div>';

		$str .= '<div class="divider">';
		$str .= '</div>';

		$str .= $this->getSubmitButton();

		$str .= '</form>';

		if ($section) {
			$str .= '</div>';
		}
		return $str;
	}

	/**
	 * Get the code to show the progress bar
	 *
	 * @deprecated
	 * @return string JS to show progress bar
	 */
	public function getShowProgressBarJs(): string {
		$str = '';
		$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - showing progress bar") . ');';
		$str .= '$(' . json_encode('#' . $this->getProgressWrapperId()) . ').removeClass("hide");';
		$str .= '$(' . json_encode('#' . $this->getSubmitWrapperId()) . ').addClass("hide");';
		return $str;
	}

	/**
	 * Get the code to hide the progress bar
	 *
	 * @deprecated
	 * @return string JS to hide progress bar
	 */
	public function getHideProgressBarJs(): string {
		$str = '';
		$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - hiding progress bar") . ');';
		$str .= '$(' . json_encode('#' . $this->getSubmitWrapperId()) . ').removeClass("hide");';
		$str .= '$(' . json_encode('#' . $this->getProgressWrapperId()) . ').addClass("hide");';
		return $str;
	}

	/**
	 * Return all the JS validation checks
	 *
	 * @deprecated
	 * @return string Form validation checks
	 */
	public function getJsValidation(): string {
		$str = '';
		$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - starting validation") . ');';
		foreach ($this->fields as $field) {
			$str .= $field->getJsValidator();
		}
		return $str;
	}

	/**
	 * Return code used to aggregate all form elements into self::FORM_DATA_VAR_NAME
	 *
	 * @deprecated
	 * @return string Aggregation code
	 */
	public function getJsAggregator(): string {
		$str = '';
		$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - starting aggregation as " . self::FORM_DATA_VAR_NAME) . ');';
		$str .= 'var ' . self::FORM_DATA_VAR_NAME . ' = new FormData();';
		$str .= self::FORM_DATA_VAR_NAME . '.append("rootdir", $("html").attr("data-rootdir"));';
		foreach ($this->fields as $field) {
			$str .= $field->getJsAggregator(self::FORM_DATA_VAR_NAME);
		}
		return $str;
	}

	/**
	 * Return code used to send and process AJAX request
	 *
	 * @deprecated
	 * @return string AJAX request
	 */
	public function getJsAjaxRequest(): string {
		$str = '';

		$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - sending " . json_encode($this->getMethodString()) . " AJAX request to " . $this->getEndpoint()) . ');';

		$str .= '$.ajax(';
		$str .= '$("html").attr("data-rootdir")+' . json_encode(self::BASE_URI . $this->getEndpoint());
		$str .= ',';
		// ajax config
		$str .= '{';
		$str .= 'data: ' . self::FORM_DATA_VAR_NAME . ',';
		$str .= 'processData: false,';
		$str .= 'contentType: false,';
		$str .= 'method: ' . json_encode($this->getMethodString());
		$str .= '})';

		// upload progress
		$str .= '.uploadProgress(function(e) {';
		$str .= 'updateUploadIndicator(' . json_encode("#" . $this->getProgressWrapperId()) . ', e);';
		$str .= '})';

		// success
		$str .= '.done(function(response) {';
		$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - successful response") . ');';
		$str .= 'try {';
		$str .= 'var data = JSON.parse(response);';
		$str .= 'M.escapeToast("Success", 4000);';

		if ($this->isResetOnSuccess()) {
			$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - resetting fields") . ');';
			$str .= '$(' . json_encode('#' . $this->getId()) . ')[0].reset();';
			$str .= '$(' . json_encode('#' . $this->getId()) . '+" input[type=text], textarea").removeClass("active").blur();';
		}

		if (!is_null($this->getCompletionAction())) {
			$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - invoking completion action " . get_class($this->getCompletionAction())) . ');';
			if (Controller::isDevelMode()) {
				$str .= 'debugger;';
			}
			$str .= $this->getCompletionAction()->getJs();
		}

		$str .= '} catch (e) {';
		$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - Bad JSON") . ', true);';
		$str .= 'showErrorMessageForCode(99999);';
		$str .= 'window.onerror("Bad JSON: "+response, "main js", -1);';
		$str .= '}';
		$str .= '})';

		// failure
		$str .= '.fail(function(response) {';

		$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - received error response") . ', true);';

		$str .= 'try {';
		$str .= 'var data = JSON.parse(response.responseText);';
		$str .= 'if (data.hasOwnProperty("error_type")) {';
		$str .= 'let fields = document.querySelectorAll("[data-properties^=\"{\"]");';
		$str .= 'for (let i=0; i<fields.length; i++) {';
		$str .= 'if (fields[i].properties.distinguisher == data.error_location) {';
		$str .= 'fields[i].markError(fields[i].properties.errors[data.error_type]);';
		$str .= '}';
		$str .= '}';
		$str .= 'return;';
		$str .= '}';
		$str .= 'switch (data.error_code) {';
		foreach ($this->getFields() as $field) {
			foreach ($field->getErrors() as $code => $message) {
				$str .= 'case ' . json_encode($code) . ':';
				$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - marking " . get_class($field) . " " . $field->getId() . " invalid") . ');';
				$str .= 'markInputInvalid(' . '$(' . json_encode("#" . $field->getId()) . '), data.message);';
				$str .= 'break;';
			}
		}
		foreach ($this->getAdditionalCases() as $code => $additionalCase) {
			$str .= 'case ' . json_encode($code) . ':';
			$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - additional case for " . $code . " invoked") . ');';
			$str .= $additionalCase->getJs();
			$str .= 'break;';
		}
		$str .= '}';
		$str .= 'showErrorMessageForCode(data.error_code);';
		$str .= '} catch (e) {';
		$str .= 'window.log("Form", ' . json_encode($this->getDistinguisher() . " - Bad JSON") . ', true);';
		$str .= 'showErrorMessageForCode(99999);';
		$str .= 'window.onerror("Bad JSON: "+response, "main js", -1);';
		$str .= '}';
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
	 * @deprecated
	 * @return string JS code
	 */
	public function getAllJs(): string {
		$str = '';

		$str .= '$(document).on("submit", ' . json_encode("#" . $this->getId()) . ', function(e) {';
		$str .= 'e.preventDefault && e.preventDefault();';
		$str .= $this->getJsValidation();
		$str .= $this->getJsAggregator();
		$str .= $this->getShowProgressBarJs();
		$str .= $this->getJsAjaxRequest();
		$str .= '});';

		foreach ($this->getFields() as $field) {
			$str .= $field->getJsOnload();
		}

		return $str;
	}

	/**
	 * Check the field's forms on the servers side
	 *
	 * @param array $requestArr Array to find the form data in
	 * @param bool $checkContentLength If it should check (and return an appropriate error) if the requestArr is empty but data was sent
	 */
	public function checkServerSide(?array &$requestArr = null, bool $checkContentLength = true): void {
		if (is_null($requestArr)) {
			if ($this->getMethod() == Form::POST) {
				$requestArr = &$_POST;
			} else {
				$requestArr = &$_GET;
			}
		}
		if (strtoupper($_SERVER["REQUEST_METHOD"]) !== strtoupper($this->getMethodString())) {
			HTTPCode::set(405);
			Response::sendErrorResponse(10002, ErrorCodes::ERR_10002);
		}
		// https://stackoverflow.com/a/9908619/
		if ($checkContentLength && $this->getMethod() == self::POST && intval($_SERVER['CONTENT_LENGTH']) > 0 && count($requestArr) === 0) {
			foreach ($this->getFields() as $field) {
				if ($field instanceof ImageField) {
					$field->throwTooLargeError();
				}
			}
			throw new Exception('PHP discarded POST data because of request exceeding post_max_size, but there are no ImageField\'s in the form');
		}
		foreach ($this->fields as $field) {
			$field->checkServerSide($requestArr);
		}
	}

	/**
	 * Get the additional cases
	 *
	 * @return AbstractCompletionAction[]
	 */
	public function getAdditionalCases(): array {
		return $this->additionalCases;
	}

	/**
	 * Set additional cases to a new value
	 *
	 * @param int $code Error code to run this action for
	 * @param AbstractCompletionAction $additionalCase Action to run
	 */
	public function addAdditionalCases(int $code, AbstractCompletionAction $additionalCase): void {
		$this->additionalCases[$code] = $additionalCase;
	}

	/**
	 * Set additional cases to a new value
	 *
	 * @param AbstractCompletionAction[] $additionalCases new cases
	 */
	public function setAdditionalCases(array $additionalCases): void {
		$this->additionalCases = [];
		/** @var callable */
		$func = [$this, "addAdditionalCase"];
		array_map($func, array_keys($additionalCases), $additionalCases);
	}
}
