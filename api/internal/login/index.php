<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\HTTPCode;
use \Catalyst\Form\FormRepository;
use \Catalyst\User\User;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_OUT);

FormRepository::getLoginForm()->checkServerSide();

$id = User::getIdFromUsername($_POST["username"], true);

if ($id == -1) {
	HTTPCode::set(400);
	Response::sendError("username", "usernameDoesNotExist");
}

$user = new User($id);

// deactivation nukes password
if ($user->isDeactivated()) {
	HTTPCode::set(400);
	Response::sendError("username", "deactivated");
}

if (!$user->verifyPassword($_POST["password"])) {
	HTTPCode::set(400);
	Response::sendError("password", "incorrectPassword");
}

// suspension is serious, and should be displayed only when password is correct
if ($user->isSuspended()) {
	HTTPCode::set(400);
	Response::sendError("username", "suspended");
}

if ($user->isTotpEnabled()) {
	$_SESSION["pending_user"] = $user;
	HTTPCode::set(400);
	Response::sendErrorResponse(90110, ErrorCodes::ERR_90110);
}

$_SESSION["user"] = $user;

Response::sendSuccess("Success");
