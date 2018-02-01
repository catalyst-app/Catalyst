<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Character\Character;
use \Catalyst\Database\Character\EditCharacter;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

if (User::isLoggedOut()) {
	\Catalyst\Response::send401(EditCharacter::ERROR_UNKNOWN, EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN]);
}

$characterId = Character::getIdFromToken($_POST["token"]);
$character = null;
if ($characterId !== -1) {
	$characterObj = new Character($characterId);
	if ($characterObj->getOwnerId() == $_SESSION["user"]->getId()) {
		$character = $characterObj;
	} else {
		Response::send401(EditCharacter::ERROR_UNKNOWN, EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN]);
	}
} else {
	Response::send401(EditCharacter::ERROR_UNKNOWN, EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN]);
}

if (is_null($character)) {
	Response::send401(EditCharacter::ERROR_UNKNOWN, EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN]);
}

$result = EditCharacter::delete(
	$character
);

if ($result == EditCharacter::ERROR_UNKNOWN) {
	Response::send500(EditCharacter::PHRASES[EditCharacter::ERROR_UNKNOWN].EditCharacter::$lastErrId, EditCharacter::ERROR_UNKNOWN);
}

Response::send200(EditCharacter::PHRASES[EditCharacter::SUCCESS]);
