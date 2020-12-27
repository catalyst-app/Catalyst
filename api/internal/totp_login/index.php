<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\{Email, HTTPCode};
use \Catalyst\Form\FormRepository;
use \Catalyst\User\{TOTP,User};

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_OUT);

FormRepository::getTotpLoginForm()->checkServerSide();

if (!User::isPending2FA()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90201, ErrorCodes::ERR_90201);
}

if (!$_SESSION["pending_user"]->isTotpEnabled()) {
	HTTPCode::set(500);
	Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
}

$key = $_SESSION["pending_user"]->getTotpKey();
if (!TOTP::checkToken($key, $_POST["totp-code"])) {
	HTTPCode::set(400);
	Response::sendError("totp-code", "invalidCode");
}

$_SESSION["user"] = $_SESSION["pending_user"];
unset($_SESSION["pending_user"]);
Response::sendSuccess("Success");
