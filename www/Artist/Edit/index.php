<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\Form\FormRepository;
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::EDIT_ARTIST_PAGE[0]);
define("PAGE_TITLE", Values::createTitle(Values::EDIT_ARTIST_PAGE[1], []));

if (User::isLoggedIn() && $_SESSION["user"]->isArtist()) {
	define("PAGE_COLOR", $_SESSION["user"]->getArtistPage()->getColor());
} else if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Edit Artist Page");

if (!User::isLoggedIn()):
	echo User::getNotLoggedInHtml();
elseif (!$_SESSION["user"]->isArtist()): ?>
		<div class="section">
			<p class="flow-text">You do not have an artist page.</p>
			<p class="flow-text"><a href="<?= ROOTDIR ?>Artist/New">Create one?</a></p>
		</div>
<?php else: ?>
		<div class="section">
			<div class="row">
				<div class="col s12">
					<div class="social-chips social-chips-editable">
						<?= $_SESSION["user"]->getArtistPage()->getSocialChipHtml(true) ?>
					</div>
					<?= SocialMedia::getAddChipHtml() ?>
					<?= SocialMedia::getAddModal("Artist") ?>
				</div>
			</div>
		</div>
		<div class="divider"></div>
		<?= FormRepository::getEditArtistPageForm()->getHtml() ?>
		<div class="divider"></div>
		<div class="section">
			<p class="col s12 flow-text">Please go <a href="../CommissionTypes/">here</a> to edit commission types.</p>
			<?= FormRepository::getDeleteArtistPageForm()->getHtml(false) ?>
		</div>
<?php
endif;

require_once Values::FOOTER_INC;
