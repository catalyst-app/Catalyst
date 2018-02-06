<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables, SelectQuery, WhereClause};
use \Catalyst\{Email, HTTPCode};
use \Catalyst\Form\FormRepository;
use \Catalyst\User\User;

Endpoint::init(true, 2);

FormRepository::getLoginForm()->checkServerSide();

$query = new SelectQuery();
$query->setTable(Tables::USERS);

$query->addColumn(new Column("ID", Tables::USERS));
$query->addColumn(new Column("HASHED_PASSWORD", Tables::USERS));
$query->addColumn(new Column("TOTP_KEY", Tables::USERS));
$query->addColumn(new Column("SUSPENDED", Tables::USERS));
$query->addColumn(new Column("DEACTIVATED", Tables::USERS));

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USERNAME", Tables::USERS), "=", $_POST["username"]]);
$query->addAdditionalCapability($whereClause);

$query->execute();

$result = $query->getResult();

// all the HTTP codes here are 401 as this is a login - they will be denied or granted access based on outcome
if (count($result) == 0) {
	HTTPCode::set(401);
	Response::sendErrorResponse(90103, ErrorCodes::ERR_90103);
}

if (!password_verify($_POST["password"], $result[0]["HASHED_PASSWORD"])) {
	HTTPCode::set(401);
	Response::sendErrorResponse(90105, ErrorCodes::ERR_90105);
}

// suspension/deactivation is serious, and should be displayed only when password is correct
if ($result[0]["SUSPENDED"]) {
	HTTPCode::set(401);
	Response::sendErrorResponse(90108, ErrorCodes::ERR_90108);
}
if ($result[0]["DEACTIVATED"]) {
	HTTPCode::set(401);
	Response::sendErrorResponse(90109, ErrorCodes::ERR_90109);
}

if ($result[0]["TOTP_KEY"] !== null) {
	$_SESSION["pending_user"] = new User($result[0]["ID"]);
	HTTPCode::set(401);
	Response::sendErrorResponse(90110, ErrorCodes::ERR_90110);
}

$_SESSION["user"] = new User($result[0]["ID"]);

Response::sendSuccessResponse("Success");
