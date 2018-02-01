<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Database\CommissionType\EditCommissionType;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

FormPHP::checkMethod(["method" => "POST"]);

if (User::isLoggedOut() || !isset($_POST["body"]) || empty($_POST["body"]) || !isset($_POST["body"]) || empty($_POST["body"])) {
	Response::send401(EditCommissionType::ERROR_UNKNOWN, EditCommissionType::PHRASES[EditCommissionType::ERROR_UNKNOWN]);
}

$body = json_decode($_POST["body"]);

if ($body === false) {
	Response::send401(EditCommissionType::ERROR_UNKNOWN, EditCommissionType::PHRASES[EditCommissionType::ERROR_UNKNOWN]);
}

foreach ($body as $row) {
	if (count($row) != 4) {
		Response::send401(EditCommissionType::ERROR_UNKNOWN, EditCommissionType::PHRASES[EditCommissionType::ERROR_UNKNOWN]);
	}
	if (strlen($row[1]) >= 255) {
		Response::send401(EditCommissionType::ERROR_UNKNOWN, EditCommissionType::PHRASES[EditCommissionType::ERROR_UNKNOWN]);
	}
	if (!is_bool($row[2]) || !is_bool($row[3])) {
		Response::send401(EditCommissionType::ERROR_UNKNOWN, EditCommissionType::PHRASES[EditCommissionType::ERROR_UNKNOWN]);
	}
}

$ctid = CommissionType::getIdFromToken($_POST["token"]);
$type = null;
if ($ctid !== -1) {
	$type = new CommissionType($ctid);
	if ($type->getArtistPageId() != $_SESSION["user"]->getArtistPageId()) {
		Response::send401(EditCommissionType::ERROR_UNKNOWN, EditCommissionType::PHRASES[EditCommissionType::ERROR_UNKNOWN]);
	}
} else {
	Response::send401(EditCommissionType::ERROR_UNKNOWN, EditCommissionType::PHRASES[EditCommissionType::ERROR_UNKNOWN]);
}

if (is_null($type)) {
	Response::send401(EditCommissionType::ERROR_UNKNOWN, EditCommissionType::PHRASES[EditCommissionType::ERROR_UNKNOWN]);
}

$response = EditCommissionType::updateImages($type, $body);

if ($response != EditCommissionType::SUCCESS) {
	Response::send401(EditCommissionType::ERROR_UNKNOWN, EditCommissionType::PHRASES[EditCommissionType::ERROR_UNKNOWN]);
}

Response::send200(EditCommissionType::PHRASES[EditCommissionType::SUCCESS]);
