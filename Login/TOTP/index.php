<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::TOTP_LOGIN[0]);
define("PAGE_TITLE", Values::createTitle(Values::TOTP_LOGIN[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColorHex());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("2FA Login");

if (!User::isPending2FA()) {
?>
		<div class="section">
			<p class="flow-text">You must first login <a href="<?= ROOTDIR ?>Login">here</a>.</p>
		</div>
<?php
} else {
	echo FormRepository::getTotpLoginForm()->getHtml();
}

require_once Values::FOOTER_INC;
