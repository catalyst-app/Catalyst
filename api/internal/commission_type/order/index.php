<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\{HTTPCode, Tokens};

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

if (!$_SESSION["user"]->isArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
}

if (!array_key_exists("order", $_POST)) {
	HTTPCode::set(400);
	Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
}

$order = json_decode($_POST["order"]);

if (!is_array($order)) {
	HTTPCode::set(400);
	Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
}

foreach ($order as $item) {
	if (!preg_match('/'.Tokens::COMMISSION_TYPE_TOKEN_REGEX.'/', $item)) {
		HTTPCode::set(400);
		Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
	}
}

$i = 0;
foreach ($order as $token) {
	$commissionTypeId = CommissionType::getIdFromToken($token, false);

	// token doesn't exist
	if ($commissionTypeId == -1) {
		HTTPCode::set(400);
		Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
	}

	$commissionType = new CommissionType($commissionTypeId);
	$commissionType->setSort($i++);
}

Response::sendSuccess("Success");
