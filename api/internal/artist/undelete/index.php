<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Artist\Artist;
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

FormRepository::getUndeleteArtistPageForm()->checkServerSide();

if ($_SESSION["user"]->isArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91101, ErrorCodes::ERR_91101);
}

$aid = Artist::getIdFromUserId($_SESSION["user"]->getId());

if ($aid == -1) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91102, ErrorCodes::ERR_91102);
}

$artist = new Artist($aid);
$artist->setDeleted(false);

$_SESSION["user"]->setArtistPageId($aid);

Response::sendSuccess("Success");
