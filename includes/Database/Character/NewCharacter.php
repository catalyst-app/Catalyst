<?php

namespace Redacted\Database\Character;

class NewCharacter {
	public const SUCCESS = 0;
	public const NOT_LOGGED_IN = 1;
	public const NAME_INVALID = 2;
	public const DESCRIPTION_INVALID = 3;
	public const COLOR_INVALID = 4;
	public const IMAGES_INVALID = 5;
	public const ERROR_UNKNOWN = 6;

	public const PHRASES = [
		self::SUCCESS => "Success",
		self::NOT_LOGGED_IN => "You are not logged in.  Please refresh and try again.",
		self::NAME_INVALID => "Invalid name",
		self::DESCRIPTION_INVALID => "Invalid description",
		self::COLOR_INVALID => "Invalid color",
		self::IMAGES_INVALID => "Invalid image(s)",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public static $lastErrId = -1;
	public static $lastInsertId = -1;

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "newchar",
				"ajax" => true,
				"eval" => 'window.location = "../"+JSON.parse(response).message;',
				"method" => "POST",
				"handler" => "handler.php",
				"button" => "create",
				"additional_cases" => [
					self::NOT_LOGGED_IN => "alert('You are not logged in. Please refresh and try again');return;break;"
				],
				"additional_fields" => [],
				"success" => self::PHRASES[self::SUCCESS],
				"flags" => [
					\Redacted\Form\Flags::COLOR_PICKER
				]
			],
			[
				"name" => "name",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"pattern" => ['^.{2,255}$', "Between 2 and 255 characters"],
				"label" => "Name",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::NAME_INVALID]],
				"error_code" => [self::NAME_INVALID],
			],
			[
				"name" => "desc",
				"wrapper_classes" => "col s12",
				"type" => "markdown-textarea",
				"label" => "Description",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::DESCRIPTION_INVALID]],
				"error_code" => [self::DESCRIPTION_INVALID],
			],
			[
				"name" => "imgs",
				"wrapper_classes" => "col s12",
				"type" => "image",
				"multiple" => true,
				"maxsize" => "2M",
				"label" => "Images (2MB limit ea.)",
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::IMAGES_INVALID]],
				"error_code" => [self::IMAGES_INVALID]
			],
			[
				"name" => "color",
				"wrapper_classes" => "col s12",
				"type" => "color",
				"label" => "Color",
				"default" => (defined("PAGE_COLOR") ? PAGE_COLOR : \Redacted\Page\Values::DEFAULT_COLOR),
				"pattern" => ['^('.implode("|", array_keys(\Redacted\Color::HEX_MAP)).')$', "One of the following: ".implode(", ", array_keys(\Redacted\Color::HEX_MAP))],
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::COLOR_INVALID]],
				"error_code" => [self::COLOR_INVALID]
			],
			[
				"name" => "public",
				"wrapper_classes" => "col s12 no-top-margin more-bottom-margin",
				"type" => "checkbox",
				"label" => "Make this character public",
				"after_html" => '<p class="col s12 no-top-margin">If this character is public, anyone can see it on your profile.  Otherwise, only you and artists you commission may see it.</p>',
				"required" => false,
				"error_text" => [],
				"error_code" => []
			],
		];
	}

	public static function add(string $name, string $desc, ?array $images, string $color, bool $public, string &$insertToken="") {
		$stmt = $GLOBALS["dbh"]->prepare("INSERT INTO `".DB_TABLES["characters"]."`
				(`USER_ID`, `CHARACTER_TOKEN`, `NAME`, `DESCRIPTION`, `COLOR`, `PUBLIC`)
			VALUES
				(:USER_ID, :CHARACTER_TOKEN, :NAME, :DESCRIPTION, UNHEX(:COLOR), :PUBLIC);");
		$id = $_SESSION["user"]->getId();
		$token = $insertToken = \Redacted\Tokens::generateUniqueCharacterToken();
		$p = ($public ? 1 : 0);

		$stmt->bindParam(":USER_ID", $id);
		$stmt->bindParam(":CHARACTER_TOKEN", $token);
		$stmt->bindParam(":NAME", $name);
		$stmt->bindParam(":DESCRIPTION", $desc);
		$stmt->bindParam(":COLOR", $color);
		$stmt->bindParam(":PUBLIC", $p);

		if (!$stmt->execute()) {
			error_log(" Add character error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		$images = \Redacted\Form\FileUpload::uploadImages($images, \Redacted\Form\FileUpload::CHARACTER_IMAGE, $token);

		if (is_null($images)) {
			return self::SUCCESS;
		}

		$cid = $GLOBALS["dbh"]->lastInsertId();

		$stmt = $GLOBALS["dbh"]->prepare("INSERT INTO `".DB_TABLES["character_images"]."`
				(`CHARACTER_ID`, `PATH`, `PRIMARY`)
			VALUES
				".implode(",", array_fill(0, count($images), "(".$cid.", ?, ?)")).";");

		$arr = [];
		foreach ($images as $img) {
			$arr = array_merge($arr, [$img, 0]);
		}
		$arr[1] = 1;

		$stmt->execute($arr);

		return self::SUCCESS;
	}
}
