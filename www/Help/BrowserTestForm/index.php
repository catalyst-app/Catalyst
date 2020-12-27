<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", "about");
define("PAGE_TITLE", "Browser Form Test");

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Browser Test Form");

?>
	<input type="hidden" id="hidden-field-test" value="foobarbazzle">
<?php

echo FormRepository::getTestForm()->getHtml();

require_once Values::FOOTER_INC;
