<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Form\FormRepository;
use \Catalyst\{HTTPCode, Tokens};
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Page\Values;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

FormRepository::getAddNetworkOtherForm()->checkServerSide();

if ($_POST["dest"] !== "User" && $_POST["dest"] !== "Artist") {
	HTTPCode::set(400);
	Response::sendErrorResponse(99903, ErrorCodes::ERR_99903);
}

if ($_POST["dest"] === "Artist") {
	if (!$_SESSION["user"]->isArtist()) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90701, ErrorCodes::ERR_90701);
	}
}

if ($_POST["dest"] === "Artist") {
	$resource = $_SESSION["user"]->getArtistPage();
} else {
	$resource = $_SESSION["user"];
}

Response::sendSuccessResponse("Success", [
	"html" => $resource->addSocialChip($_POST["type"], null, $_POST["label"]),
]);
