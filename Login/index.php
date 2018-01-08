<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Redacted\Database\User\Login;
use \Redacted\Form\FormHTML;
use \Redacted\Page\UniversalFunctions;
use \Redacted\Page\Values;
use \Redacted\User\User;

define("PAGE_KEYWORD", Values::LOGIN[0]);
define("PAGE_TITLE", Values::createTitle(Values::LOGIN[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Login");

if (FormHTML::testAjaxSubmissionFailed()) {
	echo FormHTML::getAjaxSubmissionHtml();
} elseif (call_user_func(...Login::getFormStructure()[0]["auth"][0])) {
	echo call_user_func(Login::getFormStructure()[0]["auth"][1]);
} else {
	echo FormHTML::generateForm(Login::getFormStructure());
}

require_once Values::FOOTER_INC;
