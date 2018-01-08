<?php

namespace Redacted\Form;

class FormJS {
	public static function generateCheck(array $meta, array $input) : string {
		if ($input["type"] == "custom") {
			return $input["custom_js"];
		}

		$breakJS = self::generateStopSubmitAnimation($meta).' e.preventDefault(); return false;';

		if ($input["type"] == "captcha") {
			return 'if (grecaptcha.getResponse() == "") { markCaptchaInvalid(); '.$breakJS.' }';
		}

		$selector = '$("#'.$meta["distinguisher"].'-'.$input["name"].'")';

		if ($input["type"] == "image") {
			if (isset($input["required"]) && $input["required"]) {
				return 'if ('.$selector.'[0].files.length === 0) {markInputInvalid($("#'.$meta["distinguisher"].'-'.$input["name"].'-psuedo-path"), "'.$input["error_text"][0].'"); '.$breakJS.'}for(var i=0;i<'.$selector.'[0].files.length;i++){if ('.$selector.'[0].files[i].size > '.\Redacted\Page\UniversalFunctions::dehumanize($input["maxsize"]).') { markInputInvalid($("#'.$meta["distinguisher"].'-'.$input["name"].'-psuedo-path"), "'.$input["error_text"][0].'"); '.$breakJS.' }}';
			} else {
				return 'for(var i=0;i<'.$selector.'[0].files.length;i++){if ('.$selector.'[0].files[i].size > '.\Redacted\Page\UniversalFunctions::dehumanize($input["maxsize"]).') { markInputInvalid($("#'.$meta["distinguisher"].'-'.$input["name"].'-psuedo-path"), "'.$input["error_text"][0].'"); '.$breakJS.' }}';
			}
		}

		if ($input["type"] == "checkbox") {
			if (isset($input["required"]) && $input["required"]) {
				return 'if (!'.$selector.'.is(":checked")) { markInputInvalid('.$selector.', "'.$input["error_text"][0].'"); '.$breakJS.' }';
			} else {
				return '';
			}
		}

		if ($input["type"] == "select") {
			if (isset($input["required"]) && $input["required"]) {
				return 'if ('.$selector.'.find("option:selected").is(":disabled")) { markInputInvalid('.$selector.', "'.$input["error_text"][0].'"); '.$breakJS.' }';
			} else {
				return '';
			}
		}

		$str = "";
		$checks = []; // actually inverse, these are for failures

		if (isset($input["required"]) && $input["required"]) {
			$checks[] = $selector.'.val() === ""';
		}
		
		if (isset($input["pattern"])) {
			if (isset($input["required"]) && $input["required"]) {
				$checks[] = '!(new RegExp(/'.$input["pattern"][0].'/).test('.$selector.'.val()))';
			} else {
				$checks[] = '('.$selector.'.val() !== "" && !(new RegExp("'.str_replace('"', '\\"', $input["pattern"][0]).'").test('.$selector.'.val())))';
			}
		}

		if ($input["type"] == "confirm-password") {
			$checks[] = $selector.'.val() !== $("#'.$meta["distinguisher"].'-'.$input["linked_field"].'").val()';
		}

		if (count($checks) == 0) {
			return "";
		}

		return 'if ('.$selector.'.is(":not(.hide)") && ('.implode(" || ", $checks).')) { markInputInvalid('.$selector.', "'.$input["error_text"][0].'"); '.$breakJS.'}';
	}

	public static function generateChecks(array $form) : string {
		$str = "";

		foreach (array_slice($form, 1) as $input) {
			$str .= self::generateCheck($form[0], $input);
		}

		return $str;
	}

	public static function generateEventSignature(array $meta) : string {
		return '$(document).on("submit", "#'.$meta["distinguisher"].'-form", function(e) {'.((isset($meta["ajax"]) && $meta["ajax"]) ? "e.preventDefault();" : "");
	}

	public static function generateValueGetter(array $meta, array $input) : string {
		switch ($input["type"]) {
			case "custom":
				return $input["custom_js_get_var"];
			case "captcha":
				return 'grecaptcha.getResponse()';
				break;
			case "confirm-password":
			case "password":
				return 'btoa($("#'.$meta["distinguisher"].'-'.$input["name"].'").val())';
				break;
			case "image":
				return '$("#'.$meta["distinguisher"].'-'.$input["name"].'")[0].files'.(!isset($input["multiple"]) || !$input["multiple"] ? '[0]' : '');
				break;
			case "checkbox":
				return '$("#'.$meta["distinguisher"].'-'.$input["name"].'").is(":checked")';
				break;
			default:
				return '$("#'.$meta["distinguisher"].'-'.$input["name"].'").val()';
				break;
		}
	}

	public static function generateAjaxCode(array $form) : string {
		$str = 'var data = new FormData();';

		$formData = (isset($form[0]["additional_fields"])) ? $form[0]["additional_fields"] : [];

		foreach (array_slice($form, 1) as $input) {
			if ($input["type"] == "custom") {
				$str .= $input["custom_js_getter"];
			}
			$formData[$input["name"].(isset($input["multiple"]) && $input["multiple"] ? "[]" : '')] = self::generateValueGetter($form[0], $input);
		}

		$qs = implode("", array_map(function($key, $val) {
			if (strpos(strrev($key), strrev("[]")) === 0) {
				return 'for (var i=0;i<'.$val.'.length;i++){data.append("'.$key.'", '.$val.'[i]);}';
			}
			return 'data.append("'.$key.'", '.$val.');';
		}, array_keys($formData), $formData));
		$qs = rtrim($qs, ",");

		$str .= $qs;

		$str .= '$.ajax("'.$form[0]["handler"].'", {data: data, processData: false, contentType: false, method: "'.$form[0]["method"].'"}).done(function(response) {Materialize.toast("'.$form[0]["success"].'", 4000);'.(isset($form[0]["redirect"]) ? 'window.location = "'.$form[0]["redirect"].'";' : $form[0]["eval"]).'}).fail(function(response) {var data = JSON.parse(response.responseText);console.warn(data);';
		$str .= self::generateErrorSwitch($form);
		$str .= "}).always(function() {".self::generateStopSubmitAnimation($form[0])."});";

		return $str;
	}

	public static function generateErrorCase(string $case, string $exp) : string {
		return 'case '.$case.': '.$exp;
	}

	public static function generateErrorCaseFromInput(array $meta, array $input) : string {
		if (!isset($input["error_code"])) {
			return "";
		}
		$res = "";
		foreach ($input["error_code"] as $code) {
			switch ($input["type"]) {
				case "captcha":
					$res .= self::generateErrorCase($code, 'markCaptchaInvalid(data.message); return; break;');
					break;
				default:
					$res .= self::generateErrorCase($code, 'markInputInvalid($("#'.$meta["distinguisher"].'-'.$input["name"].'"), data.message); return; break;');
					break;
			}
		}
		return $res;
	}

	public static function generateErrorSwitch(array $form) : string {
		$str = 'switch (data.error_code) {';
		foreach (array_slice($form, 1) as $input) {
			$str .= self::generateErrorCaseFromInput($form[0], $input);
		}
		if (isset($form[0]["additional_cases"])) {
			foreach ($form[0]["additional_cases"] as $key => $value) {
				$str .= self::generateErrorCase($key, $value);
			}
		}
		$str .= 'default: alert(data.message); return; break;';
		$str .= "}";
		return $str;
	}

	public static function generateStartSubmitAnimation(array $meta) : string {
		return '$("#'.$meta["distinguisher"].'-submit-wrapper").addClass("hide");$("#'.$meta["distinguisher"].'-progress-wrapper").removeClass("hide");';
	}

	public static function generateStopSubmitAnimation(array $meta) : string {
		return '$("#'.$meta["distinguisher"].'-submit-wrapper").removeClass("hide");$("#'.$meta["distinguisher"].'-progress-wrapper").addClass("hide");';
	}

	public static function generateFormHandler(array $form) : string {
		return self::generateEventSignature($form[0]).self::generateStartSubmitAnimation($form[0]).self::generateChecks($form).self::generateAjaxCode($form).'});';
	}
}
