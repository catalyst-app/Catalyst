<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, DeleteQuery, JoinClause, SelectQuery, Tables, UpdateQuery, WhereClause};
use \Catalyst\HTTPCode;
use \Catalyst\Form\FormRepository;
use \Catalyst\User\User;

Endpoint::init(true, 1);

FormRepository::getDeactivateForm()->checkServerSide();

if (strtolower($_POST["username"]) != strtolower($_SESSION["user"]->getUsername())) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90602, ErrorCodes::ERR_90602);
}

$userId = $_SESSION["user"]->getId();

$query = new SelectQuery();
$query->setTable(Tables::USERS);

$query->addColumn(new Column("HASHED_PASSWORD", Tables::USERS));

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::USERS), "=", $userId]);
$query->addAdditionalCapability($whereClause);

$query->execute();

$result = $query->getResult();

if (!password_verify($_POST["password"], $result[0]["HASHED_PASSWORD"])) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90604, ErrorCodes::ERR_90604);
}

$deactivateUserQuery = new UpdateQuery();
$deactivateUserQuery->setTable(Tables::USERS);
$deactivateUserQuery->addColumn(new Column("DEACTIVATED", Tables::USERS));
$deactivateUserQuery->addValue(true);
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::USERS), "=", $userId]);
$deactivateUserQuery->addAdditionalCapability($whereClause);
$deactivateUserQuery->execute();

$removeApiAuthorizationsQuery = new DeleteQuery();
$removeApiAuthorizationsQuery->setTable(Tables::API_AUTHORIZATIONS);
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USER_ID", Tables::API_AUTHORIZATIONS), "=", $userId]);
$removeApiAuthorizationsQuery->addAdditionalCapability($whereClause);
$removeApiAuthorizationsQuery->execute();

$removeApiKeysQuery = new DeleteQuery();
$removeApiKeysQuery->setTable(Tables::API_KEYS);
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USER_ID", Tables::API_KEYS), "=", $userId]);
$removeApiKeysQuery->addAdditionalCapability($whereClause);
$removeApiKeysQuery->execute();

if ($_SESSION["user"]->isArtist()) {
	$artistId = $_SESSION["user"]->getArtistPageId();

	$deleteArtistQuery = new UpdateQuery();
	$deleteArtistQuery->setTable(Tables::ARTIST_PAGES);
	$deleteArtistQuery->addColumn(new Column("DELETED", Tables::ARTIST_PAGES));
	$deleteArtistQuery->addValue(true);
	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("USER_ID", Tables::ARTIST_PAGES), "=", $userId]);
	$deleteArtistQuery->addAdditionalCapability($whereClause);
	$deleteArtistQuery->execute();
}
$_SESSION = [];

Response::sendSuccessResponse("Success");
