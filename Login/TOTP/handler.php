<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Database\User\TOTPLogin;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

if (User::isLoggedIn()) {
	\Catalyst\Response::send401(TOTPLogin::ALREADY_LOGGED_IN, TOTPLogin::PHRASES[TOTPLogin::ALREADY_LOGGED_IN]);
}

FormPHP::checkForm(TOTPLogin::getFormStructure());

$result = TOTPLogin::login(
	$_POST["token"]
);

if ($result == TOTPLogin::ERROR_UNKNOWN) {
	Response::send500(TOTPLogin::PHRASES[TOTPLogin::ERROR_UNKNOWN].TOTPLogin::$lastErrId, TOTPLogin::ERROR_UNKNOWN);
}

if ($result != TOTPLogin::LOGGED_IN) {
	Response::send401($result, TOTPLogin::PHRASES[$result]);
}

Response::send200(TOTPLogin::PHRASES[TOTPLogin::LOGGED_IN]);
