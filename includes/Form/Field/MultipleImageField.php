<?php

namespace Catalyst\Form\Field;

use \Catalyst\API\Response;
use \Catalyst\Form\{FileUpload,Form};
use \Catalyst\Images\MIMEType;
use \Catalyst\HTTPCode;
use \Catalyst\Page\UniversalFunctions;

/**
 * Represents an image field with multiple images
 */
class MultipleImageField extends ImageField {
	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';

		$str .= '<p';
		$str .= ' class="col s12 grey-text no-top-margin"';
		$str .= '>';

		$str .= "You can upload multiple files here.  Just hold ⌘ or ctrl while selecting your files!";

		$str .= '</p>';
		
		$str .= '<div';
		$str .= ' class="file-input-field col s12">';

		$str .= '<div';
		$str .= ' class="btn file-button">';

		$str .= '<span>FILE</span>';

		$str .= '<input';
		$str .= ' type="file"';
		$str .= ' multiple="multiple"';
		$str .= ' id="'.htmlspecialchars($this->getId()).'"';
		$str .= ' accept="image/*"'; // we must use image/* for most phone compatability
		$str .= '>';

		$str .= '</div>';

		$str .= '<div';
		$str .= ' class="file-input-path-wrapper">';

		$str .= '<div';
		$str .= ' class="input-field col s12">';

		$str .= '<input';
		$str .= ' readonly="readonly"';
		$str .= ' type="text"';
		$str .= ' id="'.htmlspecialchars($this->getId()).self::PATH_INPUT_SUFFIX.'"';
		$str .= ' class="file-input-path"';
		$str .= ' data-required="'.($this->isRequired() ? 'yes' : 'no').'"';
		$str .= '>';

		$str .= '<label';
		$str .= ' for="'.htmlspecialchars($this->getId()).self::PATH_INPUT_SUFFIX.'"';
		$str .= ' data-error="'.htmlspecialchars($this->getErrorMessage($this->getInvalidErrorCode())).'"';
		$str .= '>';
		$str .= htmlspecialchars($this->getLabel());
		if ($this->isRequired()) {
			$str .= '<span';
			$str .= ' class="red-text">';
			$str .= '&nbsp;*';
			$str .= '</span>';
		}
		$str .= '</label>';
		
		$str .= '</div>';
		
		$str .= '</div>';
		
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

		$str .= 'for (var i=0; i<$('.json_encode("#".$this->getId()).')[0].files.length; i++) {';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().'[]').', $('.json_encode("#".$this->getId()).')[0].files[i]);';
		$str .= '}';

		return $str;
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * No parameters as the fields have concrete names, and no return as appropriate errors are returned
	 */
	public function checkServerSide() : void {
		if ($this->isRequired()) {
			if (!isset($_FILES[$this->getDistinguisher()])) {
				$this->throwMissingError();
			}
		} else {
			if (!isset($_FILES[$this->getDistinguisher()])) {
				return;
			}
		}
		for ($i=0; $i < count($_FILES[$this->getDistinguisher()]["name"]); $i++) { 
			if ($_FILES[$this->getDistinguisher()]["error"][$i] !== 0 && $this->isRequired()) { // not uploaded
				$this->throwMissingError();
			}
			if ($_FILES[$this->getDistinguisher()]["error"][$i] === 1) {
				$this->throwTooLargeError();
			}
			if ($_FILES[$this->getDistinguisher()]["error"][$i] !== 0 && $_FILES[$this->getDistinguisher()]["error"][$i] !== 4) { // error
				$this->throwInvalidError();
			}
			if ($_FILES[$this->getDistinguisher()]["error"][$i] === 4) {
				return;
			}
			if (!in_array(FileUpload::getMime($_FILES[$this->getDistinguisher()]["tmp_name"][$i]), MIMEType::getMimeTypes())) {
				$this->throwInvalidError();
			}
			if ($_FILES[$this->getDistinguisher()]["size"][$i] > $this->getMaxSize()) {
				$this->throwTooLargeError();
			}
		}
	}
}
