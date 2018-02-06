<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::LOGIN[0]);
define("PAGE_TITLE", Values::createTitle(Values::LOGIN[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColorHex());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

if (User::isLoggedIn()) {
	HTTPCode::set(401);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Login");

if (User::isLoggedIn()) {
?>
			<p class="flow-text">You are already logged in.</p>
			<p class="flow-text">Go to your <a href="<?= ROOTDIR ?>Dashboard">dashboard</a>?</p>
<?php
} else {
	echo FormRepository::getLoginForm()->getHtml();
}

require_once Values::FOOTER_INC;
