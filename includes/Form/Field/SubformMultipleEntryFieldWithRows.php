<?php

namespace Catalyst\Form\Field;

use \Catalyst\Controller;
use \Catalyst\Form\Form;

/**
 * Allows for a set of fields to be used in order to add to a collection
 * Use cases: commission type stages, payemnt options
 */
class SubformMultipleEntryFieldWithRows extends SubformMultipleEntryField {
	/**
	 * Construct a SubformMultipleEntryFieldWithRows
	 *
	 * Currently throws an error because the JS isn't here to support this yet.
	 */
	public function __construct() {
		if (!Controller::isDevelMode()) {
			trigger_error("SubformMultipleEntryFieldWithRows isn't a thing yet", E_USER_ERROR);
		}
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

		$str .= '<div';
		$str .= ' class="subform-entry-sub-container col s12"';
		$str .= '>';

		$str .= '</div>';

		$str .= '</div>';

		$str .= '<button';
		$str .= ' type="button"';
		$str .= ' class="btn waves-effect waves-light add-sub-container-field-btn"';
		$str .= '>';

		$str .= "add row";
		
		$str .= '</button>';

		$str .= '<div';
		$str .= ' id="'.htmlspecialchars($this->getId()).'-subform"';
		$str .= '>';

		foreach ($this->getFields() as $field) {
			$field->setForm($this->getForm());
			$str .= $field->getHtml();
		}

		$str .= '<button';
		$str .= ' type="button"';
		$str .= ' class="btn waves-effect waves-light '.htmlspecialchars($this->getAdditionButtonClasses()).'"';
		$str .= '>';

		$str .= "add";
		
		$str .= '</button>';

		$str .= '</div>';

		return $str;
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		$str = '';

		$str .= 'for (var entry of $('.json_encode('#'.$this->getId()).').find('.json_encode(".".self::ENTRY_ITEM).')) {';

		$str .= 'var itemData = JSON.stringify($(entry).attr("data-data"));';
		$str .= 'itemData["row"] = $(entry).index();';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().'[]').', JSON.parse(itemData));';

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
		$str .= 'e.preventDefault && e.preventDefault();';
		$str .= '$('.json_encode('#'.$this->getId().'-subform'.' button').').trigger("click");';
		$str .= '}';
		$str .= '});';

		$str .= '$(document).on("click", '.json_encode('#'.$this->getId().'-subform'.' button').', function(e) {';
		
		foreach ($this->getFields() as $field) {
			$field->setForm($this->getForm());
			$str .= $field->getJsValidator();
		}

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

		$str .= '$('.json_encode("#".$this->getId()." .subform-entry-sub-container:last").').append(htmlToAdd);';

		$str .= '$('.json_encode("#".$this->getId()).').find(".raw-markdown").each(function() {renderMarkdownArea(this);});';

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
			$requestArr = &$_REQUEST;
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
			if (!is_numeric($entry["row"])) {
				$this->throwInvalidError();
			}
			if ($entry["row"] < 0) {
				$this->throwInvalidError();
			}
			foreach ($this->getFields() as $field) {
				$field->setForm($this->getForm());
				$field->checkServerSide($entry);
			}
		}
	}
}
