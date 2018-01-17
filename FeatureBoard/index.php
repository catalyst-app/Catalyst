<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Database\FeatureBoard\Groups;
use \Catalyst\Database\FeatureBoard\Item;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::FEATURE_BOARD[0]);
define("PAGE_TITLE", Values::createTitle(Values::FEATURE_BOARD[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Feature Board");
?>
<?php
require_once Values::FOOTER_INC;
