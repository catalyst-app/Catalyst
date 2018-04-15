<?php

define("ROOTDIR", "../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/initializer.php";
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
			HTTPCode::set(302);
			header("Location: ".ROOTDIR."Artist/".$artist->getUrl());
			die("Redirecting...");
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
		define("PAGE_IMAGE", $artist->getImage()->getFullPath());
	} else {
		HTTPCode::set(404);
	}
}

if (User::isLoggedIn() && !is_null($artist) && $_SESSION["user"]->getArtistPageId() == $artist->getId()) {
	define("PAGE_KEYWORD", "artist");
} else {
	define("PAGE_KEYWORD", Values::VIEW_ARTIST[0]);
}
define("PAGE_TITLE", Values::createTitle(Values::VIEW_ARTIST[1], ["name" => (!is_null($artist) ? $artist->getName() : "Invalid URL")]));

if (!is_null($artist)) {
	define("PAGE_COLOR", $artist->getColor());
} elseif (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Artist");
?>

<?php if (!is_null($artist)): ?>
			<div class="section">
				<div class="row">
					<div class="col s6 offset-s3 m4 center force-square-contents">
						<?= $artist->getImage()->getStrictCircleHtml() ?>
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

							<p class="flow-text"><a href="<?= ROOTDIR ?>Artist/ToS/<?= $artist->getUrl() ?>/">Terms of Service</a></p>

							<?= $artist->getSocialChipHtml(false) ?>
						</div>
					</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section">
				<div class="row">
					<div class="col s12 raw-markdown"><?= htmlspecialchars($artist->getDescription()) ?></div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section">
<?php
// $types = CommissionType::getForArtist($artist);
/* $types = array_filter($types, function($type) {
	if (User::isLoggedIn() && $_SESSION["user"]->isNsfw()) {
		return true;
	}
	if (!in_array("MATURE", $type->getAttrs()) && !in_array("EXPLICIT", $type->getAttrs())) {
		return true;
	}
	return in_array("SFW", $type->getAttrs());
}); */ ?>
				<p class="flow-text">Commission types are :b:roke right now, sorry!</p>
			</div>
<?php elseif (User::isLoggedIn() && !array_key_exists("q", $_GET)): ?>
			<div class="section">
				<p class="flow-text">You are not an artist.  <a href="<?= ROOTDIR ?>Artist/New">Become one</a>?</p>
			</div>
<?php else: ?>
			<div class="section">
				<p class="flow-text">This artist does not exist!</p>
			</div>
<?php endif; ?>

<?php
require_once Values::FOOTER_INC;
