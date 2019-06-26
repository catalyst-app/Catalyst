<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Requires a user to confirm through (currently) js confirm function during the validation
 */
class ConfirmField extends AbstractField {
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
	 * @return string The name of the web component tag
	 */
	public static function getWebComponentName() : string {
		return "confirm-field";
	}

	/**
	 * Get the DESCRIPTIVE error message types and default messages
	 * @return string[]
	 */
	protected function getDefaultErrorMessages() : array {
		return [
			"requiredButMissing" => "Please confirm the action",
		] + parent::getDefaultErrorMessages();
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
	 * @return array Properties for the created field element
	 */
	public function getProperties() : array {
		return [
			"formDistinguisher" => $this->getForm()->getDistinguisher(),
			"distinguisher" => "static-html-".hash("sha256", $this->getStaticHtml()),
			"prompt" => $this->getPrompt(),
			"errors" => $this->getErrorMessages(),
		];
	}

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		return $this->getWebComponentHtml();
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * None for this field, as it is just static
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		return 'if (!document.getElementById('.json_encode($this->getId()).').verify()) { return; }';
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * None for this field, as it is just a static thing, but here for consistency
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', document.getElementById('.json_encode($this->getId()).').getAggregationValue());';
	}

	/**
	 * Nothing to be done, as it is just a static thing, but here for consistency
	 */
	public function checkServerSide(?array &$requestArr=null) : void {
		return;
	}
}
