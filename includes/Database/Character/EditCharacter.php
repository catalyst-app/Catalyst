<?php

namespace Catalyst\Database\Character;

class EditCharacter {
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

	public static function getFormStructure(?\Catalyst\Character\Character $character=null) : array {
		return [
			[
				"distinguisher" => "editchar",
				"ajax" => true,
				"eval" => 'window.location = "../"+JSON.parse(response).message;',
				"method" => "POST",
				"handler" => '"+$("html").attr("data-rootdir")+"Character/edit.php"+"',
				"button" => "save",
				"additional_cases" => [
					self::NOT_LOGGED_IN => "alert('You are not logged in. Please refresh and try again');return;break;"
				],
				"additional_fields" => [
					"token" => '$("input.token-input").val()'
				],
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
				"default" => is_null($character) ? "" : $character->getName(),
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
				"default" => is_null($character) ? "" : $character->getDescription(),
				"error_text" => [self::PHRASES[self::DESCRIPTION_INVALID]],
				"error_code" => [self::DESCRIPTION_INVALID],
			],
			[
				"name" => "imgs",
				"wrapper_classes" => "col s12",
				"type" => "image",
				"multiple" => true,
				"maxsize" => "10M",
				"label" => "Add Images (10MB limit ea.)",
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::IMAGES_INVALID]],
				"error_code" => [self::IMAGES_INVALID],
				"after_html" => '<p class="col s12 no-top-margin">Go <a href="'.ROOTDIR."Character/".(is_null($character) ? "" : $character->getToken())."/EditImages/".'"">here</a> to modify already uploaded images</p>',
			],
			[
				"name" => "color",
				"wrapper_classes" => "col s12",
				"type" => "color",
				"label" => "Color",
				"default" => is_null($character) ? "" : $character->getColor(),
				"pattern" => ['^('.implode("|", array_keys(\Catalyst\Color::HEX_MAP)).')$', "One of the following: ".implode(", ", array_keys(\Catalyst\Color::HEX_MAP))],
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
				"default" => is_null($character) ? "" : $character->isPublic(),
				"required" => false,
				"error_text" => [],
				"error_code" => []
			],
		];
	}

	public static function delete(\Catalyst\Character\Character $character) : int {
		$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["characters"]."`
			SET 
				`DELETED` = 1
			WHERE
				`ID` = :ID;");
		$cid = $character->getId();
		
		$stmt->bindParam(":ID", $cid);

		if (!$stmt->execute()) {
			error_log(" Delete character error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		return self::SUCCESS;
	}

	public static function update(\Catalyst\Character\Character $character, string $name, string $desc, ?array $newImages, string $color, bool $public) : int {
		$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["characters"]."`
			SET
				`NAME` = :NAME,
				`DESCRIPTION` = :DESCRIPTION,
				`COLOR` = UNHEX(:COLOR),
				`PUBLIC` = :PUBLIC
			WHERE
				`ID` = :ID;");
		$cid = $character->getId();
		$p = ($public ? 1 : 0);
		
		$stmt->bindParam(":NAME", $name);
		$stmt->bindParam(":DESCRIPTION", $desc);
		$stmt->bindParam(":COLOR", $color);
		$stmt->bindParam(":PUBLIC", $p);
		$stmt->bindParam(":ID", $cid);

		if (!$stmt->execute()) {
			error_log(" Edit character error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		$images = \Catalyst\Form\FileUpload::uploadImages($newImages, \Catalyst\Form\FileUpload::CHARACTER_IMAGE, $character->getToken());

		if (is_null($images)) {
			return self::SUCCESS;
		}

		$stmt = $GLOBALS["dbh"]->prepare("INSERT INTO `".DB_TABLES["character_images"]."`
				(`CHARACTER_ID`, `PATH`, `PRIMARY`)
			VALUES
				".implode(",", array_fill(0, count($images), "(".$cid.", ?, ?)")).";");

		$arr = [];
		foreach ($images as $img) {
			$arr = array_merge($arr, [$img, 0]);
		}
		if (count($character->getImages()) == 0) {
			$arr[1] = 1;
		}

		$stmt->execute($arr);

		return self::SUCCESS;
	}

	public static function updateImages(\Catalyst\Character\Character $character, array $images) : int {
		$current = $character->getImages();
		$currentPaths = array_column($current, 0);

		$newPaths = array_map(function($in) use ($character) { return $character->getToken().$in; }, array_column($images, 0));

		$toRemove = array_diff($currentPaths, $newPaths);

		foreach ($toRemove as $path) {
			\Catalyst\Form\FileUpload::delete($path, \Catalyst\Form\FileUpload::CHARACTER_IMAGE);
		}

		if (count($toRemove) != 0) {
			$stmt = $GLOBALS["dbh"]->prepare("DELETE FROM `".DB_TABLES["character_images"]."` WHERE `CHARACTER_ID` = ? AND `PATH` IN (".implode(",", array_fill(0, count($toRemove), "?")).");");
			$cid = $character->getId();
			$stmt->execute(array_merge([$cid], array_map(function($in) use ($character) { return str_replace($character->getToken(), "", $in); }, $toRemove)));
		}

		if (count($images) != 0) {
			$GLOBALS["dbh"]->beginTransaction();
			$i=0;
			foreach ($images as $image) {
				$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["character_images"]."` SET `CAPTION` = :CAPTION, `NSFW` = :NSFW, `PRIMARY` = :PRIMARY, `SORT` = :SORT WHERE `CHARACTER_ID` = :CHARACTER_ID AND `PATH` = :PATH;");
				$nsfw = ($image[2] ? 1 : 0);
				$primary = ($image[3] ? 1 : 0);

				$stmt->bindParam(":CAPTION", $image[1]);
				$stmt->bindParam(":NSFW", $nsfw);
				$stmt->bindParam(":PRIMARY", $primary);
				$stmt->bindParam(":SORT", $i);

				$cid = $character->getId();
				$stmt->bindParam(":CHARACTER_ID", $cid);
				$stmt->bindParam(":PATH", $image[0]);

				$stmt->execute();

				$i++;
			}
			$GLOBALS["dbh"]->commit();
		}

		return self::SUCCESS;
	}
}
