<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\HTTPCode;
use \Catalyst\Form\FormRepository;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

FormRepository::getDeactivateForm()->checkServerSide();

if (strtolower($_POST["username"]) != strtolower($_SESSION["user"]->getUsername())) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90602, ErrorCodes::ERR_90602);
}

if (!$_SESSION["user"]->verifyPassword($_POST["password"])) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90604, ErrorCodes::ERR_90604);
}

$_SESSION["user"]->delete();

$_SESSION = [];

Response::sendSuccessResponse("Success");
