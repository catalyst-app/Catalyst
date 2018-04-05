<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\{JoinClause, WhereClause};
use \Catalyst\Database\Query\{DeleteQuery, SelectQuery, UpdateQuery};
use \Catalyst\Images\{Image, Folders};
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;

Endpoint::init(true, 1);

FormRepository::getNewCommissionTypeForm()->checkServerSide($_POST);

if (!$_SESSION["user"]->isArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91540, ErrorCodes::ERR_91540);
}

trigger_error(json_encode($_POST, false, JSON_PRETTY_PRINT), E_USER_ERROR);

// do magic

Response::sendSuccessResponse("Success");
