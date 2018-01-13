<?php

namespace Redacted\Form;

class FormHTML {
	public static function testAjaxSubmissionFailed() : bool {
		return isset($_POST["form-submitted"]);
	}

	public static function getAjaxSubmissionHtml() : string {
		return implode("\n", [
			'			<div class="section">',
			'				<p class="flow-text">Your browser does not support AJAX</p>',
			'				<p class="flow-text">Please update your browser to use this site</p>',
			'			</div>',
		]);
	}

	public static function getAjaxTestInput() : string {
		return '<input type="hidden" name="ajax-form-submitted" value="true">';
	}

	public static function generateFormHeader(array $meta) : string {
		return '<form action="'.($meta["ajax"] ? "" : $meta["handler"]).'" id="'.$meta["distinguisher"].'-form" method="'.$meta["method"].'">';
	}

	public static function generateInputHTML(array $meta, array $input) : string {
		switch ($input["type"]) {
			case "custom":
				return $input["custom_html"];
			case "captcha":
				$attributes = [];
				$attributes["class"] = "g-recaptcha".(isset($input["class"]) ? " ".$input["class"] : " col s12");
				$attributes["data-sitekey"] = $input["key"];
				$attributes["id"] = $meta["distinguisher"]."-captcha";

				if (isset($input["validate"]) && $input["validate"]) {
					$attributes["data-expired-callback"] = "markCaptchaInvalid";
					$attributes["data-callback"] = "markCaptchaValid";
				}

				if (isset($input["error_text"])) {
					$attributes["data-error"] = $input["error_text"][0];
				}

				$el = "<div";

				foreach ($attributes as $key => $value) {
					$el .= " ".$key.'="'.htmlspecialchars($value).'"';
				}

				$el .= "></div>";

				if (isset($input["after_html"])) {
					$el .= $input["after_html"];
				}

				return $el;
				break;
			case "color":
				$inputAttributes = [];
				$labelAttributes = [];
				$labelAttributes["for"] = $inputAttributes["id"] = $meta["distinguisher"]."-".$input["name"];
				$labelAttributes["id"] = $meta["distinguisher"]."-".$input["name"]."-"."label";
				$inputAttributes["name"] = $meta["distinguisher"]."-".$input["name"];

				if (isset($input["classes"])) {
					if (is_array($input["classes"])) {
						$inputAttributes["class"] = implode(" ", $input["classes"]);
					} else {
						$inputAttributes["class"] = $input["classes"];
					}
				}
				
				if (isset($input["validate"]) && $input["validate"]) {
					if (isset($inputAttributes["class"]) && !empty($inputAttributes["class"])) {
						$inputAttributes["class"] .= " valid validate";
					} else {
						$inputAttributes["class"] = "valid validate";
					}
				}
				
				if (isset($input["error_text"])) {
					$labelAttributes["data-error"] = $input["error_text"][0];
				}

				if (isset($input["success_text"])) {
					$labelAttributes["data-success"] = $input["success_text"];
				}

				$wrapperClasses = (isset($input["wrapper_classes"])) ? $input["wrapper_classes"] : "col s12";

				if (isset($input["other_label_attributes"])) {
					$labelAttributes = array_merge($labelAttributes, $input["other_label_attributes"]);
				}

				if (isset($input["other_attributes"])) {
					$inputAttributes = array_merge($inputAttributes, $input["other_attributes"]);
				}

				$el  = '<div data-for="'.$inputAttributes["name"].'" class="color-field '.$wrapperClasses.'">';
				$el .= '<div class="chosen-color btn" data-for="'.$inputAttributes["name"].'" style="background-color: #'.(isset($input["default"]) ? $input["default"]["hex"] : \Catalyst\Page\Values::DEFAULT_COLOR["hex"]).'"></div>';
				$el .= '<div class="color-input-wrapper row">';
				$el .= '<div class="input-field col s12">';
				$el .= '<input type="text" readonly="readonly" id="'.$inputAttributes["name"].'" value="'.(isset($input["default"]) ? $input["default"]["hex"] : \Catalyst\Page\Values::DEFAULT_COLOR["hex"]).'"';
				foreach ($inputAttributes as $key => $value) {
					$el .= " ".$key.'="'.htmlspecialchars($value).'"';
				}
				$el .= '>';
				$el .= '<label';
				foreach ($labelAttributes as $key => $value) {
					$el .= " ".$key.'="'.htmlspecialchars($value).'"';
				}
				$el .= '>'.$input["label"].'</label>';
				$el .= '</div>';
				$el .= '</div>';
				$el .= '</div>';

				if (isset($input["after_html"])) {
					$el .= $input["after_html"];
				}
				return $el;
				break;
			case "image":
				$inputAttributes = [];
				$labelAttributes = [];
				$labelAttributes["for"] = $meta["distinguisher"]."-".$input["name"]."-psuedo-path";
				$labelAttributes["id"] = $meta["distinguisher"]."-".$input["name"]."-"."label";
				$inputAttributes["name"] = $meta["distinguisher"]."-".$input["name"];

				if (isset($input["classes"])) {
					if (is_array($input["classes"])) {
						$inputAttributes["class"] = implode(" ", $input["classes"]);
					} else {
						$inputAttributes["class"] = $input["classes"];
					}
				}

				if (isset($input["multiple"]) && $input["multiple"]) {
					$inputAttributes["multiple"] = "multiple";
				}
				
				if (isset($input["validate"]) && $input["validate"]) {
					if (isset($inputAttributes["class"]) && !empty($inputAttributes["class"])) {
						$inputAttributes["class"] .= " valid";
					} else {
						$inputAttributes["class"] = "valid";
					}
				}
				if (isset($inputAttributes["class"]) && !empty($inputAttributes["class"])) {
					$inputAttributes["class"] .= " file-input-path";
				} else {
					$inputAttributes["class"] = "file-input-path";
				}
				
				if (isset($input["error_text"])) {
					$labelAttributes["data-error"] = $input["error_text"][0];
				}

				if (isset($input["success_text"])) {
					$labelAttributes["data-success"] = $input["success_text"];
				}

				$wrapperClasses = (isset($input["wrapper_classes"])) ? $input["wrapper_classes"] : "col s12";

				if (isset($input["other_label_attributes"])) {
					$labelAttributes = array_merge($labelAttributes, $input["other_label_attributes"]);
				}

				if (isset($input["other_attributes"])) {
					$inputAttributes = array_merge($inputAttributes, $input["other_attributes"]);
				}

				$el  = '<div data-for="'.$inputAttributes["name"].'" class="file-input-field '.$wrapperClasses.'">';
				$el .= '<div class="btn file-button">';
				$el .= '<span>File</span>';
				$el .= '<input type="file" accept="image/*" id="'.$inputAttributes["name"].'" name="'.$inputAttributes["name"].'"';
				if (isset($inputAttributes["multiple"])) {
					$el .= ' multiple="multiple"';
				}
				$el .= '>';
				$el .= '</div>';
				$el .= '<div class="file-input-path-wrapper">';
				$el .= '<div class="input-field col s12">';
				$el .= '<input readonly="readonly" type="text" id="'.$labelAttributes["for"].'" class="'.$inputAttributes["class"].'">';
				$el .= '<label';
				foreach ($labelAttributes as $key => $value) {
					$el .= " ".$key.'="'.htmlspecialchars($value).'"';
				}
				$el .= '>'.$input["label"].'</label>';
				$el .= '</div>';
				$el .= '</div>';
				$el .= '</div>';

				if (isset($input["after_html"])) {
					$el .= $input["after_html"];
				}
				return $el;
				break;
			case "checkbox":
				$inputAttributes = [];
				$labelAttributes = [];
				$labelAttributes["for"] = $inputAttributes["id"] = $meta["distinguisher"]."-".$input["name"];
				$labelAttributes["id"] = $meta["distinguisher"]."-".$input["name"]."-"."label";
				$inputAttributes["name"] = $meta["distinguisher"]."-".$input["name"];

				if (isset($input["classes"])) {
					if (is_array($input["classes"])) {
						$inputAttributes["class"] = implode(" ", $input["classes"]);
					} else {
						$inputAttributes["class"] = $input["classes"];
					}
				}

				if (isset($input["default"]) && $input["default"]) {
					$inputAttributes["checked"] = "checked";
				}

				if (isset($inputAttributes["class"]) && !empty($inputAttributes["class"])) {
					$inputAttributes["class"] .= " filled-in";
				} else {
					$inputAttributes["class"] = "filled-in";
				}
				
				if (isset($input["required"]) && $input["required"]) {
					$inputAttributes["required"] = "required";
				}
				
				if (isset($input["error_text"]) && isset($input["error_text"][0])) {
					$labelAttributes["data-error"] = $input["error_text"][0];
				}

				$wrapperClasses = (isset($input["wrapper_classes"])) ? $input["wrapper_classes"] : "col s12";

				if (isset($input["other_label_attributes"])) {
					$labelAttributes = array_merge($labelAttributes, $input["other_label_attributes"]);
				}

				if (isset($input["other_attributes"])) {
					$inputAttributes = array_merge($inputAttributes, $input["other_attributes"]);
				}

				$el  = '<p class="'.$wrapperClasses.'">';
				$el .= '<input type="checkbox"';
				foreach ($inputAttributes as $key => $value) {
					$el .= " ".$key.'="'.htmlspecialchars($value).'"';
				}
				$el .= '><label';
				foreach ($labelAttributes as $key => $value) {
					$el .= " ".$key.'="'.htmlspecialchars($value).'"';
				}
				$el .= '>'.$input["label"].'</label>';
				if (isset($input["error_text"]) && isset($input["error_text"][0])) {
					$el .= '<span class="invalid-text">'.htmlspecialchars($input["error_text"][0]).'</span>';
				}
				$el .= '</p>';

				if (isset($input["after_html"])) {
					$el .= $input["after_html"];
				}
				return $el;
				break;
			case "select":
				$inputAttributes = [];
				$labelAttributes = [];
				$labelAttributes["for"] = $inputAttributes["id"] = $meta["distinguisher"]."-".$input["name"];
				$labelAttributes["id"] = $meta["distinguisher"]."-".$input["name"]."-"."label";
				$inputAttributes["name"] = $meta["distinguisher"]."-".$input["name"];

				if (isset($input["classes"])) {
					if (is_array($input["classes"])) {
						$inputAttributes["class"] = implode(" ", $input["classes"]);
					} else {
						$inputAttributes["class"] = $input["classes"];
					}
				}

				if (isset($input["required"]) && $input["required"]) {
					$inputAttributes["required"] = "required";
				}
				
				$wrapperClasses = (isset($input["wrapper_classes"])) ? $input["wrapper_classes"] : "col s12";

				if (isset($input["other_label_attributes"])) {
					$labelAttributes = array_merge($labelAttributes, $input["other_label_attributes"]);
				}

				if (isset($input["other_attributes"])) {
					$inputAttributes = array_merge($inputAttributes, $input["other_attributes"]);
				}

				$el  = '<div class="input-field '.$wrapperClasses.'">';
				$el .= '<select';
				foreach ($inputAttributes as $key => $value) {
					$el .= " ".$key.'="'.htmlspecialchars($value).'"';
				}
				$el .= '>';
				$el .= '<option value="" disabled selected>Choose an option</option>';
				foreach ($input["options"] as $value) {
					$el .= '<option value="'.htmlspecialchars($value[0]).'">'.htmlspecialchars($value[1]).'</option>';
				}
				$el .= '</select>';
				$el .= '<label';
				foreach ($labelAttributes as $key => $value) {
					$el .= " ".$key.'="'.htmlspecialchars($value).'"';
				}
				$el .= '>'.$input["label"].'</label>';
				$el .= '</div>';

				if (isset($input["after_html"])) {
					$el .= $input["after_html"];
				}
				return $el;
				break;
			case "markdown-textarea":
				$inputAttributes = [];
				$labelAttributes = [];
				$labelAttributes["for"] = $inputAttributes["id"] = $meta["distinguisher"]."-".$input["name"];
				$labelAttributes["id"] = $meta["distinguisher"]."-".$input["name"]."-"."label";
				$inputAttributes["name"] = $meta["distinguisher"]."-".$input["name"];

				if (isset($input["classes"])) {
					if (is_array($input["classes"])) {
						$inputAttributes["class"] = implode(" ", $input["classes"]);
					} else {
						$inputAttributes["class"] = $input["classes"];
					}
				}

				if (isset($input["default"])) {
					$inputAttributes["class"] = (isset($inputAttributes["class"]) ? $inputAttributes["class"].' ' : '')."active";
				}

				$inputAttributes["class"] = (isset($inputAttributes["class"]) ? $inputAttributes["class"].' ' : '')."materialize-textarea markdown-field";

				if (isset($input["required"]) && $input["required"]) {
					$inputAttributes["required"] = "required";
				}
				
				$wrapperClasses = (isset($input["wrapper_classes"])) ? $input["wrapper_classes"] : "col s12";

				if (isset($input["other_label_attributes"])) {
					$labelAttributes = array_merge($labelAttributes, $input["other_label_attributes"]);
				}

				if (isset($input["other_attributes"])) {
					$inputAttributes = array_merge($inputAttributes, $input["other_attributes"]);
				}

				$el  = '<p class="col s12">Redacted uses a modified version of Markdown in this field.  Please see <a href="'.ROOTDIR.'Markdown">this page</a> for help.</p>';
				$el .= '<div class="input-field '.$wrapperClasses.'">';
				$el .= '<div class="row">';
				$el .= '<div class="col s12 m6">';
				$el .= '<textarea';
				foreach ($inputAttributes as $key => $value) {
					$el .= " ".$key.'="'.htmlspecialchars($value).'"';
				}
				$el .= '>';
				$el .= isset($input["default"]) ? htmlspecialchars($input["default"]) : "";
				$el .= '</textarea>';
				$el .= '<label';
				foreach ($labelAttributes as $key => $value) {
					$el .= " ".$key.'="'.htmlspecialchars($value).'"';
				}
				$el .= '>'.$input["label"].(isset($input["required"]) && $input["required"] ? '<span class="red-text">&nbsp;*</span>' : '').'</label>';
				$el .= '</div>';
				$el .= '<div class="col s12 m6 markdown-target markdown-preview raw-markdown" data-field="'.$inputAttributes["id"].'">';
				$el .= isset($input["default"]) ? htmlspecialchars($input["default"]) : "";
				$el .= '</div>';
				$el .= '</div>';
				$el .= '</div>';

				return $el;
				break;
			default:
				$inputAttributes = [];
				$labelAttributes = [];
				$labelAttributes["for"] = $inputAttributes["id"] = $meta["distinguisher"]."-".$input["name"];
				$labelAttributes["id"] = $meta["distinguisher"]."-".$input["name"]."-"."label";
				$inputAttributes["name"] = $meta["distinguisher"]."-".$input["name"];
				if ($input["type"] == "confirm-password") {
					$input["type"] = "password";
				}
				$inputAttributes["type"] = $input["type"];

				if (isset($input["required"]) && $input["required"]) {
					$inputAttributes["required"] = "required";
				}

				if (isset($input["pattern"]) && $input["pattern"]) {
					$inputAttributes["pattern"] = htmlspecialchars($input["pattern"][0]);
					$inputAttributes["title"] = htmlspecialchars($input["pattern"][1]);
				}

				if (isset($input["classes"])) {
					if (is_array($input["classes"])) {
						$inputAttributes["class"] = implode(" ", $input["classes"]);
					} else {
						$inputAttributes["class"] = $input["classes"];
					}
				}

				if (isset($input["primary"]) && $input["primary"]) {
					$inputAttributes["autofocus"] = "autofocus";
					if (!isset($inputAttributes["class"])) {
						$inputAttributes["class"] = "active";
					} else {
						$inputAttributes["class"] .= " active";
						$inputAttributes["class"] = trim($inputAttributes["class"]);
					}
				}

				if (isset($input["default"])) {
					$inputAttributes["value"] = $input["default"];
					if (!isset($inputAttributes["class"])) {
						$inputAttributes["class"] = "active";
					} else {
						$inputAttributes["class"] .= " active";
						$inputAttributes["class"] = trim($inputAttributes["class"]);
					}
				}
				
				if (isset($input["validate"]) && $input["validate"]) {
					if (isset($inputAttributes["class"]) && !empty($inputAttributes["class"])) {
						$inputAttributes["class"] .= " validate";
					} else {
						$inputAttributes["class"] = "validate";
					}
				}
				
				if (isset($input["error_text"])) {
					$labelAttributes["data-error"] = $input["error_text"][0];
				}

				if (isset($input["success_text"])) {
					$labelAttributes["data-success"] = $input["success_text"];
				}

				$wrapperClasses = (isset($input["wrapper_classes"])) ? $input["wrapper_classes"] : "col s12";

				if (isset($input["other_label_attributes"])) {
					$labelAttributes = array_merge($labelAttributes, $input["other_label_attributes"]);
				}

				if (isset($input["other_attributes"])) {
					$inputAttributes = array_merge($inputAttributes, $input["other_attributes"]);
				}

				$el = '<div class="input-field '.$wrapperClasses.'">'.
				'<input';

				foreach ($inputAttributes as $key => $value) {
					$el .= " ".$key.'="'.htmlspecialchars($value).'"';
				}

				$el .= '>'.'<label';

				foreach ($labelAttributes as $key => $value) {
					$el .= " ".$key.'="'.htmlspecialchars($value).'"';
				}

				$el .= '>'.$input["label"].(isset($input["required"]) && $input["required"] ? '<span class="red-text">&nbsp;*</span>' : '').'</label>'.'</div>';

				if (isset($input["after_html"])) {
					$el .= $input["after_html"];
				}

				return $el;
				break;
		}
	}

	public static function generateSubmitButton(array $meta) : string {
		return implode("\n",[
			'<div class="row">',
				'<br>',
				'<div id="'.$meta["distinguisher"].'-submit-wrapper">',
					'<button id="'.$meta["distinguisher"].'-btn" class="btn '.\Catalyst\Page\UniversalFunctions::getColorClasses().' waves-effect waves-light submitter col s12 m4 l2">',
						$meta["button"],
					'</button>',
				'</div>',
				'<div id="'.$meta["distinguisher"].'-progress-wrapper" class="hide">',
					'<div class="progress">',
						'<div class="indeterminate"></div>',
					'</div>',
				'</div>',
			'</div>',
		]);
	}

	public static function getColorPicker() : string {
		$str  = '<div class="color-picker-modal modal bottom-sheet">';
		$str .= '<div class="modal-content">';
		$str .= '<h3>Color</h3>';
		$str .= '<h5>Choose a color</h5>';
		$str .= '<div class="row">';

		$numColorSwatches = max(count(\Catalyst\Color::COLOR_BY_CATEGORY), max(array_map("count", \Catalyst\Color::COLOR_BY_CATEGORY)));

		for ($i = 0; $i < $numColorSwatches; $i++) {
			$str .= '<div class="color-swatch col l2 m3 s12"></div>';
		}

		$str .= '</div>';
		$str .= '</div>';
		$str .= '</div>';
		return $str;
	}

	public static function generateForm(array $form) : string {
		$str = '<div class="section">'.self::generateFormHeader($form[0]);
		if ($form[0]["ajax"]) {
			$str .= self::getAjaxTestInput();
		}
		$str .= '<div class="row">';
		foreach (array_slice($form, 1) as $el) {
			$str .= self::generateInputHTML($form[0], $el);
		}
		$str .= '</div><br><div class="divider"></div>';
		$str .= self::generateSubmitButton($form[0]);
		$str .= "</form></div>";

		if (isset($form[0]["flags"])) {
			if (in_array(\Catalyst\Form\Flags::COLOR_PICKER, $form[0]["flags"])) {
				$str .= self::getColorPicker();
			}
		}

		return $str;
	}
}
