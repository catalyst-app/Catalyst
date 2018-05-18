<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\{SelectQuery, UpdateQuery};
use \Catalyst\{Email, HTTPCode};
use \Catalyst\Form\FormRepository;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

Endpoint::init(true, Endpoint::AUTH_REQUIRED_LOGGED_OUT);

FormRepository::getLoginForm()->checkServerSide();

$id = User::getIdFromUsername($_POST["username"], true);

if ($id == -1) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90103, ErrorCodes::ERR_90103);
}

$user = new User($id);

if (!$user->verifyPassword($_POST["password"])) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90105, ErrorCodes::ERR_90105);
}

// suspension/deactivation is serious, and should be displayed only when password is correct
if ($user->isSuspended()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90108, ErrorCodes::ERR_90108);
}
if ($user->isSuspended()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90109, ErrorCodes::ERR_90109);
}

if ($user->isTotpEnabled()) {
	$_SESSION["pending_user"] = $user;
	HTTPCode::set(400);
	Response::sendErrorResponse(90110, ErrorCodes::ERR_90110);
}

$_SESSION["user"] = $user;

Response::sendSuccessResponse("Success");
