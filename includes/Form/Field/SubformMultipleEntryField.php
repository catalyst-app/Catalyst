<?php

namespace Catalyst\Form\Field;

use \Catalyst\Form\Form;

/**
 * Allows for a set of fields to be used in order to add to a collection
 * Use cases: commission type stages, payemnt options
 */
class SubformMultipleEntryField extends AbstractField {
	use LabelTrait;

	/**
	 * Class name given to remove buttons
	 */
	public const REMOVE_BUTTON_CLASS = "subform-user-created-entry-remove-btn";

	/**
	 * Array of fields
	 * @var AbstractField[]
	 */
	protected $fields = [];
	/**
	 * String containing the HTML which will display each entry through the subform
	 *
	 * Each AbstractField as defined in $fields which will be inserted through string-substitution:
	 * "{distinguisher}" to htmlspecialchars($value)
	 * 
	 * @var string
	 */
	protected $displayHtml = '';
	/**
	 * Classes added to the div.btn, potentially to be used for grid layouts
	 * @var string
	 */
	protected $additionButtonClasses = '';

	/**
	 * Get the fields used by the SubformMultipleEntryField
	 * 
	 * @return AbstractField[]
	 */
	public function getFields() : array {
		return $this->fields;
	}

	/**
	 * Add a field to the list of ones which will be used
	 * 
	 * @param AbstractField $field
	 */
	public function addField(AbstractField $field) : void {
		$this->fields[] = $field;
	}

	/**
	 * Empty the array of fields used by the SubformMultipleEntryField
	 */
	public function emptyFields() : void {
		$this->fields = [];
	}

	/**
	 * Get the HTML used to display each item
	 * 
	 * @return string
	 */
	public function getDisplayHtml() : string {
		return $this->displayHtml;
	}

	/**
	 * Set the display html to a new value
	 *
	 * @see $this->displayHtml how this HTML should be formatted
	 * @param string $html
	 */
	public function setDisplayHtml(string $html) : void {
		$this->displayHtml = $html;
	}

	/**
	 * Get the classes added to the addition button
	 * 
	 * @return string
	 */
	public function getAdditionButtonClasses() : string {
		return $this->additionButtonClasses;
	}

	/**
	 * Set the classes added to the addition button
	 *
	 * @param string $additionButtonClasses
	 */
	public function setAdditionButtonClasses(string $additionButtonClasses) : void {
		$this->additionButtonClasses = $additionButtonClasses;
	}

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';

		return $str;
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		$str = '';
		
		return $str;
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return '';
		// return $formDataName.'.append('.json_encode($this->getDistinguisher()).', $('.json_encode("#".$this->getId()).').val());';
	}

	/**
	 * Return JS code which should be added in the main onload closure
	 * 
	 * @return string
	 */
	public function getJsOnload() : string {
		return '';
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * No parameters as the fields have concrete names, and no return as appropriate errors are returned
	 */
	public function checkServerSide() : void {
		if (!isset($_REQUEST[$this->getDistinguisher()])) {
			$this->throwMissingError();
		}
		if ($this->isRequired()) {
			if (empty($_REQUEST[$this->getDistinguisher()])) {
				$this->throwMissingError();
			}
		}
	}
}
