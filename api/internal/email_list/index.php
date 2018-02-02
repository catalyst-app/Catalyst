<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, Response};
use \Catalyst\Database\{Column, Tables, ReplaceQuery};
use \Catalyst\HTTPCode;
use \Catalyst\User\User;

Endpoint::init(true, 0);

if (!isset($_POST["email"])) {
	Response::sendErrorResponse(90001, "Email was not passed");
}
if (!preg_match('/^.{2,}@.{2,}\..{2,}$/', $_POST["email"])) {
	Response::sendErrorResponse(90002, "Email is invalid");
}
if (!isset($_POST["context"])) {
	Response::sendErrorResponse(90003, "Context was not passed");
}

$query = new ReplaceQuery();
$query->setTable(Tables::EMAIL_LIST);

$query->addColumn(new Column("EMAIL", Tables::EMAIL_LIST));
$query->addValue($_POST["email"]);

$query->addColumn(new Column("CONTEXT", Tables::EMAIL_LIST));
$query->addValue("Web registration: ".$_POST["context"]);

$query->execute();

Response::sendSuccessResponse("Success");
