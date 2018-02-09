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

$_SESSION = [];

Response::sendSuccessResponse("Success");
