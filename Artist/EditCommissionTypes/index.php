<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Form\FormHTML;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::EDIT_ARTIST_PAGE_COMMISSION_TYPES[0]);
define("PAGE_TITLE", Values::createTitle(Values::EDIT_ARTIST_PAGE_COMMISSION_TYPES[1], []));

if (User::isLoggedIn() && $_SESSION["user"]->isArtist()) {
	define("PAGE_COLOR", $_SESSION["user"]->getArtistPage()->getColor());
} else if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Edit Commission Types");

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
				<p class="col s12">You may drag to reorder these.</p>
<?php
$types = CommissionType::getForArtist($_SESSION["user"]->getArtistPage());
?>
				<a href="<?= ROOTDIR ?>Artist/NewCommissionType/">
					<div class="commission-type new-commission-type row">
						<div class="divider col s12 bottom-margin"></div>
						<div class="col s6 offset-s3 m3 l2 center force-square-contents">
							<?= UniversalFunctions::getStrictCircleImageHTML(ROOTDIR."img/new.png", false) ?>
						</div>
						<div class="col s12 m7 l9 row-inside">
							<div class="col s12 no-padding">
								<h3 class="no-top-margin">
									New
								</h3>
							</div>
							<p>Create a new commission type</p>
							<p class="flow-text"><a href="<?= ROOTDIR ?>Artist/NewCommissionType/">Go</a></p>
						</div>
						<div class="divider col s12 top-margin"></div>
					</div>
				</a>
				<div class="commission-types commission-types-rearrangeable">
<?php foreach ($types as $type): ?>
					<div data-id="<?= $type->getId() ?>" class="commission-type row">
						<div class="col s6 offset-s3 m3 l2 center force-square-contents">
							<?= UniversalFunctions::getStrictCircleImageHTML(ROOTDIR."commission_type_images/".$type->getPrimaryImage()[0], $type->getPrimaryImage()[2]) ?>
						</div>
						<div class="col s12 m7 l9 row-inside">
							<div class="col s12 no-padding">
								<div class="right right-align">
									<h5 class="no-top-margin">
										<?= htmlspecialchars($type->getBaseCost()) ?>
									</h5>
									<h5 class="no-margin <?= $type->isOpen() ? "green-text" : "red-text" ?>">
										<?= $type->isOpen() ? "OPEN" : "CLOSED" ?>
									</h5>
								</div>
								<h3 class="no-top-margin">
									<?= htmlspecialchars($type->getName()) ?>
								</h3>
							</div>
							<p><?= htmlspecialchars($type->getBlurb()) ?></p>
<?php
$attrgs = $type->getHumanAttrs();
foreach ($attrgs as $key => $attrs): ?>
							<p class="grey-text no-margin"><strong><?= htmlspecialchars($key) ?>:</strong> <?= htmlspecialchars(implode(", ", $attrs)) ?></p>
<?php endforeach; ?>
							<p class="flow-text"><a href="<?= ROOTDIR ?>Artist/EditCommissionType/<?= $type->getToken() ?>">Edit</a> | <a href="#" class="red-text delete-commission-type-button">Delete</a> | <span class="commission-type-handle">Drag</span></p>
						</div>
						<div class="divider col s12 top-margin"></div>
					</div>
<?php endforeach; ?>
				</div>
				<div id="save-commission-type-order" class="btn col s12 m4 l2">save</div>
			</div>
		</div>
<?php
}

require_once Values::FOOTER_INC;
