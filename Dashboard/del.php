<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Redacted\Database\SocialMedia;
use \Redacted\Response;
use \Redacted\User\User;

if (User::isLoggedOut() || !isset($_POST["id"]) || !is_numeric($_POST["id"])) {
	Response::send500(SocialMedia::PHRASES[SocialMedia::ERROR_UNKNOWN], SocialMedia::ERROR_UNKNOWN);
}

$id = @(int)$_POST["id"];

$user = $_SESSION["user"];

$result = SocialMedia::delFromUser(
	$user,
	$id
);

if ($result == SocialMedia::ERROR_UNKNOWN) {
	Response::send500(SocialMedia::PHRASES[SocialMedia::ERROR_UNKNOWN].SocialMedia::$lastErrId, SocialMedia::ERROR_UNKNOWN);
}

if ($result != SocialMedia::SUCCESS) {
	Response::send401($result, SocialMedia::PHRASES[$result]);
}

Response::send200(SocialMedia::PHRASES[SocialMedia::SUCCESS]);
