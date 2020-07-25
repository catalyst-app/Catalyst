<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Page\Values;
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::STATUS[0]);
define("PAGE_TITLE", Values::createTitle(Values::STATUS[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("System Status");
?>
	<p class="flow-text">
		<strong>CPU Usage:</strong> <img src="<?= ROOTDIR ?>api/internal/stats/?badge=cpu"></img>
	</p>

	<p><i>We hope to add more useful information to this page soon!</i></p>
<?php
require_once Values::FOOTER_INC;
