<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Database\User\Settings;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

if (User::isLoggedOut()) {
	\Catalyst\Response::send401(Settings::ERROR_UNKNOWN, Settings::PHRASES[Settings::ERROR_UNKNOWN]);
}

FormPHP::checkForm(Settings::getFormStructure());

$username = $_POST["username"];
$password = $_POST["password"];

$result = Settings::update(
	$_POST["username"],
	$_POST["password"],
	$_POST["totp"] === "true",
	$_POST["email"],
	$_POST["nickname"],
	$_POST["color"],
	$_POST["nsfw"] === "true",
	isset($_FILES["pfp"]) ? $_FILES["pfp"] : null,
	$_POST["pfpnsfw"] === "true",
	$_POST["oldpassword"]
);

if ($result == Settings::ERROR_UNKNOWN) {
	Response::send500(Settings::PHRASES[Settings::ERROR_UNKNOWN].Settings::$lastErrId, Settings::ERROR_UNKNOWN);
}

if ($result != Settings::UPDATED) {
	Response::send401($result, Settings::PHRASES[$result]);
}

Response::send200(Settings::PHRASES[Settings::UPDATED]);
