<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Database\User\Deactivate;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

if (User::isLoggedOut()) {
	\Catalyst\Response::send401(Deactivate::ERROR_UNKNOWN, Deactivate::PHRASES[Deactivate::ERROR_UNKNOWN]);
}

FormPHP::checkForm(Deactivate::getFormStructure());

$username = $_POST["username"];
$password = $_POST["password"];

$result = Deactivate::deactivate(
	$username,
	$password
);

if ($result == Deactivate::ERROR_UNKNOWN) {
	Response::send500(Deactivate::PHRASES[Deactivate::ERROR_UNKNOWN].Deactivate::$lastErrId, Deactivate::ERROR_UNKNOWN);
}

if ($result != Deactivate::DEACTIVATED) {
	Response::send401($result, Deactivate::PHRASES[$result]);
}

Response::send200(Deactivate::PHRASES[Deactivate::DEACTIVATED]);
