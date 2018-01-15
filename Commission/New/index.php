<?php

define("ROOTDIR", "../../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

if (isset($_GET["q"])) {
	$id = CommissionType::getIdFromToken($_GET["q"]);
	if ($id != -1) {
		$type = new CommissionType($id);
	}
}

define("PAGE_KEYWORD", Values::NEW_COMMISSION[0]);
define("PAGE_TITLE", Values::createTitle(Values::NEW_COMMISSION[1], ["type" => (isset($type) ? $type->getName() : "Invalid"), "artist" => (isset($type) ? $type->getArtistPage()->getName() : "Invalid")]));

if (isset($type)) {
	define("PAGE_COLOR", $type->getArtistPage()->getColor());
} elseif (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("New Commission");

?>
<?php if (!isset($type)): ?>
	No exist
<?php elseif (!$type->isOpen()): ?>
	no open
<?php else: ?>
	ayyy
<?php endif; ?>
<?php
require_once Values::FOOTER_INC;
