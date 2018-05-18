<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Database, Tables};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\UpdateQuery;
use \Catalyst\{HTTPCode, Tokens};

Endpoint::init(true, Endpoint::AUTH_REQUIRED_LOGGED_IN);

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

Database::getDbh()->beginTransaction();

$i = 0;
foreach ($order as $token) {
	$stmt = new UpdateQuery();
	$stmt->setTable(Tables::COMMISSION_TYPES);

	$stmt->addColumn(new Column("SORT", Tables::COMMISSION_TYPES));
	$stmt->addValue($i);

	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("TOKEN", Tables::COMMISSION_TYPES), "=", $token]);
	$whereClause->addToClause(WhereClause::AND);
	$whereClause->addToClause([new Column("ARTIST_PAGE_ID", Tables::COMMISSION_TYPES), "=", $_SESSION["user"]->getArtistPageId()]);
	$stmt->addAdditionalCapability($whereClause);

	$stmt->execute();

	$i++;
}

Database::getDbh()->commit();

Response::sendSuccessResponse("Success");
