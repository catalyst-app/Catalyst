<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Database\Character\NewCharacter;
use \Catalyst\Form\FormHTML;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::NEW_CHARACTER[0]);
define("PAGE_TITLE", Values::createTitle(Values::NEW_CHARACTER[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("New Character");

if (User::isLoggedIn()) {
	echo User::getNotLoggedInHtml();
} else {
	echo FormRepository::getNewCharacterForm()->getHtml();
}

require_once Values::FOOTER_INC;
