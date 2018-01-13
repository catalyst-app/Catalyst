<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Character\Character;
use \Catalyst\Database\Character\EditCharacter;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

FormPHP::checkMethod(["method" => "POST"]);

if (User::isLoggedOut() || !isset($_POST["body"]) || empty($_POST["body"]) || !isset($_POST["body"]) || empty($_POST["body"])) {
	Response::send401(EditCharacter::ERROR_UNKNOWN, EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN]);
}

$body = json_decode($_POST["body"]);

if ($body === false) {
	Response::send401(EditCharacter::ERROR_UNKNOWN, EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN]);
}

foreach ($body as $row) {
	if (count($row) != 4) {
		Response::send401(EditCharacter::ERROR_UNKNOWN, EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN]);
	}
	if (strlen($row[1]) >= 255) {
		Response::send401(EditCharacter::ERROR_UNKNOWN, EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN]);
	}
	if (!is_bool($row[2]) || !is_bool($row[3])) {
		Response::send401(EditCharacter::ERROR_UNKNOWN, EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN]);
	}
}

$characterId = Character::getIdFromToken($_POST["token"]);
if ($characterId !== -1) {
	$characterObj = new Character($characterId);
	if ($characterObj->getOwnerId() == $_SESSION["user"]->getId()) {
		$character = $characterObj;
	}
}

if (!isset($character)) {
	Response::send401(EditCharacter::ERROR_UNKNOWN, EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN]);
}

$response = EditCharacter::updateImages($character, $body);

if ($response != EditCharacter::SUCCESS) {
	Response::send401(EditCharacter::ERROR_UNKNOWN, EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN]);
}

Response::send200(EditCharacter::PHRASES[EditCharacter::SUCCESS]);
