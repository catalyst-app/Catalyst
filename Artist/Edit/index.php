<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Database\Artist\EditArtist;
use \Catalyst\Form\FormHTML;
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::EDIT_ARTIST_PAGE[0]);
define("PAGE_TITLE", Values::createTitle(Values::EDIT_ARTIST_PAGE[1], []));

if (User::isLoggedIn() && $_SESSION["user"]->isArtist()) {
	define("PAGE_COLOR", $_SESSION["user"]->getArtistPage()->getColorHex());
} else if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColorHex());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Edit Artist Page");

if (FormHTML::testAjaxSubmissionFailed()) {
	echo FormHTML::getAjaxSubmissionHtml();
} elseif (User::isLoggedOut()) {
	echo User::getNotLoggedInHTML();
} elseif (!$_SESSION["user"]->isArtist()) { ?>
		<div class="section">
			<p class="flow-text">You do not have an artist page.</p>
			<p class="flow-text">You may create one <a href="<?= ROOTDIR ?>Artist/New">here</a>.</p>
		</div>
<?php } else { ?>

		<div class="section">
			<div class="row">
				<div class="col s12">
					<div class="social-chips">
						<?= SocialMedia::getArtistChipHTML($_SESSION["user"]->getArtistPage()) ?>
					</div>
					<?= SocialMedia::getAddChip() ?>
					<?= SocialMedia::getAddModal() ?>
				</div>
			</div>
		</div>
		<div class="divider"></div>
<?php echo FormHTML::generateForm(EditArtist::getFormStructure()); ?>

		<div class="divider"></div>
		<div class="section">
			<p class="col s12 flow-text">Please go <a href="../EditCommissionTypes/">here</a> to edit commission types.</p>
		</div>
		<div class="divider"></div>
		<div class="section">
			<div class="row"><div data-target="deactivate" class="btn col s12 m4 l2 red darken-1 modal-trigger">DELETE</div></div>

			<div id="deactivate" class="modal">
				<div class="modal-content">
					<h4>Delete artist page</h4>
					<h5><strong>This action is IRREVERSIBLE.</strong></h5>
					<p class="flow-text">
						You will lose all of the information on your page and it will become inaccessible immediately.
					</p>
					<p class="flow-text">
						In order to delete the page, click the button below.
					</p>
					<div class="row"><div class="confirm-artist-page-deletion-btn btn red darken-1">confirm</div></div>
				</div>
			</div>
		</div>
<?php
}

require_once Values::FOOTER_INC;
