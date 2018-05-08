<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Form\FormRepository;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::EDIT_COMMISSION_TYPE[0]);
define("PAGE_TITLE", Values::createTitle(Values::EDIT_COMMISSION_TYPE[1], []));

$id = $commissionType = $pendingCommissionType = null;
if (User::isLoggedIn() && $_SESSION["user"]->isArtist()) {
	if (array_key_exists("q", $_GET)) {
		$id = CommissionType::getIdFromToken($_GET["q"], false);
		if ($id !== -1) {
			$pendingCommissionType = new CommissionType($id);
			if ($pendingCommissionType->getArtistPageId() == $_SESSION["user"]->getArtistPageId()) {
				$commissionType = $pendingCommissionType;
			} else {
				HTTPCode::set(403);
			}
		} else {
			HTTPCode::set(404);
		}
	} else {
		HTTPCode::set(404);
	}
} else {
	HTTPCode::set(401);
}

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
<?php elseif (is_null($pendingCommissionType)): ?>
			<div class="section">
				<p class="flow-text">This commission type does not exist.</p>
			</div>
<?php elseif (is_null($commissionType)): ?>
			<div class="section">
				<p class="flow-text">You aren't allowed to do that.</p>
			</div>
<?php else: ?>
			<input type="hidden" id="commission-type-token" value="<?= htmlspecialchars($commissionType->getToken()) ?>">
			<?= FormRepository::getEditCommissionTypeForm($commissionType)->getHtml(); ?>
<?php
endif;

require_once Values::FOOTER_INC;
