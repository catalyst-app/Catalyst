<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Artist\Artist;
use \Catalyst\Images\Image;
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

FormRepository::getEditArtistPageForm()->checkServerSide();

if (!$_SESSION["user"]->isArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91415, ErrorCodes::ERR_91415);
}

$artist = $_SESSION["user"]->getArtistPage();

$artist->setName($_POST["name"]);
$artist->setUrl($_POST["url"]);
$artist->setDescription($_POST["description"]);
$artist->setColor($_POST["color"]);
$artist->addTos($_POST["tos"]);

if (isset($_FILES["image"])) {
	$image = Image::upload($_FILES["image"], Artist::getImageFolder(), $artist->getToken());
	if (!is_null($image)) {
		$artist->setImagePath($image->getPath());
	}
}

Response::sendSuccessResponse("Success");
