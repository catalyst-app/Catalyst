<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

$_SESSION = [];

define("PAGE_KEYWORD", Values::LOGOUT[0]);
define("PAGE_TITLE", Values::createTitle(Values::LOGOUT[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColorHex());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Logout");
?>
			<div class="section">
				<p class="flow-text">You have been logged out.  <a href="<?= ROOTDIR ?>">Return home</p></a>
			</div>
<?php
require_once Values::FOOTER_INC;
