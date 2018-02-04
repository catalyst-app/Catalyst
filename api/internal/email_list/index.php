<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables, ReplaceQuery};
use \Catalyst\HTTPCode;
use \Catalyst\User\User;

Endpoint::init(true, 0);

if (!isset($_POST["email"])) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90001, ErrorCodes::ERR_90001);
}
if (!preg_match('/^.{2,}@.{2,}\..{2,}$/', $_POST["email"])) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90002, ErrorCodes::ERR_90002);
}
if (!isset($_POST["context"])) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90003, ErrorCodes::ERR_90003);
}

$query = new ReplaceQuery();
$query->setTable(Tables::EMAIL_LIST);

$query->addColumn(new Column("EMAIL", Tables::EMAIL_LIST));
$query->addValue($_POST["email"]);

$query->addColumn(new Column("CONTEXT", Tables::EMAIL_LIST));
$query->addValue("Web registration: ".$_POST["context"]);

$query->execute();

Response::sendSuccessResponse("Success");
