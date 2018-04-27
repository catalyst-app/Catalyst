<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\DeleteQuery;
use \Catalyst\HTTPCode;
use \Catalyst\Integrations\SocialMedia;

Endpoint::init(true, 1);

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

if (!array_key_exists("id", $_POST)) {
	HTTPCode::set(400);
	Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
}

if ((int)$_POST["id"] != $_POST["id"]) {
	HTTPCode::set(400);
	Response::sendErrorResponse(99999, ErrorCodes::ERR_99999);
}

if ($_POST["dest"] === "User") {
	$table = Tables::USER_SOCIAL_MEDIA;
} else {
	$table = Tables::ARTIST_SOCIAL_MEDIA;
}

$stmt = new DeleteQuery();
$stmt->setTable($table);

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", $table), "=", $_POST["id"]]);
$whereClause->addToClause(WhereClause::AND);
if ($_POST["dest"] === "User") {
	$whereClause->addToClause([new Column("USER_ID", $table), "=", $_SESSION["user"]->getId()]);
} else {
	$whereClause->addToClause([new Column("ARTIST_ID", $table), "=", $_SESSION["user"]->getArtistPageId()]);
}
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

Response::sendSuccessResponse("Success");
