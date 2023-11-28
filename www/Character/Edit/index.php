<?php

define("ROOTDIR", "/");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\Character\Character;
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

$id = $character = $pendingCharacter = null;
if (User::isLoggedIn()) {
	if (array_key_exists("q", $_GET)) {
		$id = Character::getIdFromToken($_GET["q"]);
		if ($id !== -1) {
			$pendingCharacter = new Character($id);
			if ($pendingCharacter->getOwnerId() == $_SESSION["user"]->getId()) {
				$character = $pendingCharacter;
			} else {
				HTTPCode::set(403);
			}
		} else {
			HTTPCode::set(404);
		}
	} else {
		HTTPCode::set(404);
	}
} else {
	HTTPCode::set(401);
}

define("PAGE_KEYWORD", Values::EDIT_CHARACTER[0]);
define("PAGE_TITLE", Values::createTitle(Values::EDIT_CHARACTER[1], ["name" => (isset($character) ? $character->getName() : "Invalid Character")]));

if (!is_null($character)) {
	define("PAGE_COLOR", $character->getColor());
} elseif (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Edit Character");

if (!User::isLoggedIn()):
	echo User::getNotLoggedInHtml();
elseif (is_null($pendingCharacter)): ?>
			<div class="section">
				<p class="flow-text">This character does not exist.</p>
			</div>
<?php elseif (is_null($character)): ?>
			<div class="section">
				<p class="flow-text">You aren't allowed to do that.</p>
			</div>
<?php
else:
?>
			<input type="hidden" id="character-token" value="<?= htmlspecialchars($character->getToken()) ?>">
<?php
	echo FormRepository::getEditCharacterForm($character)->getHtml();
	echo FormRepository::getDeleteCharacterForm()->getHtml();
endif;

require_once Values::FOOTER_INC;
