<?php

define("ROOTDIR", "../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Artist\Artist;
use \Catalyst\CommissionType\{CommissionType, CommissionTypeAttribute};
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
				<h4>Commission Types</h4>
				<?php
				$commissionTypes = CommissionType::getForArtist($artist, true);
				$commissionTypes = array_filter($commissionTypes, function($type) {
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
				?>
				<?php if (empty($commissionTypes)): ?> 
					<p class="flow-text">This artist has no commission types listed!</p>
				<?php else: ?> 
					<?php $firstCommissionType = true; ?>
					<?php foreach ($commissionTypes as $commissionType): ?>
						<?php if (!$firstCommissionType): ?>
							<div class="divider small-bottom-margin"></div>
						<?php endif; ?>
						<div class="commission-type-row" id="ct-<?= htmlspecialchars($commissionType->getToken()) ?>" data-token="<?= htmlspecialchars($commissionType->getToken()) ?>">
							<div class="commission-type-row-header">
								<h5 class="inline-block"><?= htmlspecialchars($commissionType->getName()) ?></h5>
								<?php if (User::isLoggedIn()): ?>
									<?php foreach (["hide-on-small-only horizontal", "hide-on-med-and-up"] as $sizeClasses): ?>
										<div class="fixed-action-btn right <?= $sizeClasses ?> inline-fab commission-type-client-actions right">
											<a class="btn-floating btn-large">
												<i class="large material-icons">more_horiz</i>
											</a>
											<ul>
												<?php if (in_array($commissionType->getId(), $_SESSION["user"]->getWishlistCommissionTypeIds())): ?>
													<li class="tooltipped" data-tooltip="Remove from wishlist">
														<a data-action="wishlist" data-state="on" class="btn-floating">
															<i class="material-icons">star</i>
														</a>
													</li>
												<?php else: ?>
													<li class="tooltipped" data-tooltip="Add to wishlist">
														<a data-action="wishlist" data-state="off" class="btn-floating">
															<i class="material-icons">star_outline</i>
														</a>
													</li>
												<?php endif; ?>
												<li class="tooltipped" data-tooltip="Ask a Question">
													<a href="<?= ROOTDIR ?>Message/New/<?= htmlspecialchars($commissionType->getArtistPage()->getPrecomposedMessageUrl("Commission Inquiry", 'Questions regarding "'.$commissionType->getName()."\"\n\n---\n\n")) ?>" class="btn-floating">
														<i class="material-icons">contact_support</i>
													</a>
												</li>
												<?php if ($commissionType->isAcceptingQuotes()): ?>
													<li class="tooltipped" data-tooltip="Request a Quote">
														<a class="btn-floating" href="<?= ROOTDIR ?>Quote/<?= $commissionType->getToken() ?>/">
															<i class="material-icons">attach_money</i>
														</a>
													</li>
												<?php endif; ?>
												<?php if ($commissionType->isAcceptingRequests()): ?>
													<li class="tooltipped" data-tooltip="Make a Request">
														<a class="btn-floating" href="<?= ROOTDIR ?>Request/<?= $commissionType->getToken() ?>/">
															<i class="material-icons">how_to_vote</i>
														</a>
													</li>
												<?php endif; ?>
												<?php if ($commissionType->isAcceptingTrades()): ?>
													<li class="tooltipped" data-tooltip="Request a Trade">
														<a class="btn-floating" href="<?= ROOTDIR ?>Trade/<?= $commissionType->getToken() ?>/">
															<i class="material-icons">swap_horiz</i>
														</a>
													</li>
												<?php endif; ?>
												<?php if ($commissionType->isAcceptingCommissions()): ?>
													<li class="tooltipped" data-tooltip="Commission">
														<a class="btn-floating" href="<?= ROOTDIR ?>Commission/<?= $commissionType->getToken() ?>/">
															<i class="material-icons">add_shopping_cart</i>
														</a>
													</li>
												<?php endif; ?>
											</ul>
										</div>
									<?php endforeach; ?>
								<?php else: ?>
									<div class="fixed-action-btn horizontal inline-fab commission-type-client-actions right tooltipped" data-position="left" data-tooltip="Please login">
										<a class="btn-floating btn-large grey">
											<i class="large material-icons">more_horiz</i>
										</a>
									</div>
								<?php endif; ?>
							</div>
							<p class="flow-text no-margin col s12">
								<span class="tooltipped" data-tooltip="<?= htmlspecialchars($commissionType->getBaseUsdCost()) ?> USD">
									<?= htmlspecialchars($commissionType->getBaseCost()) ?>
								</span>
							</p>
							<p class="col s12">
								<?= htmlspecialchars($commissionType->getBlurb()) ?>
								(<a class="modal-trigger" href="#commission-type-info-modal-<?= htmlspecialchars($commissionType->getToken()) ?>">More information</a>)
							</p>
							<div class="commission-type-image-examples row">
								<?php $i = 0; ?>
								<?php foreach ($commissionType->getImageSet() as $image): ?>
									<?php $i++; ?>
									<?php if ($i == 3): ?>
										<!-- hide 3rd card on smaller screens -->
										<div class="col s6 l4 hide-on-med-and-down">
											<?= $image->getCard() ?>
										</div>
										<?php break; ?>
									<?php else: ?>
										<div class="col s6 l4">
											<?= $image->getCard() ?>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>

						<div class="modal modal-fixed-footer" id="commission-type-info-modal-<?= htmlspecialchars($commissionType->getToken()) ?>">
							<div class="modal-content">
								<h4><?= htmlspecialchars($commissionType->getName()) ?></h4>

								<div class="divider"></div>

								<div class="raw-markdown"><?= htmlspecialchars($commissionType->getDescription()) ?></div>

								<div class="divider"></div>

								<h5>Attributes:</h5>
								
								<?php
								$attributes = $commissionType->getAttributes();
								$keyedGroups = [];
								foreach ($attributes as $attribute) {
									if (!array_key_exists($attribute->getGroupId(), $keyedGroups)) {
										$keyedGroups[$attribute->getGroupId()] = [];
									}
									$keyedGroups[$attribute->getGroupId()][] = $attribute;
								}
								?>
								<?php if (empty($keyedGroups)): ?>
									<p>None listed</p>
								<?php else: ?>
									<?php foreach ($keyedGroups as $groupId => $attributes): ?>
										<p class="no-margin">
											<strong><?= htmlspecialchars(CommissionTypeAttribute::getGroupLabelFromId($groupId)) ?>:</strong>

											<?php
											$suffixes = UniversalFunctions::getListPunctuationArray(count($attributes));
											?>
											<?php for ($i=0; $i<count($attributes); $i++): ?>
												<span class="tooltipped" data-tooltip="<?= htmlspecialchars($attributes[$i]->getDescription()) ?>">
													<?= htmlspecialchars($attributes[$i]->getName()).$suffixes[$i] ?>
												</span>
											<?php endfor; ?>
										</p>
									<?php endforeach; ?>
								<?php endif; ?>

								<div class="divider"></div>

								<h5>Payment Options:</h5>

								<p>For information regarding payment plans, refund policies, etc., please refer to the artist's <a href="<?= ROOTDIR ?>Artist/ToS/<?= $artist->getUrl() ?>">terms of service</a>.</p>

								<?php
								$paymentOptions = $commissionType->getPaymentOptions();
								$optionStrings = [];
								$suffixes = UniversalFunctions::getListPunctuationArray(count($paymentOptions));
								for ($i=0; $i < count($paymentOptions); $i++) { 
									$optionStrings[] = $paymentOptions[$i]->getType().$suffixes[$i];
								}
								?>
								<p>This artist accepts: <?= implode("", $optionStrings) ?></p>
							</div>
						</div>
						<?php $firstCommissionType = false; ?>
					<?php endforeach; ?> 
				<?php endif; ?>
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
