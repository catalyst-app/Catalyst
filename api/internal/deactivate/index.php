<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Database, Tables};
use \Catalyst\Database\QueryAddition\{JoinClause, WhereClause};
use \Catalyst\Database\Query\{DeleteQuery, SelectQuery, UpdateQuery};
use \Catalyst\HTTPCode;
use \Catalyst\Form\FormRepository;
use \Catalyst\User\User;

Endpoint::init(true, 1);

FormRepository::getDeactivateForm()->checkServerSide();

if (strtolower($_POST["username"]) != strtolower($_SESSION["user"]->getUsername())) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90602, ErrorCodes::ERR_90602);
}

if (!password_verify($_POST["password"], $result[0]["HASHED_PASSWORD"])) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90604, ErrorCodes::ERR_90604);
}

$_SESSION["user"]->delete();

$_SESSION = [];

Response::sendSuccessResponse("Success");
