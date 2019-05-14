<?php

namespace Catalyst\Form\Field;

use \Catalyst\API\Response;
use \Catalyst\Form\Form;
use \Catalyst\Images\MIMEType;
use \Catalyst\HTTPCode;
use \Catalyst\Page\UniversalFunctions;
use \Exception;

/**
 * Represents an image field with multiple images, captions, nsfw, and extra artist/owner info
 */
class MultipleImageWithNsfwCaptionAndInfoField extends MultipleImageField {
	use SupportsPrefilledValueTrait;

	public const NSFW_CHECKBOX_ID_SUFFIX = '-nsfw';
	public const NSFW_CLASS = 'image-extra-info-nsfw';
	public const CAPTION_ID_SUFFIX = '-caption';
	public const CAPTION_CLASS = 'image-extra-info-caption';
	public const INFO_ID_SUFFIX = '-info';
	public const INFO_CLASS = 'image-extra-info-info';

	public const ROW_ID_SUFFIX = '-data-row';
	public const ROW_CLASS = 'image-extra-info-row';

	public const ROW_CONTAINER_ID_SUFFIX = '-info-row-container';
	public const ROW_CONTAINER_CLASS = 'info-row-container';

	public const EL_ID_SUFFIX_EXPR = '+"-"+file.lastModified+"-"+file.size+"-"+file.name.replace(/[^a-zA-Z0-9-]/g, "")';

	public const INPUT_CLASS = 'has-nsfw-caption-info';

	public const MODAL_ID_SUFFIX = '-modal';
	public const IMAGE_REARRANGER_ID_SUFFIX = '-rearranger';

	/**
	 * Helper text for the field's label
	 * @var string
	 */
	protected $helperText = "You can upload multiple files here.  Just hold ⌘ or ctrl while selecting your files!";

	/**
	 * What is shown to the user as this field's label
	 * @var string
	 */
	protected $infoLabel = '';
	/**
	 * What is used to delimit the caption vs info in the image's caption
	 * @var string
	 */
	protected $infoCaptionDelimiter = '';

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
	 * @return string
	 */
	public function getInfoCaptionDelimiter() : string {
		return $this->infoCaptionDelimiter;
	}

	/**
	 * @param string $infoCaptionDelimiter
	 */
	public function setInfoCaptionDelimiter(string $infoCaptionDelimiter) : void {
		$this->infoCaptionDelimiter = $infoCaptionDelimiter;
	}

	/**
	 * Get the extra fields for the input, based on provided parameters
	 */
	public static function getExtraFields(string $key, array $request) : array {
		if (!array_key_exists($key."-keys", $request)) {
			return [];
		}
		if (count($request[$key.self::NSFW_CHECKBOX_ID_SUFFIX]) != count($request[$key.self::CAPTION_ID_SUFFIX]) ||
			count($request[$key.self::INFO_ID_SUFFIX]) != count($request[$key."-keys"]) ||
			count($request[$key."-keys"]) != count($request[$key."-sort"])) {
			HTTPCode::set(400);
			Response::sendErrorResponse(99999, "Invalid image information");
		}

		$result = [];

		for ($i=0; $i < count($request[$key."-keys"]); $i++) {
			$result[$request[$key."-keys"][$i]] = [
				"nsfw" => $request[$key.self::NSFW_CHECKBOX_ID_SUFFIX][$i] == 'true',
				"caption" => ltrim($request[$key.self::CAPTION_ID_SUFFIX][$i]),
				"sort" => (int)$request[$key."-sort"][$i],
				"info" => $request[$key.self::INFO_ID_SUFFIX][$i]
			];
		}

		if (array_key_exists($key, $_FILES)) {
			foreach ($_FILES[$key]["name"] as $filename) {
				if (!array_key_exists($filename, $result)) {
					HTTPCode::set(400);
					Response::sendErrorResponse(99999, "Image information is incorrectly associated with the uploaded images");
				}
			}
		}

		return $result;
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
		$str .= ' class="'.htmlspecialchars(self::INPUT_CLASS).'"';
		$str .= ' type="file"';
		$str .= ' multiple="multiple"';
		$str .= ' autocomplete="'.htmlspecialchars($this->getAutocompleteAttribute()).'"';
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
		$str .= ' id="'.htmlspecialchars($this->getId().self::PATH_INPUT_SUFFIX).'"';
		$str .= ' class="file-input-path"';
		$str .= ' data-required="'.($this->isRequired() ? 'yes' : 'no').'"';
		$str .= '>';

		$str .= $this->getLabelHtml();
		
		$str .= '</div>';
		
		$str .= '</div>';
		
		$str .= '</div>';

		$str .= '<div';
		$str .= ' class="'.htmlspecialchars(self::ROW_CONTAINER_CLASS).' col s12"';
		$str .= ' id="'.htmlspecialchars($this->getId().self::ROW_CONTAINER_ID_SUFFIX).'"';
		$str .= '>';

		if ($this->isFieldPrefilled() && count($this->getPrefilledValue())) {
			// add the reorder button
			$str .= '<div';
			$str .= ' class="col s12"';
			$str .= '>';

			$str .= '<div';
			$str .= ' class="';
			$str .= 'btn ';
			$str .= 'right ';
			$str .= 'modal-trigger';
			$str .= '"';
			$str .= ' id="reorder-modal-button-'.htmlspecialchars($this->getId()).'"';
			$str .= ' data-target="'.htmlspecialchars($this->getId().self::MODAL_ID_SUFFIX).'"';
			$str .= '>';

			$str .= 'reorder';

			$str .= '</div>';

			$str .= '</div>';

			// add the actual items
			foreach ($this->getPrefilledValue() as $image) {
				$str .= '<div';
				$str .= ' class="';
				$str .= 'pre-existing ';
				$str .= 'row ';
				$str .= htmlspecialchars(self::ROW_CLASS).'"';
				$str .= ' data-internal-filename="'.htmlspecialchars($image->getPath()).'"';
				$str .= ' id="'.htmlspecialchars($this->getId()."-pre-existing-".self::ROW_ID_SUFFIX.$image->getToken()."-".$image->getPath()).'"';
				$str .= ' data-input="'.htmlspecialchars($this->getId()).'"';
				$str .= '>';

				$str .= '<div';
				$str .= ' class="';
				$str .= 'center ';
				$str .= 'force-square-contents ';
				$str .= 'col s4 offset-s4 m3 l2"';
				$str .= '>';

				$str .= $image->getStrictCircleHtml();

				$str .= '</div>';

				$str .= '<div';
				$str .= ' class="';
				$str .= 'center-on-small-only ';
				$str .= 'left-align ';
				$str .= 'col s12 m9 l10"';
				$str .= '>';

				$str .= '<h4';
				$str .= ' class="col s12"';
				$str .= '>';

				$str .= 'Existing Image';

				$str .= '<i class="image-row-remove-icon material-icons clickable right">';
				$str .= 'clear';
				$str .= '</i>';

				$str .= '</h4>';

				$str .= '<p';
				$str .= ' class="col s12"';
				$str .= '>';

				$str .= '<label';
				$str .= ' for="'.htmlspecialchars($this->getId()."-pre-existing-".self::NSFW_CHECKBOX_ID_SUFFIX.$image->getToken()."-".$image->getPath()).'"';
				$str .= '>';

				$str .= '<input';
				$str .= ' class="filled-in '.htmlspecialchars(self::NSFW_CLASS).'"';
				$str .= ' type="checkbox"';
				$str .= ' id="'.htmlspecialchars($this->getId()."-pre-existing-".self::NSFW_CHECKBOX_ID_SUFFIX.$image->getToken()."-".$image->getPath()).'"';
				if ($image->isNsfw()) {
					$str .= ' checked="checked"';
				}
				$str .= '>';

				$str .= '<span>';
				$str .= 'This image is mature or explicit';
				$str .= '</span>';

				$str .= '</p>';

				$str .= '<div';
				$str .= ' class="input-field col s12 m6"';
				$str .= '>';

				$str .= '<input';
				$str .= ' class="'.htmlspecialchars(self::CAPTION_CLASS).'"';
				$str .= ' type="text"';
				$str .= ' maxlength="255"';
				$str .= ' id="'.htmlspecialchars($this->getId()."-pre-existing-".self::CAPTION_ID_SUFFIX.$image->getToken()."-".$image->getPath()).'"';
				$captionWithoutInfo = trim(explode($this->getInfoCaptionDelimiter(), " ".$image->getCaption())[0]."");
				$str .= ' value="'.htmlspecialchars($captionWithoutInfo).'"';
				$str .= '>';

				$str .= '<label';
				$str .= ' for="'.htmlspecialchars($this->getId()."-pre-existing-".self::CAPTION_ID_SUFFIX.$image->getToken()."-".$image->getPath()).'"';
				$str .= $captionWithoutInfo ? ' class="active"' : '';
				$str .= ' data-error="Caption cannot be longer than 255 characters"';
				$str .= '>';
				$str .= 'Caption';
				$str .= '</label>';

				$str .= '</div>';

				$str .= '<div';
				$str .= ' class="input-field col s12 m6"';
				$str .= '>';

				$str .= '<input';
				$str .= ' class="'.htmlspecialchars(self::INFO_CLASS).'"';
				$str .= ' type="text"';
				$str .= ' id="'.htmlspecialchars($this->getId()."-pre-existing-".self::INFO_ID_SUFFIX.$image->getToken()."-".$image->getPath()).'"';
				$exploded = explode($this->getInfoCaptionDelimiter(), " ".$image->getCaption(), 2);
				if ($exploded === false) {
					throw new Exception("Unable to explode caption");
				}
				if (count($exploded) > 1) {
					$infoStr = trim($exploded[1]);
				} else {
					$infoStr = '';
				}
				$str .= ' value="'.htmlspecialchars($infoStr).'"';
				$str .= '>';

				$str .= '<label';
				$str .= ' for="'.htmlspecialchars($this->getId()."-pre-existing-".self::INFO_ID_SUFFIX.$image->getToken()."-".$image->getPath()).'"';
				$str .= $infoStr ? ' class="active"' : '';
				$str .= '>';
				$str .= htmlspecialchars($this->getInfoLabel());
				$str .= '</label>';

				$str .= '</div>';

				$str .= '</div>';

				$str .= '</div>';
			}
		}

		$str .= '</div>';

		$str .= '<div';
		$str .= ' class="modal rearrange-image-modal"';
		$str .= ' id="'.htmlspecialchars($this->getId().self::MODAL_ID_SUFFIX).'"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="modal-content"';
		$str .= '>';

		$str .= '<h5>';
		$str .= 'Drag the images to rearrange them';
		$str .= '</h5>';

		$str .= '<div';
		$str .= ' class="image-rearranger"';
		$str .= ' id="'.htmlspecialchars($this->getId().self::IMAGE_REARRANGER_ID_SUFFIX).'"';
		$str .= '>';

		if ($this->isFieldPrefilled() && count($this->getPrefilledValue())) {
			foreach ($this->getPrefilledValue() as $image) {
				$str .= '<div';
				$str .= ' class="';
				$str .= 'center ';
				$str .= 'force-square-contents ';
				$str .= 'col s4 m2';
				$str .= '"';
				$str .= '>';

				$str .= $image->getStrictCircleHtml([], ["margin" => "1em"], [
					"data-container" => $this->getId().self::ROW_CONTAINER_ID_SUFFIX,
					"id" => $this->getId()."-pre-existing-".self::ROW_ID_SUFFIX.$image->getToken()."-".$image->getPath()."-reorder-img",
					"data-path" => $image->getPath(),
				]);

				$str .= '</div>';
			}
		}

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

		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode($this->getId()." - aggregating ").'+$('.json_encode("#".$this->getId()).')[0].files.length+'.json_encode(" images").');';

		$str .= 'for (var i=0; i<$('.json_encode("#".$this->getId()).')[0].files.length; i++) {';
		
		$str .= 'var file = $('.json_encode("#".$this->getId()).')[0].files[i];';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().'[]').', file);';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().'-keys[]').', file.name);';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().'-sort[]').', $('.json_encode('.'.self::ROW_CLASS).').index($('.json_encode("#".$this->getId().self::ROW_ID_SUFFIX).self::EL_ID_SUFFIX_EXPR.')));';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().self::NSFW_CHECKBOX_ID_SUFFIX.'[]').', $('.json_encode("#".$this->getId().self::NSFW_CHECKBOX_ID_SUFFIX).self::EL_ID_SUFFIX_EXPR.').is(":checked"));';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().self::CAPTION_ID_SUFFIX.'[]').', $('.json_encode("#".$this->getId().self::CAPTION_ID_SUFFIX).self::EL_ID_SUFFIX_EXPR.').val());';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().self::INFO_ID_SUFFIX.'[]').', $('.json_encode("#".$this->getId().self::INFO_ID_SUFFIX).self::EL_ID_SUFFIX_EXPR.').val());';
		
		$str .= '}';

		$str .= 'for (var i=0; i<$('.json_encode(".".self::ROW_CLASS.'.pre-existing').').length; i++) {';
		
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().'-keys[]').', $($('.json_encode(".".self::ROW_CLASS.'.pre-existing').')[i]).attr("data-internal-filename"));';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().'-sort[]').', $('.json_encode('.'.self::ROW_CLASS).').index($($('.json_encode(".".self::ROW_CLASS.'.pre-existing').')[i])));';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().self::NSFW_CHECKBOX_ID_SUFFIX.'[]').', $($('.json_encode(".".self::ROW_CLASS.'.pre-existing').')[i]).find('.json_encode('.'.self::NSFW_CLASS).').is(":checked"));';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().self::CAPTION_ID_SUFFIX.'[]').', $($('.json_encode(".".self::ROW_CLASS.'.pre-existing').')[i]).find('.json_encode('.'.self::CAPTION_CLASS).').val());';
		$str .= $formDataName.'.append('.json_encode($this->getDistinguisher().self::INFO_ID_SUFFIX.'[]').', $($('.json_encode(".".self::ROW_CLASS.'.pre-existing').')[i]).find('.json_encode('.'.self::INFO_CLASS).').val());';
		
		$str .= '}';

		return $str;
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * @param array $requestArr Array to find the form data in
	 */
	public function checkServerSide(?array &$requestArr=null) : void {
		if (is_null($requestArr)) {
			if ($this->getForm()->getMethod() == Form::POST) {
				$requestArr = &$_POST;
			} else {
				$requestArr = &$_GET;
			}
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
			if (!in_array(MIMEType::getFilepathMimeType($_FILES[$this->getDistinguisher()]["tmp_name"][$i]), MIMEType::getMimeTypes())) {
				$this->throwInvalidError();
			}
			if ($_FILES[$this->getDistinguisher()]["size"][$i] > $this->getMaxSize()) {
				$this->throwTooLargeError();
			}
			if (!array_key_exists($i, $requestArr[$this->getDistinguisher().self::NSFW_CHECKBOX_ID_SUFFIX])) {
				$requestArr[$this->getDistinguisher().self::NSFW_CHECKBOX_ID_SUFFIX][$i] = 'false';
			}
			if (!array_key_exists($i, $requestArr[$this->getDistinguisher().self::CAPTION_ID_SUFFIX])) {
				$requestArr[$this->getDistinguisher().self::CAPTION_ID_SUFFIX][$i] = '';
			}
			if (!array_key_exists($i, $requestArr[$this->getDistinguisher().self::INFO_ID_SUFFIX])) {
				$requestArr[$this->getDistinguisher().self::INFO_ID_SUFFIX][$i] = '';
			}
		}
	}
}
