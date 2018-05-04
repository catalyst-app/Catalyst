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
	 * Class name given to items added
	 */
	public const ENTRY_ITEM = "subform-user-created-entry-item";

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

		$str .= '<p';
		$str .= ' class="no-bottom-margin col s12">';

		$str .= '<strong>';
		$str .= htmlspecialchars($this->getLabel());
		$str .= '</strong>';

		$str .= '</p>';

		$str .= '<div';
		$str .= ' class="subform-entry-container col s12"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';
		$str .= '>';

		$str .= '</div>';

		$str .= '<div';
		$str .= ' id="'.htmlspecialchars($this->getId()).'-subform"';
		$str .= ' class="subform-form-container"';
		$str .= '>';

		foreach ($this->getFields() as $field) {
			$field->setForm($this->getForm());
			$str .= $field->getHtml();
		}

		$str .= '<button';
		$str .= ' type="button"';
		$str .= ' class="btn subform-add-btn waves-effect waves-light '.htmlspecialchars($this->getAdditionButtonClasses()).'"';
		$str .= '>';

		$str .= "add";
		
		$str .= '</button>';

		$str .= '</div>';

		return $str;
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		return '';
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		$str = '';

		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - aggregating ").'+$('.json_encode('#'.$this->getId()).').find('.json_encode(".".self::ENTRY_ITEM).').length+'.json_encode(" entries of data").');';

		$str .= 'for (var entry of $('.json_encode('#'.$this->getId()).').find('.json_encode(".".self::ENTRY_ITEM).')) {';

		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().'[]').', $(entry).attr("data-data"));';

		$str .= '}';

		return $str;
	}

	/**
	 * Return JS code which should be added in the main onload closure
	 * 
	 * @return string
	 */
	public function getJsOnload() : string {
		$str = '';

		$str .= '$(document).on("keypress", '.json_encode('#'.$this->getId().'-subform').', function(e) {';
		$str .= 'if (e.keyCode == 13) {';
		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - enter recieved, handling and suppressing").');';
		$str .= 'e.preventDefault && e.preventDefault();';
		$str .= 'e.stopPropogation && e.stopPropogation();';
		$str .= 'e.stopImmediatePropogation && e.stopImmediatePropogation();';
		$str .= '$('.json_encode('#'.$this->getId().'-subform'.' button').').trigger("click");';
		$str .= '}';
		$str .= '});';

		$str .= '$(document).on("click", '.json_encode('#'.$this->getId().'-subform'.' button').', function(e) {';
		
		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - subform addition button clicked, verifying fields").');';

		foreach ($this->getFields() as $field) {
			$field->setForm($this->getForm());
			$str .= $field->getJsValidator();
		}

		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - aggregating fields into psuedoAggregator").');';

		$str .= 'var psuedoAggregator = new FormData();';
		foreach ($this->getFields() as $field) {
			$str .= $field->getJsAggregator("psuedoAggregator");
		}

		$str .= 'var htmlToAdd = '.json_encode($this->getDisplayHtml()).';';
		
		$str .= 'var serializedData = {};';

		$str .= 'for (var pair of psuedoAggregator.entries()) {';
		$str .= 'serializedData[pair[0]] = pair[1];';
		$str .= 'htmlToAdd = htmlToAdd.replace("{"+pair[0]+"}", $("<div></div>").text(pair[1]).html());';
		$str .= '}';

		$str .= 'htmlToAdd = $(htmlToAdd);';
		$str .= 'htmlToAdd.attr("data-data", JSON.stringify(serializedData));';

		$str .= '$('.json_encode("#".$this->getId()).').append(htmlToAdd);';

		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - appending generated HTML").');';
		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - rendering markdown").');';

		$str .= '$('.json_encode("#".$this->getId()).').find(".raw-markdown").each(function() {renderMarkdownArea(this);});';

		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - reseting form").');';

		$str .= '$('.json_encode('#'.$this->getId().'-subform').').find(":input").val("");';

		$str .= '});';

		return $str;
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * @param array $requestArr Array to find the form data in
	 */
	public function checkServerSide(?array &$requestArr=null) : void {
		if (is_null($requestArr)) {
			if ($this->getMethod() == Form::POST) {
				$requestArr = $_POST;
			} else {
				$requestArr = $_GET;
			}
		}
		if ($this->isRequired()) {
			if (!array_key_exists($this->getDistinguisher(), $requestArr)) {
				$this->throwMissingError();
			}
		} else {
			if (!array_key_exists($this->getDistinguisher(), $requestArr)) {
				return;
			}
		}
		if (!is_array($requestArr[$this->getDistinguisher()])) {
			$this->throwInvalidError();
		}
		foreach ($requestArr[$this->getDistinguisher()] as &$entry) {
			if (json_decode($entry) === false) {
				$this->throwInvalidError();
			}
			$entry = json_decode($entry, true);
			foreach ($this->getFields() as $field) {
				$field->setForm($this->getForm());
				$field->checkServerSide($entry);
			}
		}
	}
}
