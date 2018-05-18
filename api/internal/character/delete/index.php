<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Character\Character;
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

FormRepository::getDeleteCharacterForm()->checkServerSide();

$id = Character::getIdFromToken($_POST["token"]);
if ($id === -1) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90902, ErrorCodes::ERR_90902);
}

$character = new Character($id);
if ($character->getOwnerId() != $_SESSION["user"]->getId()) {
	HTTPCode::set(403);
	Response::sendErrorResponse(90903, ErrorCodes::ERR_90903);
}

$character->delete();

Response::sendSuccessResponse("Success");
