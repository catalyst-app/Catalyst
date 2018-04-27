<?php

namespace Catalyst\Form\Field;

use \Catalyst\Controller;
use \Catalyst\Form\Form;

/**
 * Allows for a set of fields to be used in order to add to a collection
 * Use cases: commission type stages, payemnt options
 */
class SubformMultipleEntryFieldWithRows extends SubformMultipleEntryField {
	// the holy codepen: https://codepen.io/anon/pen/Wzadvm
	const PROTECTED_RIGHT_CONTAINER_CLASS = 'subform-entry-row-right-protected-container';
	const ADD_CONTAINER_BUTTON_CLASS = 'add-sub-container-field-btn';
	const REMOVE_CONTAINER_BUTTON_CLASS = 'subform-remove-container-btn';

	/**
	 * @var string
	 */
	protected $rightBarContents = '';
	/**
	 * @var callable|null
	 */
	protected $customJsAggregator = null;
	/**
	 * @var callable|null
	 */
	protected $customSeverSideCheck = null;

	/**
	 * @return string
	 */
	public function getRightBarContents() : string {
		return $this->rightBarContents;
	}

	/**
	 * Should contain the clear (remove row) button
	 * 
	 * @param string $rightBarContents
	 */
	public function setRightBarContents(string $rightBarContents) : void {
		$this->rightBarContents = $rightBarContents;
	}

	/**
	 * @return callable
	 */
	public function getCustomJsAggregator() : callable {
		if (is_null($this->customJsAggregator)) {
			return static function(string $dataArrayName, string $entry, SubformMultipleEntryFieldWithRows $form) : string {
				return '';
			};
		}
		return $this->customJsAggregator;
	}

	/**
	 * Give null to "reset"
	 * 
	 * @param null|callable $customJsAggregator
	 *   static function(string $dataArrayName, string $entry, SubformMultipleEntryFieldWithRows $form) : string
	 */
	public function setCustomJsAggregator(?callable $customJsAggregator) : void {
		$this->customJsAggregator = $customJsAggregator;
	}

	/**
	 * @return callable
	 */
	public function getCustomServerSideCheck() : callable {
		if (is_null($this->customSeverSideCheck)) {
			return function(array &$entry) : void {
				return;
			};
		}
		return $this->customSeverSideCheck;
	}

	/**
	 * Give null to "reset"
	 * 
	 * @param null|callable $customSeverSideCheck
	 *   function(array &$entry) : void
	 */
	public function setCustomServerSideCheck(?callable $customSeverSideCheck) : void {
		$this->customSeverSideCheck = $customSeverSideCheck;
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
		$str .= ' data-right-bar="'.htmlspecialchars($this->getRightBarContents()).'"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="subform-entry-sub-container col s12"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="subform-entry-sub-container-items"';
		$str .= '>';

		$str .= '</div>';

		$str .= '<div';
		$str .= ' class="'.self::PROTECTED_RIGHT_CONTAINER_CLASS.' right-align"';
		$str .= '>';

		$str .= str_replace("{uniq}", microtime(true), $this->getRightBarContents());

		$str .= '</div>';

		$str .= '</div>';

		$str .= '</div>';

		$str .= '<button';
		$str .= ' type="button"';
		$str .= ' class="btn waves-effect waves-light '.self::ADD_CONTAINER_BUTTON_CLASS.'"';
		$str .= '>';

		$str .= "add row";
		
		$str .= '</button>';

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
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		$str = '';

		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - aggregating ").'+$('.json_encode('#'.$this->getId()).').find('.json_encode(".".self::ENTRY_ITEM).').length+'.json_encode(" entries of data").');';

		$str .= 'for (var entry of $('.json_encode('#'.$this->getId()).').find('.json_encode(".".self::ENTRY_ITEM).')) {';

		$str .= 'var itemData = JSON.parse($(entry).attr("data-data"));';

		$str .= $this->getCustomJsAggregator()("itemData", "entry", $this);

		$str .= 'itemData["row"] = $(entry).closest(".subform-entry-sub-container").index();';

		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().'[]').', JSON.stringify(itemData));';

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

		$str .= '$('.json_encode("#".$this->getId()." .subform-entry-sub-container-items:last").').append(htmlToAdd);';

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
			$this->getCustomServerSideCheck()->bindTo($this, $this)($entry);
		}
	}
}
