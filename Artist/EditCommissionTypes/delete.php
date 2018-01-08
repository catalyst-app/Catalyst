<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Redacted\Database\CommissionType\DeleteCommissionType;
use \Redacted\Form\FormPHP;
use \Redacted\Response;
use \Redacted\User\User;

FormPHP::checkMethod(["method" => "POST"]);

if (User::isLoggedOut()) {
	Response::send401(DeleteCommissionType::NOT_LOGGED_IN, DeleteCommissionType::PHRASES[DeleteCommissionType::NOT_LOGGED_IN]);
}

if (empty($_POST)) {
	Response::send401(DeleteCommissionType::IMAGES_INVALID, DeleteCommissionType::PHRASES[DeleteCommissionType::IMAGES_INVALID]);
}

if (!$_SESSION["user"]->isArtist()) {
	Response::send401(DeleteCommissionType::NOT_AN_ARTIST, DeleteCommissionType::PHRASES[DeleteCommissionType::NOT_AN_ARTIST]);
}

if (!isset($_POST["id"]) || empty($_POST["id"]) || !is_numeric($_POST["id"]) || !preg_match('/^\d+$/', $_POST["id"])) {
	Response::send401(DeleteCommissionType::ID_INVALID, DeleteCommissionType::PHRASES[DeleteCommissionType::ID_INVALID]);
}

$result = DeleteCommissionType::delete(
	$_POST["id"],
	$_SESSION["user"]->getArtistPage()
);

if ($result == DeleteCommissionType::ERROR_UNKNOWN) {
	Response::send500(DeleteCommissionType::PHRASES[DeleteCommissionType::ERROR_UNKNOWN].DeleteCommissionType::$lastErrId, DeleteCommissionType::ERROR_UNKNOWN);
}

if ($result != DeleteCommissionType::SUCCESS) {
	Response::send401($result, DeleteCommissionType::PHRASES[$result]);
}

Response::send200(DeleteCommissionType::SUCCESS);
