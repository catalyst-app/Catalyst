<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Character\Character;
use \Catalyst\Form\Field\MultipleImageWithNsfwCaptionAndInfoField;
use \Catalyst\Form\FormRepository;
use \Catalyst\{HTTPCode, Tokens};
use \Catalyst\Images\{Folders,Image};
use \Catalyst\Page\Values;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

FormRepository::getEditCharacterForm()->checkServerSide();

$id = Character::getIdFromToken($_POST["token"]);

if ($id == -1) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91012, ErrorCodes::ERR_91012);
}

$character = new Character($id);

if ($character->getOwnerId() != $_SESSION["user"]->getId()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91012, ErrorCodes::ERR_91012);
}

$character->setName($_POST["name"]);
$character->setDescription($_POST["description"]);
$character->setColor($_POST["color"]);
$character->setPublic($_POST["public"] == "true");

$imageMeta = MultipleImageWithNsfwCaptionAndInfoField::getExtraFields("images", $_POST);

$existingImages = $character->getImageSet();

foreach ($existingImages as $image) {
	if (!array_key_exists($image->getPath(), $imageMeta)) {
		$image->delete();
		continue;
	}
	$image->setNsfw(!!$imageMeta[$image->getPath()]["nsfw"]);
	$image->setCaption($imageMeta[$image->getPath()]["caption"]);
	$image->setInfo($imageMeta[$image->getPath()]["info"]);
	$image->setSort($imageMeta[$image->getPath()]["sort"]);
}

if (isset($_FILES["images"])) {
	$images = Image::uploadMultiple($_FILES["images"], Folders::CHARACTER_IMAGE, $_POST["token"]);

	foreach ($images as $image) {
		if (is_null($image->getPath())) {
			continue;
		}
		$character->addImage(
			$image->getPath(),
			!!$imageMeta[$image->getUploadName()]["nsfw"],
			$imageMeta[$image->getUploadName()]["caption"],
			$imageMeta[$image->getUploadName()]["info"],
			$imageMeta[$image->getUploadName()]["sort"]
		);
	}
}

Response::sendSuccessResponse("Success", [
	"redirect" => "Character/View/".$_POST["token"]
]);
