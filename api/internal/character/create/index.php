<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, Response};
use \Catalyst\Character\Character;
use \Catalyst\Form\Field\MultipleImageWithNsfwCaptionAndInfoField;
use \Catalyst\Form\FormRepository;
use \Catalyst\Tokens;
use \Catalyst\Images\{Folders,Image};

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

FormRepository::getNewCharacterForm()->checkServerSide();

$values = [];

$values["USER_ID"] = $_SESSION["user"]->getId();
$values["CHARACTER_TOKEN"] = Tokens::generateCharacterToken();
$values["NAME"] = $_POST["name"];
$values["DESCRIPTION"] = $_POST["description"];
$values["COLOR"] = hex2bin($_POST["color"]);
$values["PUBLIC"] = $_POST["public"] == "true";

if (isset($_FILES["images"])) {
	$values["_images"] = Image::uploadMultiple($_FILES["images"], Folders::CHARACTER_IMAGE, $values["CHARACTER_TOKEN"]);
	$values["_image_meta"] = MultipleImageWithNsfwCaptionAndInfoField::getExtraFields("images", $_POST);
}

$character = Character::create($values);

Response::sendSuccessResponse("Success", [
	"redirect" => "Character/View/".$character->getToken()
]);
