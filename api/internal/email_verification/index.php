<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\UpdateQuery;
use \Catalyst\{Email, HTTPCode};
use \Catalyst\Form\FormRepository;
use \Catalyst\User\User;

Endpoint::init(true, 1);

FormRepository::getEmailVerificationForm()->checkServerSide();

if ($_SESSION["user"]->emailIsVerified()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90405, ErrorCodes::ERR_90405);
}

if ($_POST["token"] != $_SESSION["user"]->getEmailToken()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90402, ErrorCodes::ERR_90402);
}

$stmt = new UpdateQuery();
$stmt->setTable(Tables::USERS);

$stmt->addColumn(new Column("EMAIL_VERIFIED", Tables::USERS));
$stmt->addValue(1);

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::USERS), "=", $_SESSION["user"]->getId()]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

Response::sendSuccessResponse("Success");
