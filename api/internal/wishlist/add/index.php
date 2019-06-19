<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\HTTPCode;
use \Catalyst\User\WishlistItem;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

if (!array_key_exists("token", $_POST)) {
	HTTPCode::set(400);
	Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
}

$commissionTypeId = CommissionType::getIdFromToken($_POST["token"], false);

if ($commissionTypeId == -1) {
	HTTPCode::set(400);
	Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
}

$commissionType = new CommissionType($commissionTypeId);

WishlistItem::create([
	"USER_ID" => $_SESSION["user"]->getId(),
	"COMMISSION_TYPE_ID" => $commissionType->getId(),
]);

Response::sendSuccess("Success");
