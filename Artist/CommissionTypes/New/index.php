<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Form\FormRepository;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::NEW_COMMISSION_TYPE[0]);
define("PAGE_TITLE", Values::createTitle(Values::NEW_COMMISSION_TYPE[1], []));

if (User::isLoggedIn() && $_SESSION["user"]->isArtist()) {
	define("PAGE_COLOR", $_SESSION["user"]->getArtistPage()->getColor());
} else if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("New Commission Type");

if (!User::isLoggedIn()):
	echo User::getNotLoggedInHtml();
elseif (!$_SESSION["user"]->isArtist()): ?>
		<div class="section">
			<p class="flow-text">You do not have an artist page.</p>
			<p class="flow-text"><a href="<?= ROOTDIR ?>Artist/New">Create one?</a></p>
		</div>
<?php
else:
	echo FormRepository::getNewCommissionTypeForm()->getHtml();
endif;

require_once Values::FOOTER_INC;
