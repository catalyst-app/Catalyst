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
			<div class="right-align">
				<a href="<?= ROOTDIR ?>Artist/CommissionTypes/New" class="btn">new</a>
				<div id="reorder-commission-types-btn" class="btn modal-trigger" data-target="commission-type-reorder-modal">reorder</div>
			</div>
			<div id="commission-type-reorder-modal" class="modal rearrange-image-modal">
				<div class="modal-content">
					<h5>Drag the thumbnails to rearrange them</h5>
					<div class="commission-type-rearranger row" id="commission-type-rearranger">
						<?php foreach ($commissionTypes as $commissionType): ?>
							<div class="center force-square-contents col s4 m2" data-token="<?= htmlspecialchars($commissionType->getToken()) ?>">
								<?= $commissionType->getImage()->getStrictCircleHtml(["tooltipped"], ["margin" => "1em"], ["data-tooltip" => $commissionType->getName()]) ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<br>
		</div>
		<div class="section">
			<?php if (count($commissionTypes) == 0): ?>
				<p class="flow-text">You have no commission types!  <a href="<?= ROOTDIR ?>Artist/CommissionTypes/New">Create one</a>?</p>
			<?php endif; ?>
			<div class="user-commission-types">
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
		</div>
<?php
endif;

require_once Values::FOOTER_INC;
