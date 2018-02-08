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

$query = new SelectQuery();
$query->setTable(Tables::USERS);

$query->addColumn(new Column("ID", Tables::USERS));

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USERNAME", Tables::USERS), "=", $_POST["username"]]);
$query->addAdditionalCapability($whereClause);

$query->execute();

$result = $query->getResult();

if (count($result) != 0) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90503, ErrorCodes::ERR_90103);
}
