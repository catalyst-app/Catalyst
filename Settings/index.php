<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Form\FormRepository;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\HTTPCode;
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::SETTINGS[0]);
define("PAGE_TITLE", Values::createTitle(Values::SETTINGS[1], ["name" => (isset($_SESSION["user"]) ? $_SESSION["user"]->getNickname() : "Logged Out")]));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColorHex());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

if (!User::isLoggedIn()) {
	HTTPCode::set(401);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Settings");

if (!User::isLoggedIn()):
	echo User::getNotLoggedInHTML();
else: 
	echo FormRepository::getSettingsForm($_SESSION["user"])->getHtml();
?>
	<div class="divider"></div>
	<div class="section">
		<div class="row"><div data-target="deactivate" class="col s12 m4 l2 btn red darken-1 modal-trigger">DEACTIVATE</div></div>

		<div id="deactivate" class="modal">
			<div class="modal-content">
				<h4>Deactivate your account</h4>
				<h5><strong>This action is IRREVERSIBLE</strong></h5>
				<p class="flow-text">
					In order to deactivate your account, please enter your username and password below (for confirmation purposes).
				</p>
				<?= FormRepository::getDeactivateForm()->getHtml() ?>
			</div>
		</div>
	</div>
<?php
endif;

require_once Values::FOOTER_INC;
