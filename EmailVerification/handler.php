<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Redacted\Database\User\EmailVerification;
use \Redacted\Form\FormPHP;
use \Redacted\Response;
use \Redacted\User\User;

FormPHP::checkForm(EmailVerification::getFormStructure());

$user = $_SESSION["user"];

if ($user->emailIsVerified()) {
	\Redacted\Response::send401(EmailVerification::ALREADY_VERIFIED, EmailVerification::PHRASES[EmailVerification::ALREADY_VERIFIED]);
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
