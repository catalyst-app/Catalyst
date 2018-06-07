<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Character\Character;
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Images\Image;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\HTTPCode;
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::DASHBOARD[0]);
define("PAGE_TITLE", Values::createTitle(Values::DASHBOARD[1], ["name" => (isset($_SESSION["user"]) ? $_SESSION["user"]->getNickname() : "Logged Out")]));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

if (!User::isLoggedIn()) {
	HTTPCode::set(401);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Dashboard");

if (!User::isLoggedIn()):
	echo User::getNotLoggedInHtml();
else: ?>
			<div class="section">
				<div class="row">
					<div class="col s6 offset-s3 m4 center force-square-contents">
						<?= $_SESSION["user"]->getImage()->getStrictCircleHtml() ?>
					</div>
					<div class="col s12 m7 offset-m1">
						<div class="col s12 center-on-small-only">
							<h2 class="header hide-on-small-only no-margin"><?= htmlspecialchars($_SESSION["user"]->getNickname()) ?></h2>
							
							<br class="hide-on-med-and-up">
							<h3 class="header hide-on-med-and-up no-margin"><?= htmlspecialchars($_SESSION["user"]->getNickname()) ?></h3>

							<p class="flow-text no-margin"><?= $_SESSION["user"]->getUsername() ?></p>
							
							<p class="flow-text"><a href="<?=ROOTDIR?>User/<?=$_SESSION["user"]->getUsername()?>">View public profile</a></p>

							<br>

							<div class="social-chips social-chips-editable">
								<?= $_SESSION["user"]->getSocialChipHtml(true) ?>
							</div>
							<?= SocialMedia::getAddChip() ?>
							<?= SocialMedia::getAddModal("User") ?>
						</div>
					</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section">
				<h4>Characters</h4>
				<div class="horizontal-scrollable-container row">
					<?php
					$characters = Character::getCharactersFromUser($_SESSION["user"]);

					$newCharacterImage = Image::getNewItemImage();

					$cards = [
						'<div class="col s8 m4 l3">'.$newCharacterImage->getCard("New Character", "", true, ROOTDIR."Character/New", [], false).'</div>'
					];
					foreach ($characters as $character) {
						$cards[] = '<div class="col s8 m4 l3">'.$character->getImage()->getCard($character->getName(), "", true, ROOTDIR."Character/View/".$character->getToken()."/", [], true).'</div>';
					}
					// there is always at least one because of the add
					?>
					<?= implode("", $cards) ?>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section">
				<h4>Wishlist</h4>
				<?php
				$ids = $_SESSION["user"]->getWishlistCommissionTypeIds();
				$commissionTypes = [];
				foreach ($ids as $id) {
					$commissionTypes[] = new CommissionType($id);
				}
				$commissionTypes = array_filter($commissionTypes, function(CommissionType $type) : bool {
					// if it shouldn't be visible, don't show it (wishlist can contain these)
					if (!$type->isVisible()) {
						return false;
					}

					if (User::isCurrentUserNsfw()) {
						return true;
					}

					// if known SAFE then leave in
					if (in_array("SAFE", $type->getAttributes())) {
						return true;
					}
					// if NOT SFW and mature or explicit, say no
					if (in_array("MATURE", $type->getAttributes()) || in_array("EXPLICIT", $type->getAttributes())) {
						return false;
					}

					// not explicitly marked any way
					return true;
				});
				$commissionTypes = array_unique($commissionTypes);
				$cards = [];
				foreach ($commissionTypes as $type) {
					$cards[] = '<div class="col s8 m4 l3">'.$type->getImage()->getCard(
						$type->getName()." by ".$type->getArtistPage()->getName(), 
						$type->getBlurb(), 
						true, 
						ROOTDIR."Artist/".$type->getArtistPage()->getUrl()."/#ct-".$type->getToken(), 
						[
							$type->getArtistPage()->getColor(),
							(
								$type->isAcceptingCommissions() ||
								$type->isAcceptingTrades() || 
								$type->isAcceptingRequests() 
							) ? $type->getBaseCost() : "CLOSED",
						]
					).'</div>';
				}
				?>
				<?php if (count($cards) === 0): ?>
					<p class="flow-text">Your wishlist is empty!</p>
				<?php else: ?>
					<div class="horizontal-scrollable-container row">
						<?= implode("", $cards) ?>
					</div>
				<?php endif; ?>
			</div>
<?php endif; ?>
<?php
require_once Values::FOOTER_INC;
