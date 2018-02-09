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

