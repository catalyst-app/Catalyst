<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Database\CommissionType\DeleteCommissionType;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

FormPHP::checkMethod(["method" => "POST"]);

if (User::isLoggedOut()) {
	Response::send401(DeleteCommissionType::NOT_LOGGED_IN, DeleteCommissionType::PHRASES[DeleteCommissionType::NOT_LOGGED_IN]);
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

Response::send200(DeleteCommissionType::PHRASES[DeleteCommissionType::SUCCESS]);
