<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables, ReplaceQuery};
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\User\User;

Endpoint::init(true, 0);

FormRepository::getEmailListAdditionForm()->checkServerSide();

$query = new ReplaceQuery();
$query->setTable(Tables::EMAIL_LIST);

$query->addColumn(new Column("EMAIL", Tables::EMAIL_LIST));
$query->addValue($_POST["email"]);

$query->addColumn(new Column("CONTEXT", Tables::EMAIL_LIST));
$query->addValue("Web registration: ".$_POST["context"]);

$query->execute();

Response::sendSuccessResponse("Success");
