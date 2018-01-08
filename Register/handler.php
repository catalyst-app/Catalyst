<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Redacted\Database\User\Register;
use \Redacted\Form\Captcha;
use \Redacted\Form\FormPHP;
use \Redacted\Response;
use \Redacted\User\User;

if (User::isLoggedIn()) {
	\Redacted\Response::send401(Register::ALREADY_LOGGED_IN, Register::PHRASES[Register::ALREADY_LOGGED_IN]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	\Redacted\Response::send405('GET is not a valid method for this POST endpoint.');
}

if (empty($_POST)) {
	\Redacted\Response::send401(Register::PICTURE_INVALID, Register::PHRASES[Register::PICTURE_INVALID]);
}

FormPHP::checkForm(Register::getFormStructure());

if (!Register::usernameAvailable($_POST["username"])) {
	\Redacted\Response::send401(Register::USERNAME_EXISTS, Register::PHRASES[Register::USERNAME_EXISTS]);
}

if (!Register::emailAvailable($_POST["email"])) {
	\Redacted\Response::send401(Register::EMAIL_EXISTS, Register::PHRASES[Register::EMAIL_EXISTS]);
}

$result = Register::register(
	$_POST["username"],
	$_POST["password"],
	$_POST["email"],
	$_POST["nickname"],
	$_POST["color"],
	$_POST["nsfw"] === "true",
	isset($_FILES["pfp"]) ? $_FILES["pfp"] : null,
	$_POST["pfpnsfw"] === "true"
);

if ($result == Register::ERROR_UNKNOWN) {
	Response::send500(Register::PHRASES[Register::ERROR_UNKNOWN].Register::$lastErrId, Register::ERROR_UNKNOWN);
}

Response::send201(Register::PHRASES[Register::ACCOUNT_CREATED]);
