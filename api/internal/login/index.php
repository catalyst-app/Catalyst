<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables, SelectQuery, UpdateQuery, WhereClause};
use \Catalyst\{Email, HTTPCode};
use \Catalyst\Form\FormRepository;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

Endpoint::init(true, 2);

FormRepository::getLoginForm()->checkServerSide();

$stmt = new SelectQuery();
$stmt->setTable(Tables::USERS);

$stmt->addColumn(new Column("ID", Tables::USERS));
$stmt->addColumn(new Column("HASHED_PASSWORD", Tables::USERS));
$stmt->addColumn(new Column("TOTP_KEY", Tables::USERS));
$stmt->addColumn(new Column("SUSPENDED", Tables::USERS));
$stmt->addColumn(new Column("DEACTIVATED", Tables::USERS));

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USERNAME", Tables::USERS), "=", $_POST["username"]]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

$result = $stmt->getResult();

if (count($result) == 0) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90103, ErrorCodes::ERR_90103);
}

if (!password_verify($_POST["password"], $result[0]["HASHED_PASSWORD"])) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90105, ErrorCodes::ERR_90105);
}

// suspension/deactivation is serious, and should be displayed only when password is correct
if ($result[0]["SUSPENDED"]) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90108, ErrorCodes::ERR_90108);
}
if ($result[0]["DEACTIVATED"]) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90109, ErrorCodes::ERR_90109);
}

if (password_needs_rehash($result[0]["HASHED_PASSWORD"], PASSWORD_BCRYPT, ["cost" => Values::BCRYPT_COST])) {
	$stmt = new UpdateQuery();

	$stmt->setTable(Tables::USERS);

	$stmt->addColumn(new Column("HASHED_PASSWORD", Tables::USERS));
	$stmt->addValue(password_hash($_POST["password"], PASSWORD_BCRYPT, ["cost" => Values::BCRYPT_COST]));

	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("ID", Tables::USERS), '=', $result[0]["ID"]]);
	$stmt->addAdditionalCapability($whereClause);

	$stmt->execute();
}

if ($result[0]["TOTP_KEY"] !== null) {
	$_SESSION["pending_user"] = new User($result[0]["ID"]);
	HTTPCode::set(400);
	Response::sendErrorResponse(90110, ErrorCodes::ERR_90110);
}

$_SESSION["user"] = new User($result[0]["ID"]);

Response::sendSuccessResponse("Success");
