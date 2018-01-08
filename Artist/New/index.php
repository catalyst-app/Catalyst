<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Redacted\Database\Artist\NewArtist;
use \Redacted\Form\FormHTML;
use \Redacted\Page\UniversalFunctions;
use \Redacted\Page\Values;
use \Redacted\User\User;

define("PAGE_KEYWORD", Values::NEW_ARTIST_PAGE[0]);
define("PAGE_TITLE", Values::createTitle(Values::NEW_ARTIST_PAGE[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("New Artist");

if (FormHTML::testAjaxSubmissionFailed()) {
	echo FormHTML::getAjaxSubmissionHtml();
} elseif (User::isLoggedOut()) {
	echo User::getNotLoggedInHTML();
} elseif ($_SESSION["user"]->isArtist()) { ?>
		<div class="section">
			<p class="flow-text">You are already an artist.</p>
			<p class="flow-text">You may view your page <a href="<?= ROOTDIR ?>Artist/<?= $_SESSION["user"]->getArtistPage()->getUrl() ?>">here</a>.</p>
		</div>
<?php
} else {
	echo FormHTML::generateForm(NewArtist::getFormStructure());
	echo '<p class="col s12">You will be able to list commission types after you create your page.</p>';
}

require_once Values::FOOTER_INC;
