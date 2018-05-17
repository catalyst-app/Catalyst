<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Form\FormRepository;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::NEW_ARTIST_PAGE[0]);
define("PAGE_TITLE", Values::createTitle(Values::NEW_ARTIST_PAGE[1], []));

if (User::isLoggedIn() && $_SESSION["user"]->isArtist()) {
	define("PAGE_COLOR", $_SESSION["user"]->getArtistPage()->getColor());
} elseif (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("New Artist Page");

if (!User::isLoggedIn()):
	echo User::getNotLoggedInHtml();
elseif ($_SESSION["user"]->isArtist()): ?>
		<div class="section">
			<p class="flow-text">You are already an artist.</p>
			<p class="flow-text">You may view your page <a href="<?= ROOTDIR ?>Artist/<?= $_SESSION["user"]->getArtistPage()->getUrl() ?>">here</a>.</p>
		</div>
<?php elseif ($_SESSION["user"]->wasArtist()): ?>
		<div class="section">
			<p class="flow-text">You deleted your artist page.  In order to re-activate it, click below.  Please note, none of your original information will be present, <strong>except</strong> pre-existing commissions.</p>
			<?= FormRepository::getUndeleteArtistPageForm()->getHtml(false) ?>
		</div>
<?php else: ?>
		<div class="section">
			<?= FormRepository::getCreateArtistPageForm()->getHtml(false) ?>
			<p class="col s12">You will be able to list commission types after you create your page.</p>
		</div>
<?php
endif;

require_once Values::FOOTER_INC;
