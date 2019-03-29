<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::REGISTER[0]);
define("PAGE_TITLE", Values::createTitle(Values::REGISTER[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

if (User::isLoggedIn()) {
	HTTPCode::set(401);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Register");

if (User::isLoggedIn()):
?>
	<p class="flow-text">You are already logged in.</p>
	<p class="flow-text">Go to your <a href="<?= ROOTDIR ?>Dashboard">dashboard</a>?</p>
<?php else: ?>
	<h5>This page is for beta testers only.  If you have not completed and been approved then this application will not work.</h5>
	<?= FormRepository::getRegisterForm()->getHtml(); ?>
<?php
endif;

require_once Values::FOOTER_INC;
