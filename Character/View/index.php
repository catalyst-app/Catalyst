<?php

define("ROOTDIR", "../../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Character\Character;
use \Catalyst\HTTPCode;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

$id = $character = null;
if (isset($_GET["q"])) {
	$id = Character::getIdFromToken($_GET["q"]);
	if ($id !== -1) {
		$pendingCharacter = new Character($id);
		if ($pendingCharacter->visibleToMe()) {
			$character = $pendingCharacter;
		}
	} else {
		HTTPCode::set(400);
	}
	HTTPCode::set(400);
}

define("PAGE_KEYWORD", Values::VIEW_CHARACTER[0]);
define("PAGE_TITLE", Values::createTitle(Values::VIEW_CHARACTER[1], ["name" => (isset($character) ? $character->getName() : "Invalid Character")]));

if (!is_null($character)) {
	define("PAGE_COLOR", $character->getColor());
} elseif (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Character");

?>
