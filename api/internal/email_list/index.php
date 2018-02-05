<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, Response};
use \Catalyst\Database\{Column, Tables, ReplaceQuery};
use \Catalyst\Email;
use \Catalyst\Form\FormRepository;

Endpoint::init(true, 0);

FormRepository::getEmailListAdditionForm()->checkServerSide();

$query = new ReplaceQuery();
$query->setTable(Tables::EMAIL_LIST);

$query->addColumn(new Column("EMAIL", Tables::EMAIL_LIST));
$query->addValue($_POST["email"]);

$query->addColumn(new Column("CONTEXT", Tables::EMAIL_LIST));
$query->addValue("Web registration: ".$_POST["context"]);

$query->execute();

Email::sendEmail(
	[[$_POST["email"], $_POST["context"]]],
	"Catalyst - Email List Registration",
	'<html><head><style>'.Email::getCss().'</style></head><body><div class="container"><div class="section"><h1 class="center header hide-on-small-only">Email List Registration</h1><h3 class="center header hide-on-med-and-up">Email List Registration</h3></div><div class="section"><p class="flow-text">Thank you for registering for Catalyst\'s email list!  We\'re glad to see you\'re with us, and can\'t wait to keep you updated via email.</p><p>If you did not sign up for this list, contact abuse@catalystapp.co</p></div></div></body></html>',
	implode("\r\n", [
		"Email List Registration",
		"",
		"Thank you for registering for Catalyst\'s email list!  We\'re glad to see you\'re with us, and can\'t wait to keep you updated via email.",
		"",
		"If you did not sign up for this list, contact abuse@catalystapp.co immediately."
	]),
	Email::NO_REPLY_EMAIL,
	Email::NO_REPLY_PASSWORD
);

Response::sendSuccessResponse("Success");
