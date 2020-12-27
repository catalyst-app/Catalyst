<?php

define("ROOTDIR", "../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\Character\Character;
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\HTTPCode;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

$id = $user = null;
if (array_key_exists("q", $_GET)) {
	$id = User::getIdFromUsername($_GET["q"]); 
	if ($id !== -1) {
		$user = new User($id);
		define("PAGE_IMAGE", $user->getImage()->getFullPath());
	} else {
		HTTPCode::set(404);
	}
} else {
	HTTPCode::set(400);
}

define("PAGE_KEYWORD", Values::USER_PROFILE[0]);
define("PAGE_TITLE", Values::createTitle(Values::USER_PROFILE[1], ["name" => (isset($user) ? $user->getNickname() : "Invalid User")]));

if (!is_null($user)) {
	define("PAGE_COLOR", $user->getColor());
} elseif (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("User Profile");

?>
		<?php if (is_null($user)): ?>
			<div class="section">
				<p class="flow-text">This account does not exist or has been deleted.</p>
			</div>
		<?php else: ?>
			<div class="section">
				<div class="row">
					<div class="col s6 offset-s3 m4 center force-square-contents">
						<?= $user->getImage()->getStrictCircleHtml() ?>
					</div>
					<div class="col s12 m7 offset-m1">
						<div class="col s12 center-on-small-only">
							<h2 class="header hide-on-small-only no-margin"><?= htmlspecialchars($user->getNickname()) ?></h2>
							
							<br class="hide-on-med-and-up">
							<h3 class="header hide-on-med-and-up no-margin"><?= htmlspecialchars($user->getNickname()) ?></h3>

							<p class="flow-text no-margin"><?= $user->getUsername() ?></p>

							<?php if (User::isLoggedIn() && $_SESSION["user"]->getId() == $id): ?>
								<p class="flow-text no-margin"><a href="<?=ROOTDIR?>Dashboard">View Dashboard</a></p>
							<?php else: ?>
								<br>
							<?php endif; ?>
							<?php if (!is_null($user->getArtistPage())): ?>
								<p class="flow-text no-margin"><?= htmlspecialchars($user->getNickname()) ?> takes commissions: <a href="<?= ROOTDIR."Artist/".$user->getArtistPage()->getUrl() ?>"><?= htmlspecialchars($user->getArtistPage()->getName()) ?></a></p>
							<?php endif; ?>

							<br>

							<?= $user->getMessageButton() ?>

							<br>

							<div class="social-chips">
								<?= $user->getSocialChipHtml(false) ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section">
				<h4>Characters</h4>
				<?php
				$characters = Character::getPublicCharactersFromUser($user);
				$cards = [];
				foreach ($characters as $character) {
					$cardContents = $character->getImage()->getCard($character->getName(), "", true, ROOTDIR."Character/View/".$character->getToken()."/", [], false);
					if (!empty($cardContents)) {
						$cards[] = '<div class="col s8 m4 l3">'.$cardContents.'</div>';
					}
				}
				?>
				<?php if (count($cards) === 0): ?>
					<p class="flow-text">This user has no public characters</p>
				<?php else: ?>
					<div class="horizontal-scrollable-container row">
						<?= implode("", $cards) ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="divider"></div>
			<div class="divider"></div>
			<div class="section">
				<h4>Wishlist</h4>
				<?php
				$ids = $user->getWishlistCommissionTypeIds();
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
					<p class="flow-text">This user has not added anything to their wishlist!</p>
				<?php else: ?>
					<div class="horizontal-scrollable-container row">
						<?= implode("", $cards) ?>
					</div>
				<?php endif; ?>
			</div>
<?php
endif;
require_once Values::FOOTER_INC;
