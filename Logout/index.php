<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Page\{UniversalFunctions, Values};

$_SESSION = [];

define("PAGE_KEYWORD", Values::LOGOUT[0]);
define("PAGE_TITLE", Values::createTitle(Values::LOGOUT[1], []));

define("PAGE_COLOR", Values::DEFAULT_COLOR);

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Logout");
?>
			<div class="section">
				<p class="flow-text">You have been logged out.</p>
				<p class="flow-text">Would you like to <a href="<?= ROOTDIR ?>">return home</a> or <a href="<?= ROOTDIR ?>Login">login again</a>?</a>
			</div>
<?php
require_once Values::FOOTER_INC;
