<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\{SelectQuery, UpdateQuery};
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;

Endpoint::init(true, 1);

FormRepository::getUndeleteArtistPageForm()->checkServerSide();

if ($_SESSION["user"]->isArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91101, ErrorCodes::ERR_91101);
}
if (!$_SESSION["user"]->wasArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91102, ErrorCodes::ERR_91102);
}

$stmt = new UpdateQuery();

$stmt->setTable(Tables::ARTIST_PAGES);

$stmt->addColumn(new Column("DELETED", Tables::ARTIST_PAGES));
$stmt->addValue(0);

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USER_ID", Tables::ARTIST_PAGES), '=', $_SESSION["user"]->getId()]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();


$stmt = new SelectQuery();

$stmt->setTable(Tables::ARTIST_PAGES);

$stmt->addColumn(new Column("ID", Tables::ARTIST_PAGES));

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USER_ID", Tables::ARTIST_PAGES), '=', $_SESSION["user"]->getId()]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

$aid = $stmt->getResult()[0]["ID"];


$stmt = new UpdateQuery();

$stmt->setTable(Tables::USERS);

$stmt->addColumn(new Column("ARTIST_PAGE_ID", Tables::USERS));
$stmt->addValue($aid);

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::USERS), '=', $_SESSION["user"]->getId()]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

Response::sendSuccessResponse("Success");
