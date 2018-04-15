<?php

define("ROOTDIR", "../../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/initializer.php";
use \Catalyst\Character\Character;
use \Catalyst\HTTPCode;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

$id = $character = $pendingCharacter = null;
if (isset($_GET["q"])) {
	$id = Character::getIdFromToken($_GET["q"]);
	if ($id !== -1) {
		$pendingCharacter = new Character($id);
		if ($pendingCharacter->visibleToMe()) {
			$character = $pendingCharacter;
			define("PAGE_IMAGE", $character->getImage()->getFullPath());
		} else {
			HTTPCode::set(403);
		}
	} else {
		HTTPCode::set(404);
	}
} else {
	HTTPCode::set(404);
}

define("PAGE_KEYWORD", Values::VIEW_CHARACTER[0]);
define("PAGE_TITLE", Values::createTitle(Values::VIEW_CHARACTER[1], ["name" => (isset($character) ? $character->getName() : "Invalid Character")]));

if (!is_null($character)) {
	define("PAGE_COLOR", $character->getColor());
} elseif (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Character");

?>
<?php if (is_null($character) && is_null($pendingCharacter)): ?>
			<div class="section">
				<p class="flow-text">This character does not exist.</p>
			</div>
<?php elseif (is_null($character)): ?>
			<div class="section">
				<p class="flow-text">You do not have permission to view this character.</p>
			</div>
<?php else: ?>
			<div class="section">
				<div class="row">
					<div class="col s6 offset-s3 m4 center force-square-contents">
						<?= $character->getImage()->getStrictCircleHtml() ?>
					</div>
					<div class="col s12 m7 offset-m1">
						<div class="col s12 center-on-small-only">
							<h2 class="header hide-on-small-only no-margin"><?= htmlspecialchars($character->getName()) ?></h2>
							
							<br class="hide-on-med-and-up">
							<h3 class="header hide-on-med-and-up no-margin"><?= htmlspecialchars($character->getName()) ?></h3>

							<p class="flow-text no-margin">Owned by <a href="<?= ROOTDIR ?>User/<?= $character->getOwner()->getUsername() ?>"><?= htmlspecialchars($character->getOwner()->getNickname()) ?></a></p>

<?php if (User::isLoggedIn() && $_SESSION["user"]->getId() == $character->getOwnerId()): ?>
							<p class="flow-text"><a href="<?= ROOTDIR ?>Character/Edit/<?= $character->getToken() ?>/">Edit</a></p>
<?php else: ?>
							<br>
<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section">
				<div class="row">
					<div class="col s12 raw-markdown"><?= htmlspecialchars($character->getDescription()) ?></div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section">
				<div class="row">
<?php
if (count($character->getImageSet()) === 0) {
?>
					<p class="flow-text">There are no images of this character yet!</p>
<?php 
} else {
	$cards = [];

	foreach ($character->getImageSet() as $image) {
		$cardContents = $image->getCard("", null, true, null, [], false);
		if (!empty($cardContents)) {
			$cards[] = $cardContents;
		}
	}

	$bisected = [[],[]];
	$trisected = [[],[],[]];

	for ($i=0; $i < count($cards); $i++) { 
		$bisected[$i%2][] = $cards[$i];
		$trisected[$i%3][] = $cards[$i];
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
<?php
}
?>
				</div>
			</div>
<?php endif; ?>
<?php
require_once Values::FOOTER_INC;
