<?php

namespace Redacted\Database\Artist;

class NewArtist {
	public const SUCCESS = 0;
	public const NOT_LOGGED_IN = 1;
	public const ALREADY_AN_ARTIST = 2;
	public const NAME_INVALID = 3;
	public const URL_INVALID = 4;
	public const URL_TAKEN = 5;
	public const DESCRIPTION_INVALID = 6;
	public const COLOR_INVALID = 7;
	public const IMAGE_INVALID = 8;
	public const TOS_INVALID = 9;
	public const ERROR_UNKNOWN = 10;

	public const PHRASES = [
		self::SUCCESS => "Success",
		self::NOT_LOGGED_IN => "You are not logged in.  Please refresh and try again.",
		self::ALREADY_AN_ARTIST => "You are already an artist.  Please refresh and try again.",
		self::NAME_INVALID => "Invalid name, please use between 4 and 255 characters",
		self::URL_INVALID => "Invalid URL.  Use between 4 and 255 characters containing only letters, numbers, dashes, underscores, or a period.  Additionally, a period may not be the last character of the URL.",
		self::URL_TAKEN => "Please use a different URL.",
		self::DESCRIPTION_INVALID => "Invalid description",
		self::COLOR_INVALID => "Invalid color",
		self::IMAGE_INVALID => "Invalid image",
		self::TOS_INVALID => "Invalid TOS",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public static $lastErrId = -1;
	public static $lastInsertId = -1;

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "newartist",
				"ajax" => true,
				"eval" => 'window.location = "../"+JSON.parse(response).message;',
				"method" => "POST",
				"handler" => "handler.php",
				"button" => "create",
				"additional_cases" => [
					self::NOT_LOGGED_IN => "alert('You are not logged in. Please refresh and try again');return;break;",
					self::ALREADY_AN_ARTIST => "alert('You are already an artist. Please refresh and try again');return;break;"
				],
				"additional_fields" => [],
				"success" => self::PHRASES[self::SUCCESS],
				"flags" => [
					\Catalyst\Form\Flags::COLOR_PICKER
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
				"name" => "url",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"pattern" => ['^[A-Za-z0-9._-]{3,254}[A-Za-z0-9_-]$', "Between 4 and 255 characters containing only letters, numbers, dashes, underscores, or a period.  Additionally, a period may not be the last character of the URL."],
				"label" => "URL",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::URL_INVALID], self::PHRASES[self::URL_TAKEN]],
				"error_code" => [self::URL_INVALID, self::URL_TAKEN],
				"after_html" => '<p class="col s12">This will be to the link to your page: <strong id="newartist-url-sample" data-base="'.(preg_replace('/New\/?$/', '', \Catalyst\Page\UniversalFunctions::getRequestURI())).'">'.(preg_replace('/New\/?$/', '', \Catalyst\Page\UniversalFunctions::getRequestURI())).'</strong></p>'
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
				"after_html" => '<p class="col s12">Please do not list commission information here.</p>'
			],
			[
				"name" => "img",
				"wrapper_classes" => "col s12",
				"type" => "image",
				"multiple" => true,
				"maxsize" => "2M",
				"label" => "Image (2MB limit)",
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::IMAGE_INVALID]],
				"error_code" => [self::IMAGE_INVALID],
				"after_html" => '<p class="col s12 no-top-margin">Artist profile images may <i>not</i> be NSFW</p>'
			],
			[
				"name" => "color",
				"wrapper_classes" => "col s12",
				"type" => "color",
				"label" => "Color",
				"default" => (defined("PAGE_COLOR") ? PAGE_COLOR : \Catalyst\Page\Values::DEFAULT_COLOR),
				"pattern" => ['^('.implode("|", array_keys(\Catalyst\Color::HEX_MAP)).')$', "One of the following: ".implode(", ", array_keys(\Catalyst\Color::HEX_MAP))],
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::COLOR_INVALID]],
				"error_code" => [self::COLOR_INVALID]
			],
			[
				"name" => "tos",
				"wrapper_classes" => "col s12",
				"type" => "markdown-textarea",
				"label" => "Terms of Service",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::TOS_INVALID]],
				"error_code" => [self::TOS_INVALID],
				"after_html" => '<p class="col s12">Commissioners will read and agree to this when they request a commission.</p>'
			],
		];
	}

	public static function create(string $name, string $url, string $desc, string $tos, ?array $img, string $color) : int {
		$uid = $_SESSION["user"]->getId();

		$stmt = $GLOBALS["dbh"]->prepare("SELECT 1 FROM `".DB_TABLES["artist_pages"]."` WHERE `USER_ID` != :USER_ID AND `URL` = :URL;");
		$stmt->bindParam(":USER_ID", $uid);
		$stmt->bindParam(":URL", $url);
		$stmt->execute();

		if ($stmt->rowCount()) {
			$stmt->closeCursor();
			return self::URL_TAKEN;
		}

		$stmt->closeCursor();

		$token = \Catalyst\Tokens::generateUniqueArtistToken();
		$img = \Catalyst\Form\FileUpload::uploadImages($img, \Catalyst\Form\FileUpload::ARTIST_IMAGE, $token);

		$stmt = $GLOBALS["dbh"]->prepare("INSERT INTO `".DB_TABLES["artist_pages"]."`
				(`USER_ID`, `TOKEN`, `NAME`, `URL`, `DESCRIPTION`, `TOS`, `IMG`, `COLOR`, `DELETED`)
			VALUES
				(:USER_ID, :TOKEN, :NAME, :URL, :DESCRIPTION, :TOS, :IMG, UNHEX(:COLOR), 0)
			ON DUPLICATE KEY UPDATE
				`TOKEN`=VALUES(`TOKEN`),
				`NAME`=VALUES(`NAME`),
				`URL`=VALUES(`URL`),
				`DESCRIPTION`=VALUES(`DESCRIPTION`),
				`TOS`=VALUES(`TOS`),
				`IMG`=VALUES(`IMG`),
				`COLOR`=VALUES(`COLOR`),
				`DELETED`=0;");
		$stmt->bindParam(":USER_ID", $uid);
		$stmt->bindParam(":TOKEN", $token);
		$stmt->bindParam(":NAME", $name);
		$stmt->bindParam(":URL", $url);
		$stmt->bindParam(":DESCRIPTION", $desc);
		$stmt->bindParam(":TOS", $tos);
		$stmt->bindParam(":IMG", $img[0]);
		$stmt->bindParam(":COLOR", $color);

		if (!$stmt->execute()) {
			error_log(" Add artist error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		$id = $GLOBALS["dbh"]->lastInsertId();

		$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["users"]."` SET `ARTIST_PAGE_ID` = :ARTIST_PAGE_ID WHERE `ID` = :ID;");
		$stmt->bindParam(":ARTIST_PAGE_ID", $id);
		$stmt->bindParam(":ID", $uid);

		if (!$stmt->execute()) {
			error_log(" Add artist error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}		

		return self::SUCCESS;
	}
}
