<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Character\Character;
use \Catalyst\Database\Character\EditCharacter;
use \Catalyst\Form\FormHTML;
use \Catalyst\Images\Image;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::CHARACTERS[0]);
define("PAGE_TITLE", Values::createTitle(Values::CHARACTERS[1], []));


if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("My Characters");
?>

<?php if (!User::isLoggedIn()): ?>
			<?= User::getNotLoggedInHtml() ?>
<?php else: ?>
			<div class="section">
				<div class="row">
<?php
$characters = Character::getCharactersFromUser($_SESSION["user"]);

$cards = [];

foreach ($characters as $character) {
	$cards[] = $character->getImage()->getCard($character->getName(), "", true, ROOTDIR."Character/View/".$character->getToken(), [], true);
}

array_unshift($cards, Image::getNewItemImage()->getCard("New Character", "", true, ROOTDIR."Character/New"));

$bisected = [[],[]];
$trisected = [[],[],[]];

for ($i=0; $i < count($cards); $i++) { 
	$bisected[$i%2][] = $cards[$i];
	$trisected[$i%3][] = $cards[$i];
}

?>
					<div class="col s12 hide-on-med-and-up">
						<?= implode("", $cards) ?>
					</div>
					<div class="col s12 m6 hide-on-large-only hide-on-small-only">
						<?= implode("", $bisected[0]) ?>
					</div>
					<div class="col s12 m6 hide-on-large-only hide-on-small-only">
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

<?php
require_once Values::FOOTER_INC;
