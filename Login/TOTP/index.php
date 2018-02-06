<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Database\User\Login;
use \Catalyst\Database\User\TOTPLogin;
use \Catalyst\Form\FormHTML;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
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

if (FormHTML::testAjaxSubmissionFailed()) {
	echo FormHTML::getAjaxSubmissionHtml();
} elseif (call_user_func(...TOTPLogin::getFormStructure()[0]["auth"][0])) {
	echo call_user_func(TOTPLogin::getFormStructure()[0]["auth"][1]);
} elseif (!Login::pending2FA()) {
?>
		<div class="section">
			<p class="flow-text">You must first enter your password <a href="<?= ROOTDIR ?>Login">here</a>.</p>
		</div>
<?php
} else {
	echo FormHTML::generateForm(TOTPLogin::getFormStructure());
}

require_once Values::FOOTER_INC;
