<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\{Email, HTTPCode};
use \Catalyst\Form\FormRepository;
use \Catalyst\User\{TOTP,User};

Endpoint::init(true, 2);

FormRepository::getTotpLoginForm()->checkServerSide();

if (!User::isPending2FA()) {
	HTTPCode::set(401);
	Response::sendErrorResponse(90201, ErrorCodes::ERR_90201);
}

if (!$_SESSION["pending_user"]->isTotpEnabled()) {
	HTTPCode::set(500);
	Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
}

$key = $_SESSION["pending_user"]->getTotpKey();
if (!TOTP::checkToken($key, $_POST["totp-code"])) {
	HTTPCode::set(401);
	Response::sendErrorResponse(90204, ErrorCodes::ERR_90204);
}

$_SESSION["user"] = $_SESSION["pending_user"];
unset($_SESSION["pending_user"]);
Response::sendSuccessResponse("Success");
