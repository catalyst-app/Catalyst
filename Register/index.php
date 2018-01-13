<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Database\User\Register;
use \Catalyst\Form\FormHTML;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::REGISTER[0]);
define("PAGE_TITLE", Values::createTitle(Values::REGISTER[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Register");

if (FormHTML::testAjaxSubmissionFailed()) {
	echo FormHTML::getAjaxSubmissionHtml();
} elseif (call_user_func(...Register::getFormStructure()[0]["auth"][0])) {
	echo call_user_func(Register::getFormStructure()[0]["auth"][1]);
} else {
	echo FormHTML::generateForm(Register::getFormStructure());
}

require_once Values::FOOTER_INC;
