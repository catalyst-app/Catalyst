<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR . "src/php/initializer.php";
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", "Unimplemented");
define("PAGE_TITLE", "Unimplemented");

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Unimplemented");

?>
<p class="flow-text center">The page you&apos;ve reached is not available.</p>
<?php
require_once Values::FOOTER_INC;
