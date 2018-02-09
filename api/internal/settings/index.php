<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, InsertQuery, SelectQuery, Tables, WhereClause};
use \Catalyst\{Email, HTTPCode, Tokens};
use \Catalyst\Form\{FileUpload, FormRepository};
use \Catalyst\Page\Values;
use \Catalyst\User\User;

Endpoint::init(true, 1);

FormRepository::getSettingsForm()->checkServerSide();

$id = $_SESSION["user"]->getId();


// check username is free/own
$query = new SelectQuery();
$query->setTable(Tables::USERS);
$query->addColumn(new Column("ID", Tables::USERS));
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USERNAME", Tables::USERS), "=", $_POST["username"]]);
$whereClause->addToClause(WhereClause::AND);
$whereClause->addToClause([new Column("ID", Tables::USERS), "!=", $id]);
$query->addAdditionalCapability($whereClause);
$query->execute();

if (count($query->getResult()) != 0) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90503, ErrorCodes::ERR_90503);
}

// check email is free/own
if (!empty($_POST["email"])) {
	$query = new SelectQuery();
	$query->setTable(Tables::USERS);
	$query->addColumn(new Column("ID", Tables::USERS));
	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("EMAIL", Tables::USERS), "=", $_POST["email"]]);
	$whereClause->addToClause(WhereClause::AND);
	$whereClause->addToClause([new Column("ID", Tables::USERS), "!=", $id]);
	$query->addAdditionalCapability($whereClause);
	$query->execute();
	if (count($query->getResult()) != 0) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90503, ErrorCodes::ERR_90503);
	}
	if (strpos($_POST["email"], "@catalystapp.co") !== false) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90523, ErrorCodes::ERR_90523);
	}
}

$query = new SelectQuery();
$query->setTable(Tables::USERS);
$query->addColumn(new Column("FILE_TOKEN", Table::USERS));
$query->addColumn(new Column("USERNAME", Table::USERS));
$query->addColumn(new Column("HASHED_PASSWORD", Table::USERS));
$query->addColumn(new Column("PASSWORD_RESET_TOKEN", Table::USERS));
$query->addColumn(new Column("TOTP_KEY", Table::USERS));
$query->addColumn(new Column("TOTP_RESET_TOKEN", Table::USERS));
$query->addColumn(new Column("EMAIL", Table::USERS));
$query->addColumn(new Column("EMAIL_VERIFIED", Table::USERS));
$query->addColumn(new Column("EMAIL_TOKEN", Table::USERS));
$query->addColumn(new Column("PICTURE_LOC", Table::USERS));
$query->addColumn(new Column("PICTURE_NSFW", Table::USERS));
$query->addColumn(new Column("NSFW", Table::USERS));
$query->addColumn(new Column("COLOR", Table::USERS));
$query->addColumn(new Column("NICK", Table::USERS));
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::USERS), "=", $id]);
$query->addAdditionalCapability($whereClause);
$query->execute();

$user = $query->result()[0];

if (!password_verify($oldPassword, $user["HASHED_PASSWORD"])) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90522, ErrorCodes::ERR_90522);
}

