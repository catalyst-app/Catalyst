<?php

define("ROOTDIR", "");
define("REAL_ROOTDIR", "");

require_once REAL_ROOTDIR."includes/init.php";
use \Redacted\Page\UniversalFunctions;
use \Redacted\Page\Values;
use \Redacted\User\User;


define("PAGE_KEYWORD", Values::HOME[0]);
define("PAGE_TITLE", Values::createTitle(Values::HOME[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Home");
?>
			<div class="section">
				<h3>Go to <a href="https://github.com/smileytechguy/Redacted">the roadmap/info page</a></h3>
				<?= var_dump($_SESSION); ?>
			</div>
<?php
require_once Values::FOOTER_INC;
 
 
