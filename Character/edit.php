<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Character\Character;
use \Catalyst\Database\Character\EditCharacter;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

if (!User::isLoggedIn()) {
	\Catalyst\Response::send401(EditCharacter::ERROR_UNKNOWN, EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN]);
}

if (empty($_POST)) {
	\Catalyst\Response::send401(EditCharacter::PICTURE_INVALID, EditCharacter::PHRASES[EditCharacter::PICTURE_INVALID]);
}

FormPHP::checkForm(EditCharacter::getFormStructure());

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

$result = EditCharacter::update(
	$character,
	$_POST["name"],
	$_POST["desc"],
	isset($_FILES["imgs"]) ? $_FILES["imgs"] : null,
	$_POST["color"],
	$_POST["public"] === "true"
);

if ($result == EditCharacter::ERROR_UNKNOWN) {
	Response::send500(EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN].EditCharacter::$lastErrId, EditCharacter::ERROR_UNKNOWN);
}

Response::send200(EditCharacter::PHRASES[EditCharacter::SUCCESS]);
