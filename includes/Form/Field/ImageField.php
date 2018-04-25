<?php

namespace Catalyst\Form\Field;

use \Catalyst\API\Response;
use \Catalyst\Form\{FileUpload,Form};
use \Catalyst\Images\MIMEType;
use \Catalyst\HTTPCode;
use \Catalyst\Page\UniversalFunctions;

/**
 * Represents a singular image field
 */
class ImageField extends AbstractField {
	use LabelTrait, SupportsAutocompleteAttributeTrait;
	/**
	 * Maximum size of the image, in bytes
	 * 
	 * @var int
	 */
	protected $maxSize = 10485760; // 10 MB
	/**
	 * Error code to send if the image is too large
	 * 
	 * @var int
	 */
	protected $tooLargeErrorCode = -1;

	const PATH_INPUT_SUFFIX = "-psuedo-path";

	/**
	 * Get the current max size, in bytes
	 * 
	 * @return int Max size in bytes
	 */
	public function getMaxSize() : int {
		return $this->maxSize;
	}

	/**
	 * Set the maximum size (in bytes)
	 * 
	 * @param int $maxSize New maximum size (bytes)
	 */
	public function setMaxSize(int $maxSize) : void {
		$this->maxSize = $maxSize;
	}

	/**
	 * Get the max size in a human format (10M, etc)
	 * 
	 * This will be calculated with 1024B/kB and the like, regardless of iB or B
	 * 
	 * @return string Current max size (humanized)
	 */
	public function getMaxHumanSize() : string {
		return UniversalFunctions::humanize($this->getMaxSize());
	}

	/**
	 * Set the maximum size from a human format
	 * 
	 * This will be calculated with 1024B/kB and the like, regardless of B/iB
	 * 
	 * @param string $maxSize New humanized max size
	 */
	public function setMaxHumanSize(string $maxSize) : void {
		$this->setMaxSize(UniversalFunctions::dehumanize($maxSize));
	}

	/**
	 * Get the error code for when the image is too big
	 * 
	 * @return int The error code sent when the image is too big
	 */
	public function getTooLargeErrorCode() : int {
		return $this->tooLargeErrorCode;
	}

	/**
	 * Set the error code for when the image is too big
	 * 
	 * @param int $tooLargeErrorCode The error code to set the too big code to
	 */
	public function setTooLargeErrorCode(int $tooLargeErrorCode) : void {
		$this->tooLargeErrorCode = $tooLargeErrorCode;
	}

	/**
	 * Throw an error for too large an image
	 * 
	 * Public as this is called by Form
	 */
	public function throwTooLargeError() : void {
		HTTPCode::set(400);
		Response::sendErrorResponse($this->getTooLargeErrorCode(), $this->getErrorMessage($this->getTooLargeErrorCode()));
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
		$str .= ' type="file"';
		$str .= ' autocomplete="'.htmlspecialchars($this->getAutocompleteAttribute()).'"';
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

		$str .= $this->getLabelHtml();
		
		$str .= '</div>';
		
		$str .= '</div>';
		
		$str .= '</div>';

		return $str;
	}

	/**
	 * Full JS validation code, including if statement and all
	 * 
	 * @return string The JS to validate the field
	 */
	public function getJsValidator() : string {
		$str = '';
		if ($this->isRequired()) {
			$str .= 'if (';
			$str .= '$('.json_encode("#".$this->getId()).')[0].files.length === 0';
			$str .= ') {';
			$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - field is required, but empty").', true);';
			$str .= 'markInputInvalid('.json_encode('#'.$this->getId().self::PATH_INPUT_SUFFIX).', '.json_encode($this->getErrorMessage($this->getMissingErrorCode())).');';
			$str .= Form::CANCEL_SUBMISSION_JS;
			$str .= '}';
		}

		$str .= 'if (';
		$str .= '$('.json_encode("#".$this->getId()).')[0].files.length !== 0';
		$str .= ') {';

		$str .= 'for (var i=0;i<$('.json_encode("#".$this->getId()).')[0].files.length;i++) {';

		$str .= 'if (';
		$str .= '$('.json_encode("#".$this->getId()).')[0].files[i].size >= '.json_encode($this->getMaxSize());
		$str .= ') {';
		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - file ").'+i+'.json_encode(" is too large").', true);';
		$str .= 'markInputInvalid('.json_encode('#'.$this->getId().self::PATH_INPUT_SUFFIX).', '.json_encode($this->getErrorMessage($this->getTooLargeErrorCode())).');';
		$str .= Form::CANCEL_SUBMISSION_JS;
		$str .= '}';

		$str .= 'if (';
		$str .= '!'.json_encode(MIMEType::getMimeTypes()).'.includes($('.json_encode("#".$this->getId()).')[0].files[i].type)';
		$str .= ') {';
		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - file ").'+i+'.json_encode(" is not a supported type (").'+$('.json_encode("#".$this->getId()).')[0].files[i].type+'.json_encode(")").', true);';
		$str .= 'markInputInvalid('.json_encode('#'.$this->getId().self::PATH_INPUT_SUFFIX).', '.json_encode($this->getErrorMessage($this->getInvalidErrorCode())).');';
		$str .= Form::CANCEL_SUBMISSION_JS;
		$str .= '}';

		$str .= '}';

		$str .= '}';

		return $str;
	}

	/**
	 * Return JS code to store the field's value in $formDataName
	 * 
	 * @param string $formDataName The name of the FormData variable
	 * @return string Code to use to store field in $formDataName
	 */
	public function getJsAggregator(string $formDataName) : string {
		return $formDataName.'.append('.json_encode($this->getDistinguisher()).', $('.json_encode("#".$this->getId()).')[0].files[0]);';
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
			if (!isset($_FILES[$this->getDistinguisher()])) {
				$this->throwMissingError();
			}
		} else {
			if (!isset($_FILES[$this->getDistinguisher()])) {
				return;
			}
		}
		if ($_FILES[$this->getDistinguisher()]["error"] !== 0 && $this->isRequired()) { // not uploaded
			$this->throwMissingError();
		}
		if ($_FILES[$this->getDistinguisher()]["error"] === 1) {
			$this->throwTooLargeError();
		}
		if ($_FILES[$this->getDistinguisher()]["error"] !== 0 && $_FILES[$this->getDistinguisher()]["error"] !== 4) { // error
			$this->throwInvalidError();
		}
		if ($_FILES[$this->getDistinguisher()]["error"] === 4) {
			return;
		}
		if (!in_array(FileUpload::getMime($_FILES[$this->getDistinguisher()]["tmp_name"]), MIMEType::getMimeTypes())) {
			$this->throwInvalidError();
		}
		if ($_FILES[$this->getDistinguisher()]["size"] > $this->getMaxSize()) {
			$this->throwTooLargeError();
		}
	}

	/**
	 * Get the default autocomplete attribute value
	 *
	 * @return string
	 */
	public static function getDefaultAutocompleteAttribute() : string {
		return AutocompleteValues::PHOTO;
	}
}
