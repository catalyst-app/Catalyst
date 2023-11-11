<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR . "src/php/initializer.php";
use \Catalyst\API\{Endpoint, Response};
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\Query\ReplaceQuery;
use \Catalyst\Email\Email;
use \Catalyst\Form\FormRepository;
use \Catalyst\Secrets;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_NONE);

FormRepository::getEmailListAdditionForm()->checkServerSide();

$stmt = new ReplaceQuery();
$stmt->setTable(Tables::EMAIL_LIST);

$stmt->addColumn(new Column("EMAIL", Tables::EMAIL_LIST));
$stmt->addValue($_POST["email"]);

$stmt->addColumn(new Column("CONTEXT", Tables::EMAIL_LIST));
$stmt->addValue("Web registration: " . $_POST["context"]);

$stmt->execute();

Email::sendEmail(
	[[$_POST["email"], $_POST["context"]]],
	"Catalyst - Email List Registration",
	'<html><head><style>' . Email::getCss() . '</style></head><body><div class="container"><div class="section"><h1 class="center header hide-on-small-only">Email List Registration</h1><h3 class="center header hide-on-med-and-up">Email List Registration</h3></div><div class="section"><p class="flow-text">Thank you for registering for Catalyst\'s email list!  We\'re glad to see you\'re with us, and can\'t wait to keep you updated via email.</p>' . ($_POST["request-info"] == "true" ? '<p class="flow-text">A staff member will contact you within the next 48 hours with additional information.</p>' : '') . '<p>If you did not sign up for this list, please contact abuse@catalystapp.co</p></div></div></body></html>',
	implode("\r\n", [
		"Email List Registration",
		"",
		"Thank you for registering for Catalyst's email list!  We're glad to see you're with us, and can't wait to keep you updated via email.",
		($_POST["request-info"] == "true" ? "\r\nA staff member will contact you within the next 48 hours with additional information.\r\n" : ""),
		"If you did not sign up for this list, please contact abuse@catalystapp.co."
	]),
	Email::NO_REPLY_EMAIL,
	Secrets::get("NO_REPLY_PASSWORD")
);

if ($_POST["request-info"] == "true") {
	Email::sendEmail(
		[["catalyst@catalystapp.co", "Email list information request"]],
		"Email List Information Request",
		'<html><head><style>' . Email::getCss() . '</style></head><body><div class="container"><div class="section"><h1 class="center header hide-on-small-only">Email List Information Request</h1><h3 class="center header hide-on-med-and-up">Email List Information Request</h3></div><div class="section"><p class="flow-text">' . htmlspecialchars($_POST["email"]) . ': ' . htmlspecialchars($_POST["context"]) . '</p></div></div></body></html>',
		implode("\r\n", [
			"Email List Registration",
			"",
			$_POST["email"],
			"",
			$_POST["context"],
		]),
		Email::NO_REPLY_EMAIL,
		Secrets::get("NO_REPLY_PASSWORD")
	);
}

Response::sendSuccess("Success");
