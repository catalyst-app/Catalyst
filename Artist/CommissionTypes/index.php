<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Images\Image;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::EDIT_ARTIST_PAGE_COMMISSION_TYPES[0]);
define("PAGE_TITLE", Values::createTitle(Values::EDIT_ARTIST_PAGE_COMMISSION_TYPES[1], []));

if (User::isLoggedIn() && $_SESSION["user"]->isArtist()) {
	define("PAGE_COLOR", $_SESSION["user"]->getArtistPage()->getColor());
} else if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Edit Commission Types");

if (!User::isLoggedIn()):
	echo User::getNotLoggedInHtml();
elseif (!$_SESSION["user"]->isArtist()): ?>
		<div class="section">
			<p class="flow-text">You do not have an artist page.</p>
			<p class="flow-text"><a href="<?= ROOTDIR ?>Artist/New">Create one?</a></p>
		</div>
<?php else: ?>
<?php
$commissionTypes = CommissionType::getForArtist($_SESSION["user"]->getArtistPage());
?>
		<div class="section no-top-margin">
			<div id="reorder-commission-types-btn" class="right btn">reorder</div>
			<br>
		</div>
		<div class="section">
			<div class="row">
				<div class="col s6 offset-s3 m3 l2 center force-square-contents">
					<?= Image::getNewItemImage()->getStrictCircleHtml() ?>
				</div>
				<div class="col s12 m9 l10">
					<h3 class="no-top-margin">
						New
					</h3>
					<p>Create a new commission type</p>
					<p class="flow-text"><a href="<?= ROOTDIR ?>Artist/CommissionTypes/New/">Go</a></p>
				</div>
			</div>
			<?php foreach ($commissionTypes as $commissionType): ?>
				<div class="row commission-type-row" data-token="<?= htmlspecialchars($commissionType->getToken()) ?>">
					<div class="col s6 offset-s3 m3 l2 center force-square-contents">
						<?= $commissionType->getImage()->getStrictCircleHtml() ?>
					</div>
					<div class="col s12 m9 l10">
						<h3 class="no-margin col s12">
							<?= htmlspecialchars($commissionType->getName()) ?>
						</h3>
						<p class="flow-text no-margin col s12">
							<span class="tooltipped" data-tooltip="<?= htmlspecialchars($commissionType->getBaseUsdCost()) ?> USD">
								<?= htmlspecialchars($commissionType->getBaseCost()) ?>
							</span>
						</p>
						<p class="col s12">
							<?= htmlspecialchars($commissionType->getBlurb()) ?>
						</p>
						<div class="no-bottom-margin col s12">
							<span>Quick toggles:</span>
							<?php foreach (CommissionType::QUICK_TOGGLE_BUTTONS as $button): ?>
								<div class="btn toggle-btn-set-button toggle-btn commission-type-quick-toggle-button <?= htmlspecialchars(call_user_func([$commissionType, $button[1]]) ? "on" : "off") ?>" data-action="<?= htmlspecialchars($button[0]) ?>">
									<?= htmlspecialchars($button[0]) ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
<?php
endif;

require_once Values::FOOTER_INC;
