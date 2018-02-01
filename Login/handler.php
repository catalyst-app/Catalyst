<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Database\User\Login;
use \Catalyst\Form\Captcha;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

if (User::isLoggedIn()) {
	\Catalyst\Response::send401(Login::ALREADY_LOGGED_IN, Login::PHRASES[Login::ALREADY_LOGGED_IN]);
}

FormPHP::checkForm(Login::getFormStructure());

$username = $_POST["username"];
$password = $_POST["password"];

$result = Login::login(
	$username,
	$password
);

if ($result == Login::ERROR_UNKNOWN) {
	Response::send500(Login::PHRASES[Login::ERROR_UNKNOWN].Login::$lastErrId, Login::ERROR_UNKNOWN);
}

if ($result != Login::CREDENTIALS_VALID) {
	Response::send401($result, Login::PHRASES[$result]);
}

Response::send200(Login::PHRASES[Login::CREDENTIALS_VALID]);
