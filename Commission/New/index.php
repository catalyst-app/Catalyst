<?php

define("ROOTDIR", "../../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

if (isset($_GET["q"])) {
	$id = CommissionType::getIdFromToken($_GET["q"]);
	if ($id != -1) {
		$type = new CommissionType($id);
		$artist = $type->getArtistPage();
	}
}

define("PAGE_KEYWORD", Values::NEW_COMMISSION[0]);
define("PAGE_TITLE", Values::createTitle(Values::NEW_COMMISSION[1], ["type" => (isset($type) ? $type->getName() : "Invalid"), "artist" => (isset($type) ? $artist->getName() : "Invalid")]));

if (isset($type)) {
	define("PAGE_COLOR", $artist->getColorHex());
} elseif (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColorHex());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("New Commission");

?>
<?php if (User::isLoggedOut()): ?>
	<?= User::getNotLoggedInHTML() ?>
<?php elseif (!isset($type)): ?>
	<div class="section">
		<p class="flow-text">This commission type does not exist</p>
	</div>
<?php elseif (!$type->isOpen()): ?>
	<div class="section">
		<p class="flow-text">This commission type is not currently open!</p>
<?php if (!$_SESSION["user"]->isOnWishlist($type)): ?>
		<p class="flow-text"><a href="#" data-id="<?= $type->getId() ?>" class="wishlist-add-btn">Add to wishlist?</a></p>
<?php endif; ?>
		<p class="flow-text">Return to <a href="<?= ROOTDIR ?>Artist/<?= $artist->getUrl() ?>"><?= $artist->getName() ?>'s page</a></p>
	</div>
<?php else: ?>
	ayyy
<?php endif; ?>
<?php
require_once Values::FOOTER_INC;
