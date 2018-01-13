<?php

namespace Catalyst\Database\Artist;

class EditArtist {
	public const SUCCESS = 0;
	public const NOT_LOGGED_IN = 1;
	public const NOT_AN_ARTIST = 2;
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
		self::NOT_AN_ARTIST => "You are not an artist.  Please refresh and try again.",
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
				"distinguisher" => "editartist",
				"ajax" => true,
				"eval" => 'window.location = "../"+JSON.parse(response).message;',
				"method" => "POST",
				"handler" => "handler.php",
				"button" => "save",
				"additional_cases" => [
					self::NOT_LOGGED_IN => "alert('You are not logged in. Please refresh and try again');return;break;",
					self::NOT_AN_ARTIST => "alert('You are not an artist. Please refresh and try again');return;break;"
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
				"default" => (\Catalyst\User\User::isLoggedIn() ? ($_SESSION["user"]->isArtist() ? $_SESSION["user"]->getArtistPage()->getName() : null) : null),
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
				"default" => (\Catalyst\User\User::isLoggedIn() ? ($_SESSION["user"]->isArtist() ? $_SESSION["user"]->getArtistPage()->getUrl() : null) : null),
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::URL_INVALID], self::PHRASES[self::URL_TAKEN]],
				"error_code" => [self::URL_INVALID, self::URL_TAKEN],
				"after_html" => '<p class="col s12">This will be to the link to your page: <strong id="editartist-url-sample" data-base="'.(preg_replace('/New\/?$/', '', \Catalyst\Page\UniversalFunctions::getRequestURI())).'">'.(preg_replace('/New\/?$/', '', \Catalyst\Page\UniversalFunctions::getRequestURI())).(\Catalyst\User\User::isLoggedIn() ? ($_SESSION["user"]->isArtist() ? $_SESSION["user"]->getArtistPage()->getUrl() : null) : null).'</strong></p>'
			],
			[
				"name" => "desc",
				"wrapper_classes" => "col s12",
				"type" => "markdown-textarea",
				"label" => "Description",
				"required" => true,
				"validate" => true,
				"default" => (\Catalyst\User\User::isLoggedIn() ? ($_SESSION["user"]->isArtist() ? $_SESSION["user"]->getArtistPage()->getDescription() : null) : null),
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
				"after_html" => '<p class="col s12 no-top-margin">Artist profile images may <i>not</i> be NSFW</p><p class="col s12 no-top-margin">Only upload one if you would like to change your picture</p>'
			],
			[
				"name" => "color",
				"wrapper_classes" => "col s12",
				"type" => "color",
				"label" => "Color",
				"default" => (\Catalyst\User\User::isLoggedIn() ? ($_SESSION["user"]->isArtist() ? $_SESSION["user"]->getArtistPage()->getColor() : null) : null),
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
				"default" => (\Catalyst\User\User::isLoggedIn() ? ($_SESSION["user"]->isArtist() ? $_SESSION["user"]->getArtistPage()->getTos() : null) : null),
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::TOS_INVALID]],
				"error_code" => [self::TOS_INVALID],
			],
		];
	}

	public static function update(\Catalyst\Artist\Artist $artist, string $name, string $url, string $desc, string $tos, ?array $img, string $color) : int {
		$aid = $_SESSION["user"]->getId();

		$stmt = $GLOBALS["dbh"]->prepare("SELECT 1 FROM `".DB_TABLES["artist_pages"]."` WHERE `ID` != :ID AND `URL` = :URL;");
		$stmt->bindParam(":ID", $aid);
		$stmt->bindParam(":URL", $url);
		$stmt->execute();

		if ($stmt->rowCount()) {
			$stmt->closeCursor();
			return self::URL_TAKEN;
		}

		$stmt->closeCursor();

		$img = \Catalyst\Form\FileUpload::uploadImages($img, \Catalyst\Form\FileUpload::ARTIST_IMAGE, $artist->getToken());
		if ($img[0] == null) {
			if ($artist->getImg() != "default.png") {
				$img[0] = str_replace($artist->getToken(), "", $artist->getImg());
			}
		}

		$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["artist_pages"]."`
			SET
				`NAME`=:NAME,
				`URL`=:URL,
				`DESCRIPTION`=:DESCRIPTION,
				`TOS`=:TOS,
				`IMG`=:IMG,
				`COLOR`=UNHEX(:COLOR)
			WHERE
				`ID` = :ID;");
		$stmt->bindParam(":ID", $aid);
		$stmt->bindParam(":NAME", $name);
		$stmt->bindParam(":URL", $url);
		$stmt->bindParam(":DESCRIPTION", $desc);
		$stmt->bindParam(":TOS", $tos);
		$stmt->bindParam(":IMG", $img[0]);
		$stmt->bindParam(":COLOR", $color);

		if (!$stmt->execute()) {
			error_log(" Update artist error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		return self::SUCCESS;
	}

	public static function delete(\Catalyst\Artist\Artist $artist) : int {
		$aid = $artist->getId();

		$stmt = $GLOBALS["dbh"]->prepare("SET @ID = :ID;");
		$stmt->bindParam(":ID", $aid);

		if (!$stmt->execute()) {
			error_log(" Delete artist error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		$stmt = $GLOBALS["dbh"]->prepare("
			UPDATE
				`".DB_TABLES["artist_pages"]."`
			SET
				`DELETED` = 1
			WHERE
				`ID` = @ID;

			DELETE
				FROM `".DB_TABLES["artist_social_media"]."`
			WHERE
				`ARTIST_ID` = @ID;

			DELETE
				FROM `".DB_TABLES["artist_streaming_integrations"]."`
			WHERE
				`ARTIST_ID` = @ID;

			UPDATE
				`".DB_TABLES["commission_types"]."`
			SET
				`DELETED` = 1
			WHERE
				`ARTIST_PAGE_ID` = @ID;

			UPDATE
				`".DB_TABLES["users"]."`
			SET
				`ARTIST_PAGE_ID` = NULL
			WHERE
				`ARTIST_PAGE_ID` = @ID;
			");

		if (!$stmt->execute()) {
			error_log(" Delete artist error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		return self::SUCCESS;
	}

	public static function order(array $order, \Catalyst\Artist\Artist $artist) : int {
		if (empty($order)) {
			return self::SUCCESS;
		}

		$GLOBALS["dbh"]->beginTransaction();

		$i = 0;
		$aid = $artist->getId();
		foreach ($order as $id) {
			$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["commission_types"]."` SET `SORT` = :SORT WHERE `ID` = :ID AND `ARTIST_PAGE_ID` = :ARTIST_PAGE_ID;");
			$stmt->bindParam(":SORT", $i);
			$stmt->bindParam(":ID", $id);
			$stmt->bindParam(":ARTIST_PAGE_ID", $aid);
			$stmt->execute();

			$i++;
		}
		$GLOBALS["dbh"]->commit();

		return self::SUCCESS;
	}
}
