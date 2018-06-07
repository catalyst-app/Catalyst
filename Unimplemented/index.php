<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\HTTPCode;
use \Catalyst\User\User;

define("PAGE_KEYWORD", "Unimplemented");
define("PAGE_TITLE", "Unimplemented");

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

HTTPCode::set(501);

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Unimplemented");

?>
		<p class="flow-text center">We haven't gotten here yet, but we hope to soon!  We're <em>hard at work</em> to bring you the newest features!  Watch our <a href="https://trello.com/b/X37KEv4A/catalyst" target="_blank">trello</a> for updates.</p>
<?php
require_once Values::FOOTER_INC;
