<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Database\User\EmailVerification;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

FormPHP::checkForm(EmailVerification::getFormStructure());

$user = $_SESSION["user"];

if ($user->emailIsVerified()) {
	\Catalyst\Response::send401(EmailVerification::ALREADY_VERIFIED, EmailVerification::PHRASES[EmailVerification::ALREADY_VERIFIED]);
}

$result = EmailVerification::verify(
	$user,
	$_POST["token"]
);

if ($result == EmailVerification::ERROR_UNKNOWN) {
	Response::send500(EmailVerification::PHRASES[EmailVerification::ERROR_UNKNOWN].EmailVerification::$lastErrId, EmailVerification::ERROR_UNKNOWN);
}

if ($result != EmailVerification::VERIFIED) {
	Response::send401($result, EmailVerification::PHRASES[$result]);
}

Response::send200(EmailVerification::PHRASES[EmailVerification::VERIFIED]);
