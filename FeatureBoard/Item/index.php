<?php

define("ROOTDIR", "../../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Database\FeatureBoard\Comment;
use \Catalyst\Database\FeatureBoard\Groups;
use \Catalyst\Database\FeatureBoard\Item;
use \Catalyst\Form\FormHTML;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

if (!isset($_GET["q"])) {
	header("Location: ".ROOTDIR."FeatureBoard");
	die("Redirecting...");
}

$item = Item::getFromUrl($_GET["q"]);

define("PAGE_KEYWORD", Values::FEATURE[0]);
define("PAGE_TITLE", Values::createTitle(Values::FEATURE[1], ["name" => (!is_null($item) ? $item["NAME"] : "Invalid")]));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Feature");
?>

<?php if (!is_null($item)): ?>
			<div class="section">
			</div>
<?php else: ?>
			<div class="section">
				<p class="flow-text">Feature not found.  <a href="<?= ROOTDIR ?>FeatureBoard">Go back</a>?</p>
			</div>
<?php endif; ?>

<?php
require_once Values::FOOTER_INC;
