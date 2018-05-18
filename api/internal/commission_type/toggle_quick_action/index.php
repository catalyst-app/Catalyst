<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Database\{Column, RawColumn, Tables};
use \Catalyst\Database\QueryAddition\{JoinClause, WhereClause};
use \Catalyst\Database\Query\{SelectQuery, UpdateQuery};
use \Catalyst\HTTPCode;

Endpoint::init(true, Endpoint::AUTH_REQUIRED_LOGGED_IN);

if (!$_SESSION["user"]->isArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91601, ErrorCodes::ERR_91601);
}

if (!array_key_exists("token", $_POST)) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91602, ErrorCodes::ERR_91602);
}

$commissionTypeId = CommissionType::getIdFromToken($_POST["token"], false);

if ($commissionTypeId == -1) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91602, ErrorCodes::ERR_91602);
}

$commissionType = new CommissionType($commissionTypeId);

if ($commissionType->getArtistPageId() !== $_SESSION["user"]->getArtistPageId()) {
	HTTPCode::set(403);
	Response::sendErrorResponse(91603, ErrorCodes::ERR_91603);
}

$actionIndex = -1;

for ($i=0; $i < count(CommissionType::QUICK_TOGGLE_BUTTONS); $i++) { 
	if (CommissionType::QUICK_TOGGLE_BUTTONS[$i][0] == $_POST["action"]) {
		$actionIndex = $i;
		break;
	}
}

if ($actionIndex == -1) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91604, ErrorCodes::ERR_91604);
}

if (!array_key_exists("value", $_POST)) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91605, ErrorCodes::ERR_91605);
}

$stmt = new UpdateQuery();

$stmt->setTable(Tables::COMMISSION_TYPES);

$stmt->addColumn(new Column(CommissionType::QUICK_TOGGLE_BUTTONS[$i][2], Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["value"] == "true");

$whereClause = new WhereClause();

$whereClause->addToClause([new Column("ID", Tables::COMMISSION_TYPES), '=', $commissionTypeId]);

$stmt->execute();

Response::sendSuccessResponse("Success");
