<?php

namespace Catalyst\Form\Field;

use \Catalyst\API\Response;
use \Catalyst\Form\{FileUpload,Form};
use \Catalyst\Images\MIMEType;
use \Catalyst\HTTPCode;
use \Catalyst\Page\UniversalFunctions;

/**
 * Represents an image field with multiple images, captions, nsfw, and extra artist/owner info
 */
class MultipleImageWithNsfwCaptionAndInfoField extends MultipleImageField {
	public const NSFW_CHECKBOX_ID_SUFFIX = '-nsfw';
	public const NSFW_CLASS = 'image-extra-info-nsfw';
	public const CAPTION_ID_SUFFIX = '-caption';
	public const CAPTION_CLASS = 'image-extra-info-caption';
	public const INFO_ID_SUFFIX = '-info';
	public const INFO_CLASS = 'image-extra-info-info';

	public const ROW_ID_SUFFIX = '-data-row';
	public const ROW_CLASS = 'image-extra-info-row';
	
	public const EL_ID_SUFFIX_EXPR = '+"-"+file.lastModified+"-"+file.size+"-"+file.name';

	public const INPUT_CLASS = 'has-nsfw-caption-info';

	/**
	 * What is shown to the user as this field's label
	 */
	protected $infoLabel = '';

	/**
	 * @return string
	 */
	public function getInfoLabel() : string {
		return $this->infoLabel;
	}

	/**
	 * @param string $infoLabel
	 */
	public function setInfoLabel(string $infoLabel) : void {
		$this->infoLabel = $infoLabel;
	}

	/**
	 * Return the field's HTML input
	 * 
	 * @return string The HTML to display
	 */
	public function getHtml() : string {
		$str = '';
		$str .= '<div';
		$str .= ' class="file-input-field col s12">';

		$str .= '<div';
		$str .= ' class="btn file-button">';

		$str .= '<span>FILE</span>';

		$str .= '<input';
		$str .= ' class="'.self::INPUT_CLASS.'"';
		$str .= ' type="file"';
		$str .= ' multiple="multiple"';
		$str .= ' data-extra-info-prefix="'.htmlspecialchars($this->getId()).'"';
		$str .= ' data-extra-info-name="'.htmlspecialchars($this->getInfoLabel()).'"';
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
		
		$str .= 'var file = $('.json_encode("#".$this->getId().self::NSFW_CHECKBOX_ID_SUFFIX).')[0].files[i];';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().'[]').', file);';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().self::NSFW_CHECKBOX_ID_SUFFIX.'[]').', $('.json_encode("#".$this->getId().self::NSFW_CHECKBOX_ID_SUFFIX).self::EL_ID_SUFFIX_EXPR.').is(":checked"));';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().self::CAPTION_ID_SUFFIX.'[]').', $('.json_encode("#".$this->getId().self::CAPTION_ID_SUFFIX).self::EL_ID_SUFFIX_EXPR.').val());';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().self::INFO_ID_SUFFIX.'[]').', $('.json_encode("#".$this->getId().self::INFO_ID_SUFFIX).self::EL_ID_SUFFIX_EXPR.').val());';
		
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
		if (!array_key_exists($this->getDistinguisher().self::NSFW_CHECKBOX_ID_SUFFIX, $_REQUEST) || !is_array($_REQUEST[$this->getDistinguisher().self::NSFW_CHECKBOX_ID_SUFFIX])) {
			$_POST[$this->getDistinguisher().self::NSFW_CHECKBOX_ID_SUFFIX] = 
			$_GET[$this->getDistinguisher().self::NSFW_CHECKBOX_ID_SUFFIX] = 
			$_REQUEST[$this->getDistinguisher().self::NSFW_CHECKBOX_ID_SUFFIX] = 
				[];
		}
		if (!array_key_exists($this->getDistinguisher().self::CAPTION_ID_SUFFIX, $_REQUEST) || !is_array($_REQUEST[$this->getDistinguisher().self::CAPTION_ID_SUFFIX])) {
			$_POST[$this->getDistinguisher().self::CAPTION_ID_SUFFIX] = 
			$_GET[$this->getDistinguisher().self::CAPTION_ID_SUFFIX] = 
			$_REQUEST[$this->getDistinguisher().self::CAPTION_ID_SUFFIX] = 
				[];
		}
		if (!array_key_exists($this->getDistinguisher().self::INFO_ID_SUFFIX, $_REQUEST) || !is_array($_REQUEST[$this->getDistinguisher().self::INFO_ID_SUFFIX])) {
			$_POST[$this->getDistinguisher().self::INFO_ID_SUFFIX] = 
			$_GET[$this->getDistinguisher().self::INFO_ID_SUFFIX] = 
			$_REQUEST[$this->getDistinguisher().self::INFO_ID_SUFFIX] = 
				[];
		}
		for ($i=0; $i < count($_FILES[$this->getDistinguisher()]["name"]); $i++) { 
			if ($_FILES[$this->getDistinguisher()]["error"][$i] !== 0 && $this->isRequired()) { // not uploaded
				$this->throwMissingError();
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
			if (!array_key_exists($i, $_REQUEST[$this->getDistinguisher().self::NSFW_CHECKBOX_ID_SUFFIX])) {
				$_REQUEST[$this->getDistinguisher().self::NSFW_CHECKBOX_ID_SUFFIX][$i] = 'false';
			}
			if (!array_key_exists($i, $_REQUEST[$this->getDistinguisher().self::CAPTION_ID_SUFFIX])) {
				$_REQUEST[$this->getDistinguisher().self::CAPTION_ID_SUFFIX][$i] = '';
			}
			if (!array_key_exists($i, $_REQUEST[$this->getDistinguisher().self::INFO_ID_SUFFIX])) {
				$_REQUEST[$this->getDistinguisher().self::INFO_ID_SUFFIX][$i] = '';
			}
		}
	}
}
