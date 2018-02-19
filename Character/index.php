<?php

define("ROOTDIR", "../".((isset($_GET["level1"]) && $_GET["level1"] == "/") ? "../" : "").((isset($_GET["level2"]) && $_GET["level2"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Character\Character;
use \Catalyst\Database\Character\EditCharacter;
use \Catalyst\Form\FormHTML;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

if (!isset($_GET["q1"])) {
	$page = "LANDING";
} elseif (isset($_GET["q2"]) && $_GET["q2"] == "Edit" || $_GET["q2"] == "Edit/") {
	$page = "EDIT";
} elseif (isset($_GET["q2"]) && $_GET["q2"] == "EditImages" || $_GET["q2"] == "EditImages/") {
	$page = "EDITIMG";
} else {
	$page = "VIEW";
}

if ($page == "EDIT" || $page == "EDITIMG" || $page == "VIEW") {
	$characterId = Character::getIdFromToken($_GET["q1"]);
	if ($characterId !== -1) {
		$characterObj = new Character($characterId);
		if ($characterObj->visibleToMe()) {
			$character = $characterObj;
		}
	}
}

switch ($page) {
	case "LANDING":
		define("PAGE_KEYWORD", Values::CHARACTERS[0]);
		define("PAGE_TITLE", Values::createTitle(Values::CHARACTERS[1], []));
		break;
	case "EDIT":
	case "EDITIMG":
		define("PAGE_KEYWORD", Values::EDIT_CHARACTER[0]);
		define("PAGE_TITLE", Values::createTitle(Values::EDIT_CHARACTER[1], ["name" => (isset($character) ? $character->getName() : "Invalid Name")]));
		break;
	case "VIEW":
		define("PAGE_KEYWORD", Values::VIEW_CHARACTER[0]);
		define("PAGE_TITLE", Values::createTitle(Values::VIEW_CHARACTER[1], ["name" => (isset($character) ? $character->getName() : "Invalid Name")]));
		break;
}


if (isset($character)) {
	define("PAGE_COLOR", $character->getColor());
} elseif (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

switch ($page) {
	case "LANDING":
		echo UniversalFunctions::createHeading("My Characters");
		break;
	case "EDIT":
	case "EDITIMG":
		echo UniversalFunctions::createHeading("Edit Character");
		break;
	case "VIEW":
		echo UniversalFunctions::createHeading("Character");
		break;
}
?>

<?php if ($page == "LANDING"): ?>
<?php if (!User::isLoggedIn()): ?>
			<?= User::getNotLoggedInHtml() ?>
<?php else: ?>
			<div class="section">
				<div class="row">
<?php
$chars = Character::getCharactersFromUser($_SESSION["user"]);
$cards = array_map(function($in) {
	$img = $in->getPrimaryImage();
	return UniversalFunctions::renderImageCard(ROOTDIR.\Catalyst\Form\FileUpload::FOLDERS[\Catalyst\Form\FileUpload::CHARACTER_IMAGE]."/".$img[0], $img[2], $in->getName(), "", ROOTDIR."Character/".$in->getToken()."/");
}, $chars);

array_unshift($cards, UniversalFunctions::renderImageCard(ROOTDIR."img/new.png", false, "New Character", "", ROOTDIR."Character/New"));

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
				</div>
			</div>
<?php endif; ?>				
<?php elseif ($page == "EDIT"): ?>
<?php if (!isset($character)): ?>
			<div class="section">
				<p class="flow-text">This character does not exist or has been made private by the owner.</p>
			</div>
<?php elseif (!User::isLoggedIn() || $character->getOwnerId() != $_SESSION["user"]->getId()): ?>
			<div class="section">
				<p class="flow-text">You do not have permission to do that.</p>
			</div>
<?php else: ?>
			<input type="hidden" class="token-input" value="<?= $character->getToken() ?>">
<?= FormHTML::generateForm(EditCharacter::getFormStructure($character)) ?>
			<div class="divider"></div>
			<div class="section">
				<div class="row"><div data-target="deactivate" class="btn col s12 m4 l2 red darken-1 modal-trigger">DELETE</div></div>

				<div id="deactivate" class="modal">
					<div class="modal-content">
						<h4>Delete character</h4>
						<h5><strong>This action is IRREVERSIBLE</strong></h5>
						<p class="flow-text">
							In order to delete the character, click the button below.
						</p>
						<div class="row"><div class="confirm-character-deletion-btn btn red darken-1">confirm</div></div>
					</div>
				</div>
			</div>
<?php endif; ?>
<?php elseif ($page == "EDITIMG"): ?>
<?php if (!isset($character)): ?>
			<div class="section">
				<p class="flow-text">This character does not exist or has been made private by the owner.</p>
			</div>
<?php elseif (!User::isLoggedIn() || $character->getOwnerId() != $_SESSION["user"]->getId()): ?>
			<div class="section">
				<p class="flow-text">You do not have permission to do that.</p>
			</div>
<?php else: ?>
			<div class="section">
				<div class="row">
					<div class="col s6 offset-s3 m4 center force-square-contents">
						<?= UniversalFunctions::getStrictCircleImageHTML(ROOTDIR.\Catalyst\Form\FileUpload::FOLDERS[\Catalyst\Form\FileUpload::CHARACTER_IMAGE]."/".$character->getPrimaryImage()[0], $character->getPrimaryImage()[2]) ?>
					</div>
					<div class="col s12 m7 offset-m1">
						<div class="col s12 center-on-small-only">
							<h2 class="header hide-on-small-only no-margin"><?= htmlspecialchars($character->getName()) ?></h2>
							
							<br class="hide-on-med-and-up">
							<h3 class="header hide-on-med-and-up no-margin"><?= htmlspecialchars($character->getName()) ?></h3>

							<p class="flow-text no-margin">Owned by <a href="<?= ROOTDIR ?>User/<?= $character->getOwner()->getUsername() ?>"><?= htmlspecialchars($character->getOwner()->getNickname()) ?></a></p>
						</div>
					</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section">
				<div class="row character-images edit-cards">
<?php if ($character->getImages()[0][0] == "default.png"): ?>
					<p class="col s12 flow-text">There are no images of this character yet!</p>
<?php else: ?>
					<input type="hidden" class="token-input" value="<?= $character->getToken() ?>">
					<p class="col s12 flow-text">Drag the images to rearrange</p>
<?php
$images = $character->getImages();

$i=0;

$images = array_map(function($in) use ($character, $i) {
	$i++;
	return UniversalFunctions::renderImageCardRawHTML(ROOTDIR.\Catalyst\Form\FileUpload::FOLDERS[\Catalyst\Form\FileUpload::CHARACTER_IMAGE]."/".$in[0], $in[2], 
		implode("", [
			'<div class="row no-margin">',
			'<input type="hidden" class="path-input" id="path'.$i.'" value="'.str_replace($character->getToken(), "", $in[0]).'">',
			'<div class="input-field col s12">',
			'<input type="text" id="caption'.$i.'" maxlength="255" class="caption-input" value="'.htmlspecialchars($in[1]).'">',
			'<label for="caption'.$i.'">Caption</label>',
			'</div>',
			'<div class="switch col s12 small-bottom-margin">',
			'<label>',
			'SFW',
			'<input type="checkbox" class="nsfw-input"'.($in[2] ? ' checked="checked"' : '').'>',
			'<span class="lever"></span>',
			'NSFW',
			'</label>',
			'</div>',
			'<input type="hidden" class="primary-input" id="primary'.$i.'" value="'.($in[3] ? "true" : "false").'">',
			'<div class="col s12 m6 small-padding">',
			'<div class="col s12 btn make-primary-button"'.($in[3] ? ' disabled="disabled"' : '').'>primary</div>',
			'</div>',
			'<div class="col s12 m6 small-padding">',
			'<div class="col s12 btn delete-button red darken-1">delete</div>',
			'</div>',
			'</div>',
		])
	);
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
				</div>
				<br>
				<div class="divider"></div>
				<div class="row">
					<br>
					<div id="editcharimg-submit-wrapper">
						<div id="editcharimg-btn" class="btn waves-effect waves-light submitter col s12 m4 l2">
							save
						</div>
					</div>
					<div id="editcharimg-progress-wrapper" class="hide">
						<div class="progress">
							<div class="indeterminate"></div>
						</div>
					</div>
<?php endif; ?>
				</div>
			</div>
<?php endif; ?>
<?php else: ?>
<?php if (!isset($character)): ?>
			<div class="section">
				<p class="flow-text">This character does not exist or has been made private by the owner.</p>
			</div>
<?php else: ?>
			<div class="section">
				<div class="row">
					<div class="col s6 offset-s3 m4 center force-square-contents">
						<?= UniversalFunctions::getStrictCircleImageHTML(ROOTDIR.\Catalyst\Form\FileUpload::FOLDERS[\Catalyst\Form\FileUpload::CHARACTER_IMAGE]."/".$character->getPrimaryImage()[0], $character->getPrimaryImage()[2]) ?>
					</div>
					<div class="col s12 m7 offset-m1">
						<div class="col s12 center-on-small-only">
							<h2 class="header hide-on-small-only no-margin"><?= htmlspecialchars($character->getName()) ?></h2>
							
							<br class="hide-on-med-and-up">
							<h3 class="header hide-on-med-and-up no-margin"><?= htmlspecialchars($character->getName()) ?></h3>

							<p class="flow-text no-margin">Owned by <a href="<?= ROOTDIR ?>User/<?= $character->getOwner()->getUsername() ?>"><?= htmlspecialchars($character->getOwner()->getNickname()) ?></a></p>

<?php if (User::isLoggedIn() && $_SESSION["user"]->getId() == $character->getOwnerId()): ?>
							<p class="flow-text"><a href="<?= ROOTDIR ?>Character/<?= $character->getToken() ?>/Edit/">Edit</a></p>
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
					<div class="col s12 raw-markdown">
<?= htmlspecialchars($character->getDescription()) ?>
					</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section">
				<div class="row">
<?php if ($character->getImages()[0][0] == "default.png"): ?>
					<p class="flow-text">There are no images of this character yet!</p>
<?php else: ?>
<?php
$images = $character->getImages();
if (!\Catalyst\User\User::isLoggedIn() || !$_SESSION["user"]->isNsfw()) {
	$images = array_values(array_filter($images, function($in) { return !$in[2]; }));
}

$images = array_map(function($in) {
	return UniversalFunctions::renderImageCard(ROOTDIR.\Catalyst\Form\FileUpload::FOLDERS[\Catalyst\Form\FileUpload::CHARACTER_IMAGE]."/".$in[0], false, "", $in[1], "");
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
<?php endif; ?>
<?php endif; ?>

<?php
require_once Values::FOOTER_INC;
