<?php

define("ROOTDIR", "../../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Redacted\Artist\Artist;
use \Redacted\CommissionType\CommissionType;
use \Redacted\Database\CommissionType\EditCommissionType;
use \Redacted\Form\FormHTML;
use \Redacted\Page\UniversalFunctions;
use \Redacted\Page\Values;
use \Redacted\User\User;

define("PAGE_KEYWORD", Values::EDIT_COMMISSION_TYPE);
define("PAGE_TITLE", Values::createTitle(Values::EDIT_COMMISSION_TYPE[1], []));

if (User::isLoggedIn() && $_SESSION["user"]->isArtist()) {
	define("PAGE_COLOR", $_SESSION["user"]->getArtistPage()->getColor());
} else if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Edit Commission Type");

$typeId = CommissionType::getIdFromToken($_GET["q"]);

if (FormHTML::testAjaxSubmissionFailed()): ?>
	<?= FormHTML::getAjaxSubmissionHtml(); ?>
<?php elseif (User::isLoggedOut()): ?>
	<?= User::getNotLoggedInHTML() ?>
<?php elseif (!$_SESSION["user"]->isArtist()): ?>
		<div class="section">
			<p class="flow-text">You do not have an artist page.</p>
			<p class="flow-text">You may create one <a href="<?= ROOTDIR ?>Artist/New">here</a>.</p>
		</div>
<?php elseif ($typeId == -1): ?>
		<div class="section">
			<p class="flow-text">This commission type does not exist.</p>
		</div>
<?php elseif (($type = (new CommissionType($typeId)))->getArtistPageId() != $_SESSION["user"]->getArtistPageId()): ?>
		<div class="section">
			<p class="flow-text">This commission type is not owned by you.</p>
		</div>
<?php else: ?>
	<?= FormHTML::generateForm(EditCommissionType::getFormStructure($type)); ?>
	<input type="hidden" value="<?= $type->getToken() ?>" class="token-input">
<?php
endif;

require_once Values::FOOTER_INC;
