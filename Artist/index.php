<?php

define("ROOTDIR", "../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Artist\Artist;
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\HTTPCode;
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

$artist = null;
if (!array_key_exists("q", $_GET)) {
	if (User::isLoggedIn()) {
		if ($_SESSION["user"]->isArtist()) {
			$artist = $_SESSION["user"]->getArtistPage();
		} else {
			HTTPCode::set(400);
		}
	} else {
		HTTPCode::set(401);
	}
} else {
	$artistId = Artist::getIdFromUrl($_GET["q"]);
	if ($artistId !== -1) {
		$artist = new Artist($artistId);
	} else {
		HTTPCode::set(404);
	}
}

if (User::isLoggedIn() && isset($artist) && $_SESSION["user"]->getArtistPageId() == $artist->getId()) {
	define("PAGE_KEYWORD", "artist");
} else {
	define("PAGE_KEYWORD", Values::VIEW_ARTIST[0]);
}
define("PAGE_TITLE", Values::createTitle(Values::VIEW_ARTIST[1], ["name" => (isset($artist) ? $artist->getName() : "Invalid URL")]));

if (isset($artist)) {
	define("PAGE_COLOR", $artist->getColor());
} elseif (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Artist");
?>

<?php if (isset($artist)): ?>
			<div class="section">
				<div class="row">
					<div class="col s6 offset-s3 m4 center force-square-contents">
						<?= UniversalFunctions::getStrictCircleImageHTML(ROOTDIR.\Catalyst\Form\FileUpload::FOLDERS[\Catalyst\Form\FileUpload::ARTIST_IMAGE]."/".$artist->getImg(), false) ?>
					</div>
					<div class="col s12 m7 offset-m1">
						<div class="col s12 center-on-small-only">
							<h2 class="header hide-on-small-only no-margin"><?= htmlspecialchars($artist->getName()) ?></h2>
							
							<br class="hide-on-med-and-up">
							<h3 class="header hide-on-med-and-up no-margin"><?= htmlspecialchars($artist->getName()) ?></h3>

<?php if (User::isLoggedIn() && $_SESSION["user"]->getId() == $artist->getUserId()): ?>
							<p class="flow-text"><a href="<?= ROOTDIR ?>Artist/Edit/">Edit</a></p>
<?php else: ?>
							<br>
<?php endif; ?>
							<p class="flow-text"><a href="<?= ROOTDIR ?>User/<?= (new User($artist->getUserId()))->getUsername() ?>/">User profile</a></p>

							<?= SocialMedia::getArtistChipHTML($artist) ?>
						</div>
					</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section">
				<div class="row">
					<div class="col s12 raw-markdown">
<?= htmlspecialchars($artist->getDescription()) ?>
					</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section">
<?php
$types = CommissionType::getForArtist($artist);
$types = array_filter($types, function($type) {
	if (User::isLoggedIn() && $_SESSION["user"]->isNsfw()) {
		return true;
	}
	if (!in_array("MATURE", $type->getAttrs()) && !in_array("EXPLICIT", $type->getAttrs())) {
		return true;
	}
	return in_array("SFW", $type->getAttrs());
});
if (count($types)): ?>
<?php foreach ($types as $type): ?>
				<div class="commission-type-collapsible commission-type">
					<div class="commission-type-collapsible-header">
						<div class="row">
							<div class="col s6 offset-s3 m3 l2 center force-square-contents">
								<?= UniversalFunctions::getStrictCircleImageHTML(ROOTDIR."commission_type_images/".$type->getPrimaryImage()[0], $type->getPrimaryImage()[2]) ?>
							</div>
							<div class="col s12 m9 l10 row-inside">
								<div class="right right-align">
<?php if (User::isLoggedIn()): ?>
<?php if ($_SESSION["user"]->isOnWishlist($type)): ?>
									<div class="hide-on-large-only small-padding-bottom"><div data-id="<?= $type->getId() ?>" class="btn wishlist-remove-btn">remove from wishlist</div></div>
<?php else: ?>
									<div class="hide-on-large-only small-padding-bottom"><div data-id="<?= $type->getId() ?>" class="btn wishlist-add-btn">add to wishlist</div></div>
<?php endif; ?>
<?php endif; ?>
									<h5 class="no-top-margin">
										<?= htmlspecialchars($type->getBaseCost()) ?>
									</h5>
<?php if ($type->isOpen()): ?>
									<a href="<?= ROOTDIR ?>Commission/New/<?= $type->getToken() ?>" class="btn">commission</a>
<?php else: ?>
									<a class="btn disabled">closed</a>
<?php endif; ?>
<?php if (User::isLoggedIn()): ?>
<?php if ($_SESSION["user"]->isOnWishlist($type)): ?>
									<div class="hide-on-med-and-down small-padding-top"><div data-id="<?= $type->getId() ?>" class="btn wishlist-remove-btn">remove from wishlist</div></div>
<?php else: ?>
									<div class="hide-on-med-and-down small-padding-top"><div data-id="<?= $type->getId() ?>" class="btn wishlist-add-btn">add to wishlist</div></div>
<?php endif; ?>
<?php endif; ?>
									<div><i class="material-icons large">arrow_drop_down</i></div>
								</div>
								<h3 class="no-top-margin">
									<?= htmlspecialchars($type->getName()) ?>
								</h3>
								<p><?= htmlspecialchars($type->getBlurb()) ?></p>
<?php
$attrgs = $type->getHumanAttrs();
foreach ($attrgs as $key => $attrs): ?>
								<p class="grey-text no-margin"><strong><?= htmlspecialchars($key) ?>:</strong> <?= htmlspecialchars(implode(", ", $attrs)) ?></p>
<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="commission-type-collapsible-body collapsible-hidden" style="display: none;">
						<div class="row">
							<div class="col s12 raw-markdown row">
<?= htmlspecialchars($type->getDescription()) ?>
							</div>
							<br><br>
							<div class="col s12">
								<p><strong>Options:</strong></p>
<?php foreach ($type->getModifiers() as $a): ?>
								<p class="no-margin"><?= htmlspecialchars(implode(", ", array_map(function($in) { return $in[0]." (+".$in[1].")"; }, $a["items"]))) ?></p>
<?php endforeach; ?>
<?php if (!count($type->getModifiers())): ?>
								<p>None</p>
<?php endif; ?>
							</div>
							<div class="col s12">
								<p><strong>You may pay with:</strong> <?= count($type->getPaymentOptions()) ? htmlspecialchars(implode(", ", array_column($type->getPaymentOptions(), 0))) : "None specified" ?></p>
							</div>
							<div class="row">
<?php if ($type->getImages()[0][0] == "default.png"): ?>
								<p class="col s12 flow-text">There are no images of this commission type yet!</p>
<?php else: ?>
<?php
$images = $type->getImages();
if (!\Catalyst\User\User::isLoggedIn() || !$_SESSION["user"]->isNsfw()) {
	$images = array_values(array_filter($images, function($in) { return !$in[2]; }));
}

$images = array_map(function($in) {
	return UniversalFunctions::renderImageCard(ROOTDIR.\Catalyst\Form\FileUpload::FOLDERS[\Catalyst\Form\FileUpload::COMMISSION_TYPE_IMAGE]."/".$in[0], false, "", $in[1], "");
}, $images);

$bisected = [[],[]];
$trisected = [[],[],[]];
for ($i=0; $i < count($images); $i++) { 
	$bisected[$i%2][] = $images[$i];
	$trisected[$i%3][] = $images[$i];
}

?>
								<div class="col s12 m6 hide-on-large-only">
									<?= implode("", $bisected[0]) ?>
								</div>
								<div class="col s12 m6 hide-on-large-only">
									<?= implode("", $bisected[1]) ?>
								</div>
								<div class="col l4 hide-on-med-and-down">
									<?= implode("", $trisected[0]) ?>
								</div>
								<div class="col l4 hide-on-med-and-down">
									<?= implode("", $trisected[1]) ?>
								</div>
								<div class="col l4 hide-on-med-and-down">
									<?= implode("", $trisected[2]) ?>
								</div>
<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="divider col s12 top-margin"></div>
<?php endforeach; ?>
<?php else: ?>
				<h4>This artist has no commission types listed!</h4>
<?php endif; ?>
			</div>
<?php else: ?>
			<div class="section">
				<p class="flow-text">This artist does not exist!</p>
			</div>
<?php endif; ?>

<?php
require_once Values::FOOTER_INC;
