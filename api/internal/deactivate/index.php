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

$_SESSION = [];

Response::sendSuccessResponse("Success");
