<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Database, Tables};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\UpdateQuery;
use \Catalyst\HTTPCode;
use \Catalyst\Integrations\SocialMedia;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

if ($_POST["dest"] !== "User" && $_POST["dest"] !== "Artist") {
	HTTPCode::set(400);
	Response::sendErrorResponse(99903, ErrorCodes::ERR_99903);
}

if ($_POST["dest"] === "Artist") {
	if (!$_SESSION["user"]->isArtist()) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90701, ErrorCodes::ERR_90701);
	}
}

if (!array_key_exists("order", $_POST)) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90712, ErrorCodes::ERR_90712);
}

$order = json_decode($_POST["order"]);

if (!is_array($order)) {
	HTTPCode::set(400);
	Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
}
foreach ($order as $item) {
	if ((int)$item != $item) {
		HTTPCode::set(400);
		Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
	}
}

if ($_POST["dest"] === "User") {
	$table = Tables::USER_SOCIAL_MEDIA;
} else {
	$table = Tables::ARTIST_SOCIAL_MEDIA;
}

Database::getDbh()->beginTransaction();

$i = 0;
foreach ($order as $id) {
	$stmt = new UpdateQuery();
	$stmt->setTable($table);

	$stmt->addColumn(new Column("SORT", $table));
	$stmt->addValue($i);

	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("ID", $table), "=", $id]);
	$whereClause->addToClause(WhereClause::AND);
	if ($_POST["dest"] === "User") {
		$whereClause->addToClause([new Column("USER_ID", $table), "=", $_SESSION["user"]->getId()]);
	} else {
		$whereClause->addToClause([new Column("ARTIST_ID", $table), "=", $_SESSION["user"]->getArtistPageId()]);
	}
	$stmt->addAdditionalCapability($whereClause);

	$stmt->execute();

	$i++;
}

Database::getDbh()->commit();

Response::sendSuccess("Success");
