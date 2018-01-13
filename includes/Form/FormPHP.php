<?php

namespace Redacted\Form;

class FormPHP {
	public static function checkMethod(array $meta) {
		if ($_SERVER["REQUEST_METHOD"] !== $meta["method"]) {
			\Catalyst\Response::send405($meta["method"]);
		}
	}

	public static function checkInput(array $form, array $input) {
		$requestArr = [];
		if ($form["method"] == "GET") {
			$requestArr =& $_GET;
		} else {
			$requestArr =& $_POST;
		}

		switch ($input["type"]) {
			case "custom":
				break;
			case "captcha":
				if (!isset($requestArr[$input["name"]])) {
					\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
				}
				if (isset($input["required"]) && $input["required"]) {
					if (!isset($requestArr[$input["name"]]) || empty($requestArr[$input["name"]])) {
						\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
					}
				}
				if (!\Catalyst\Form\Captcha::test($input["secret"], $requestArr[$input["name"]])) {
					\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
				}
				break;
			case "image":
				if (!isset($_FILES[$input["name"]]) && isset($input["required"]) && $input["required"]) {
					\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
				}
				if (!isset($_FILES[$input["name"]])) {
					return;
				}
				if (isset($input["multiple"]) && $input["multiple"]) {
					for ($i=0; $i < count($_FILES[$input["name"]]["name"]); $i++) { 
						if ($_FILES[$input["name"]]["error"][$i] !== 0 && isset($input["required"]) && $input["required"]) { // not uploaded
							\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
						}
						if ($_FILES[$input["name"]]["error"][$i] !== 0 && $_FILES[$input["name"]]["error"][$i] !== 4) { // error
							\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
						}
						if ($_FILES[$input["name"]]["error"][$i] === 4) {
							return;
						}
						if (strpos(\Catalyst\Form\FileUpload::getMIME($_FILES[$input["name"]]["tmp_name"][$i]), "image/") !== 0) {
							\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
						}
						if ($_FILES[$input["name"]]["size"][$i] > \Catalyst\Page\UniversalFunctions::dehumanize($input["maxsize"])) {
							\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
						}
					}
				} else {
					if ($_FILES[$input["name"]]["error"] !== 0 && isset($input["required"]) && $input["required"]) { // not uploaded
						\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
					}
					if ($_FILES[$input["name"]]["error"] !== 0 && $_FILES[$input["name"]]["error"] !== 4) { // error
						\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
					}
					if ($_FILES[$input["name"]]["error"] === 4) {
						return;
					}
					if (strpos(\Catalyst\Form\FileUpload::getMIME($_FILES[$input["name"]]["tmp_name"]), "image/") !== 0) {
						\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
					}
					if ($_FILES[$input["name"]]["size"] > \Catalyst\Page\UniversalFunctions::dehumanize($input["maxsize"])) {
						\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
					}
				}
				break;
			case "checkbox":
				if (!isset($requestArr[$input["name"]])) {
					\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
				}
				if (isset($input["required"]) && $input["required"]) {
					if (((string)$_POST[$input["name"]]) != "true") {
						\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
					}
				}
				break;
			case "select":
				if (!isset($requestArr[$input["name"]])) {
					\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
				}
				if (isset($input["required"]) && $input["required"]) {
					if (!in_array($_POST[$input["name"]], array_column($input["options"], 0))) {
						\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
					}
				}
				break;
			default:
				if (!isset($requestArr[$input["name"]])) {
					\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
				}
				if ($input["type"] == "confirm-password") {
					if ($requestArr[$input["name"]] !== $requestArr[$input["linked_field"]]) {
						\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
					}
				}
				if (isset($input["required"]) && $input["required"]) {
					if (!isset($requestArr[$input["name"]]) || empty($requestArr[$input["name"]])) {
						\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
					}
				}
				if (isset($input["pattern"])) {
					if ((
						!empty($requestArr[$input["name"]]) &&
						!preg_match('/'.str_replace(['/', '"'], ['\\/', '\\"'], $input["pattern"][0]).'/', $requestArr[$input["name"]]))) {
						\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
					}
					if (
						((isset($input["required"]) && $input["required"]) &&
						!preg_match('/'.str_replace(['/', '"'], ['\\/', '\\"'], $input["pattern"][0]).'/', $requestArr[$input["name"]]))) {
						\Catalyst\Response::send401($input["error_code"][0], $input["error_text"][0]);
					}
				}
				break;
		}
	}

	public static function checkForm(array $form) {
		self::checkMethod($form[0]);
		if ($form[0]["method"] != "POST" && $form[0]["method"] != "GET") {
			return;
		}
		foreach (array_slice($form, 1) as $input) {
			self::checkInput($form[0], $input);
		}
	}
}
