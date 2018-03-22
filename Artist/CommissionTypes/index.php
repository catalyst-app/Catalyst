<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
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
		<div class="section">
			<div class="row">
				<p class="col s12">You may drag to reorder these.</p>
				<a href="<?= ROOTDIR ?>Artist/CommissionType/New/">
					<div class="commission-type new-commission-type row">
						<div class="divider col s12 bottom-margin"></div>
						<div class="col s6 offset-s3 m3 l2 center force-square-contents">
							<?= Image::getNewItemImage()->getStrictCircleHtml() ?>
						</div>
						<div class="col s12 m7 l9 row-inside">
							<div class="col s12 no-padding">
								<h3 class="no-top-margin">
									New
								</h3>
							</div>
							<p>Create a new commission type</p>
							<p class="flow-text"><a href="<?= ROOTDIR ?>Artist/CommissionTypes/New/">Go</a></p>
						</div>
						<div class="divider col s12 top-margin"></div>
					</div>
				</a>
				<div class="commission-types commission-types-rearrangeable">
					<!-- oof -->
				</div>
				<div id="save-commission-type-order" class="btn col s12 m4 l2">save</div>
			</div>
		</div>
<?php
endif;

require_once Values::FOOTER_INC;
