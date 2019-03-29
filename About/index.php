<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Form\FormRepository;
use \Catalyst\Images\{Folders, Image};
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\{Patron, Staff, User};

define("PAGE_KEYWORD", Values::ABOUT_US[0]);
define("PAGE_TITLE", Values::createTitle(Values::ABOUT_US[1], []));
define("NO_HEADER", 1);

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;
?></div>
		<div class="row lighten-half center align-center" id="about-definition">
			<h1 class="tinted-white-text no-bottom-margin">
				cat<span class="dark-tinted-white-text">·</span>a<span class="dark-tinted-white-text">·</span>lyst
			</h1>
			<h5 class="dark-tinted-white-text no-margin">noun | \ˈka-tə-ləst\</h5>

			<p class="small-margin flow-text white-text">"an agent that provokes or speeds significant change or action" –<a href="https://www.merriam-webster.com/dictionary/catalyst" class="white-text">Merriam&nbsp;Webster</a></p>
		</div>

		<div class="container center align-center">
			<p class="small-margin flow-text">At Catalyst, we strive to improve and quicken the process of commissioning, providing a simple, unified, and mobile-friendly way for artists to list their prices, receive and track commissions, and so much more.</p>

			<p class="small-margin flow-text"><strong>Please note, we are currently in a private beta.</strong>  If you are interested in participating, you may apply in our Telegram and Discord communities.</p>

			<p class="small-margin flow-text">Take a look below to see some of our features!</p>
		</div>

		<div class="row lighten-quarter center align-center" style="padding: 1rem 0 2rem 0;">
			<div class="container">
				<p class="align-center center tinted-white-text" style="margin-bottom: 1.4em;"><i>Click any of the below features to learn more!</i></p>
			</div>
			<p style="margin-bottom: 1.4em;">
				<span class="flow-text white-text about-page-inline-block about-page-inline-block-selected" data-reveal-clump="feature" data-reveal="feature-character-storage">
					<strong>Character Storage</strong>
				</span>
				<span class="flow-text white-text about-page-inline-block" data-reveal-clump="feature" data-reveal="feature-connected-profiles">
					<strong>Connected Profiles</strong>
				</span>
				<span class="flow-text white-text about-page-inline-block" data-reveal-clump="feature" data-reveal="feature-flexible-commission-types">
					<strong>Flexible Commission Types</strong>
				</span>
				<span class="flow-text white-text about-page-inline-block" data-reveal-clump="feature" data-reveal="feature-smart-commission-management">
					<strong>Smart Commission Management</strong>
				</span>
				<span class="flow-text white-text about-page-inline-block" data-reveal-clump="feature" data-reveal="feature-wishlists">
					<strong>Wish-Lists</strong>
				</span>
				<span class="flow-text white-text about-page-inline-block" data-reveal-clump="feature" data-reveal="feature-zero-fee-payments">
					<strong>Zero-Fee Flexible Payments</strong>
				</span>
				<span class="flow-text white-text about-page-inline-block" data-reveal-clump="feature" data-reveal="feature-user-powered">
					<strong>User Powered</strong>
				</span>
			</p>
			<div class="container">
				<div>
					<div id="feature-character-storage" data-clump="feature">
						<p class="tinted-white-text small-margin flow-text">Gone are the days of re-uploading reference sheets, digging through your computer for examples, or forgetting some critical information that your character needs.  Gone are the days of artists not having enough to go on or missing details.</p>
						<p class="tinted-white-text small-margin flow-text">With Catalyst, users can create as many character profiles as they would like and easily sharing them using custom links.  These profiles are entirely customizable to suit the needs of any character: users can upload and caption as many images as they would like, add full markdown descriptions, change the page colors, and so much more.</p>

						<p class="tinted-white-text flow-text">Please explore these wonderful examples from our community of beta testers: <a href="https://c.catl.st/keeri/" class="white-text">Keeri</a>, <a href="https://c.catl.st/vmy3ad3/" class="white-text">Hidoni</a>, <a href="https://c.catl.st/aolwglb/" class="white-text">Sauksauk</a>.</p>
					</div>
					<div id="feature-connected-profiles" data-clump="feature" class="hide">
						<p class="tinted-white-text flow-text">This description is still being written - please check back later!</p>
					</div>
					<div id="feature-flexible-commission-types" data-clump="feature" class="hide">
						<p class="tinted-white-text flow-text">This description is still being written - please check back later!</p>
					</div>
					<div id="feature-smart-commission-management" data-clump="feature" class="hide">
						<p class="tinted-white-text flow-text">This description is still being written - please check back later!</p>
					</div>
					<div id="feature-wishlists" data-clump="feature" class="hide">
						<p class="tinted-white-text flow-text">This description is still being written - please check back later!</p>
					</div>
					<div id="feature-zero-fee-payments" data-clump="feature" class="hide">
						<p class="tinted-white-text flow-text">This description is still being written - please check back later!</p>
					</div>
					<div id="feature-user-powered" data-clump="feature" class="hide">
						<p class="tinted-white-text flow-text">This description is still being written - please check back later!</p>
					</div>
				</div>
			</div>
		</div>

		<div class="row container center align-center">
			<p class="flow-text">Would you like to join our mailing list?  We will keep you updated about everything Catalyst, feature select artists, and much more, typically in no more than one email a week!</p>

			<?= FormRepository::getEmailListAdditionForm()->getHtml() ?>
		</div>

		<div class="row lighten-tenth center align-center" style="padding: 1rem 0 2rem 0;">
			<div class="container">
				<p class="align-center center tinted-white-text" style="margin-bottom: 1.4em;"><i>You can find us all over the web!  Click any of the below social networks to follow us!</i></p>
			</div>
			<?php
			$networks = [
				["href" => "mailto:catalyst@catalystapp.co", "img" => "mail", "label" => "Email Us"],
				["href" => "https://gh.catl.st/", "img" => "github", "label" => "GitHub"],
				["href" => "https://tw.catl.st/", "img" => "twitter", "label" => "Twitter"],
				["href" => "https://d.catl.st/", "img" => "discord", "label" => "Discord"],
				["href" => "https://fa.catl.st/", "img" => "furaffinity", "label" => "FurAffinity"],
				["href" => "https://da.catl.st/", "img" => "deviantart", "label" => "DeviantArt"],
				["href" => "https://weasyl.catl.st/", "img" => "weasyl", "label" => "Weasyl"],
				["href" => "https://fn.catl.st/", "img" => "furrynetwork", "label" => "FurryNetwork"],
				["href" => "https://r.catl.st/", "img" => "reddit", "label" => "Reddit"],
				["href" => "https://steam.catl.st/", "img" => "steam", "label" => "Steam"],
				["href" => "https://ig.catl.st/", "img" => "instagram", "label" => "Instagram"],
				["href" => "https://ko-fi.catl.st/", "img" => "kofi", "label" => "Ko-Fi"],
				["href" => "https://patreon.catl.st/", "img" => "patreon", "label" => "Patreon"],
				["href" => "https://tumblr.catl.st/", "img" => "tumblr", "label" => "Tumblr"],
				["href" => "https://fb.catl.st/", "img" => "facebook", "label" => "Facebook"],
				["href" => "https://t.catl.st", "img" => "telegram", "label" => "Telegram"],
				["href" => "https://telegram-announcements.catl.st", "img" => "telegram_announcements", "label" => "Telegram Announcements"],
				["href" => "https://trello.catl.st/", "img" => "trello", "label" => "Trello"],
			];
			?>
			<p style="margin-bottom: 1.4em;">
				<?php foreach ($networks as ["href" => $href, "img" => $img, "label" => $label]): ?>
					<span class="about-page-inline-block about-page-inline-block-no-border tooltipped" data-tooltip="<?= htmlspecialchars($label) ?>" data-reveal-clump="inapplicable" data-reveal="inapplicable">
						<a href="<?= $href ?>" target="_blank" rel="noopener">
							<?= (new Image(Folders::ABOUT_ICONS, "", $img.'.png'))->getImgElementHtml(["about-page-social-icon"]) ?>
						</a>
					</span>
				<?php endforeach; ?>
			</p>
		</div>

		<div class="row center align-center">
			<h4>Meet our amazing staff team!</h4>
			<p><i>Click to learn more about each member or follow them on social networks!</i></p>
			<?php foreach (Staff::getAll() as $staff): ?>
				<div class="about-page-inline-block about-page-inline-block-no-border" data-reveal-clump="staff" data-reveal="staff-<?= UniversalFunctions::toDashCase($staff->getName()) ?>">
					<?= $staff->getImage()->getStrictCircleHtml() ?>
					<br>
					<span class="flow-text black-text"><?= htmlspecialchars($staff->getName()) ?></span>
				</div>
			<?php endforeach; ?>
			<div class="container">
				<div>
					<?php foreach (Staff::getAll() as $staff): ?>
						<div id="staff-<?= UniversalFunctions::toDashCase($staff->getName()) ?>" data-clump="staff" class="hide">
							<hr>
							<p class="flow-text raw-inline-markdown"><?= str_replace("\r\n", '</p><p class="flow-text raw-inline-markdown">', $staff->getDescription()) ?></p>
							<br>
							<div class="social-chips">
								<?= SocialMedia::getChipHtml(SocialMedia::getChipArray(json_decode($staff->getSocialMediaChips(), true))) ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		
		<div class="row center align-center lighten-quarter" style="padding: 1rem 0 2rem 0;">
			<h4 class="tinted-white-text">Thanks to our wonderful patrons!</h4>
			<p class="tinted-white-text no-bottom-margin"><i>You could get your name here for as low as $1 a month!  Find out how <a href="https://p.catl.st/" target="_blank" class="white-text" rel="noopener">here</a>.</i></p>
			<p class="tinted-white-text no-top-margin"><i>Please note that Catalyst does not endorse/support any of the below people and the patrons listed below do not officially represent Catalyst.</i></p>
			<?php
			$patrons = Patron::getAll();

			// patrons should already be sorted from the database, we don't need to do much more besides clump
			$rankedPatrons = [
				Patron::TIER_DEAD => [],
				Patron::TIER_BASE => [],
				Patron::TIER_BRONZE => [],
				Patron::TIER_SILVER => [],
				Patron::TIER_GOLD => [],
				Patron::TIER_PLATINUM => [],
			];

			foreach ($patrons as $patron) {
				$rankedPatrons[$patron->getPledgeLevel()][] = $patron;
			}
			?>
			<?php if (count($rankedPatrons[Patron::TIER_PLATINUM])): ?>
				<?php
				usort($rankedPatrons[Patron::TIER_PLATINUM], function(Patron $a, Patron $b) : int {
					return $a->getSince() <=> $b->getSince();
				});
				?>
				<h5 class="tinted-white-text"><strong>Titanium Level</strong></h5>
				<div class="container" style="display: flex; flex-wrap: wrap; align-content: center; justify-content: center;">
					<?php foreach ($rankedPatrons[Patron::TIER_PLATINUM] as $patron): ?>
						<div class="about-page-inline-block about-page-inline-block-no-border" data-reveal-clump="titanium-patron" data-reveal="titanium-patron-<?= UniversalFunctions::toDashCase($patron->getName()) ?>">
							<div class="force-square-contents" style="width: 100%;">
								<?= $patron->getImage()->getStrictCircleHtml() ?>
							</div>
							<br>
							<span class="flow-text tinted-white-text"><strong><?= htmlspecialchars($patron->getName()) ?></strong></span>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="container">
					<div>
						<?php foreach ($rankedPatrons[Patron::TIER_PLATINUM] as $patron): ?>
							<div id="titanium-patron-<?= UniversalFunctions::toDashCase($patron->getName()) ?>" data-clump="titanium-patron" class="hide">
								<hr style="opacity: 0.3;">
								<p class="flow-text small-margin tinted-white-text"><i>Patron since <?= $patron->getSince()->format("F Y") ?>, total contribution $<?= number_format($patron->getTotalCents()/100, 0) ?>, current contribution $<?= number_format($patron->getPledgedCents()/100, 0) ?>/month.</i></p>
								<p class="flow-text small-margin tinted-white-text raw-inline-markdown"><?= str_replace("\r\n", '</p><p class="flow-text small-margin tinted-white-text raw-inline-markdown">', $patron->getDescription()) ?></p>
								<?php if (count(json_decode($patron->getSocialChips(), true))): ?>
									<div class="social-chips">
										<?= SocialMedia::getChipHtml(SocialMedia::getChipArray(json_decode($patron->getSocialChips(), true))) ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
					<hr>
				</div>
			<?php endif; ?>

			
			<?php if (count($rankedPatrons[Patron::TIER_GOLD])): ?>
				<?php
				usort($rankedPatrons[Patron::TIER_GOLD], function(Patron $a, Patron $b) : int {
					return $a->getSince() <=> $b->getSince();
				});
				?>
				<h5 class="tinted-white-text"><strong>Gold Level</strong></h5>
				<div class="container" style="display: flex; flex-wrap: wrap; align-content: center; justify-content: center;">
					<?php foreach ($rankedPatrons[Patron::TIER_GOLD] as $patron): ?>
						<div class="about-page-inline-block about-page-inline-block-no-border" data-reveal-clump="gold-patron" data-reveal="gold-patron-<?= UniversalFunctions::toDashCase($patron->getName()) ?>">
							<div class="force-square-contents" style="width: 100%;">
								<?= $patron->getImage()->getStrictCircleHtml() ?>
							</div>
							<br>
							<span class="flow-text tinted-white-text"><strong><?= htmlspecialchars($patron->getName()) ?></strong></span>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="container">
					<div>
						<?php foreach ($rankedPatrons[Patron::TIER_GOLD] as $patron): ?>
							<div id="gold-patron-<?= UniversalFunctions::toDashCase($patron->getName()) ?>" data-clump="gold-patron" class="hide">
								<hr style="opacity: 0.3;">
								<p class="flow-text small-margin tinted-white-text"><i>Patron since <?= $patron->getSince()->format("F Y") ?>, total contribution $<?= number_format($patron->getTotalCents()/100, 0) ?>, current contribution $<?= number_format($patron->getPledgedCents()/100, 0) ?>/month.</i></p>
								<p class="flow-text small-margin tinted-white-text raw-inline-markdown"><?= str_replace("\r\n", '</p><p class="flow-text small-margin tinted-white-text raw-inline-markdown">', $patron->getDescription()) ?></p>
								<?php if (count(json_decode($patron->getSocialChips(), true))): ?>
									<div class="social-chips">
										<?= SocialMedia::getChipHtml(SocialMedia::getChipArray(json_decode($patron->getSocialChips(), true))) ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
					<hr>
				</div>
			<?php endif; ?>

			
			<?php if (count($rankedPatrons[Patron::TIER_SILVER])): ?>
				<?php
				usort($rankedPatrons[Patron::TIER_SILVER], function(Patron $a, Patron $b) : int {
					return $a->getSince() <=> $b->getSince();
				});
				?>
				<h5 class="tinted-white-text"><strong>Silver Level</strong></h5>
				<div class="container" style="display: flex; flex-wrap: wrap; align-content: center; justify-content: center;">
					<?php foreach ($rankedPatrons[Patron::TIER_SILVER] as $patron): ?>
						<div class="about-page-inline-block about-page-inline-block-no-border" data-reveal-clump="silver-patron" data-reveal="silver-patron-<?= UniversalFunctions::toDashCase($patron->getName()) ?>">
							<div class="force-square-contents" style="width: 100%;">
								<?= $patron->getImage()->getStrictCircleHtml() ?>
							</div>
							<br>
							<span class="flow-text tinted-white-text"><strong><?= htmlspecialchars($patron->getName()) ?></strong></span>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="container">
					<div>
						<?php foreach ($rankedPatrons[Patron::TIER_SILVER] as $patron): ?>
							<div id="silver-patron-<?= UniversalFunctions::toDashCase($patron->getName()) ?>" data-clump="silver-patron" class="hide">
								<hr style="opacity: 0.3;">
								<p class="flow-text small-margin tinted-white-text"><i>Patron since <?= $patron->getSince()->format("F Y") ?>, total contribution $<?= number_format($patron->getTotalCents()/100, 0) ?>, current contribution $<?= number_format($patron->getPledgedCents()/100, 0) ?>/month.</i></p>
								<p class="flow-text small-margin tinted-white-text raw-inline-markdown"><?= str_replace("\r\n", '</p><p class="flow-text small-margin tinted-white-text raw-inline-markdown">', $patron->getDescription()) ?></p>
								<?php if (count(json_decode($patron->getSocialChips(), true))): ?>
									<div class="social-chips">
										<?= SocialMedia::getChipHtml(SocialMedia::getChipArray(json_decode($patron->getSocialChips(), true))) ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
					<hr>
				</div>
			<?php endif; ?>

			
			<?php if (count($rankedPatrons[Patron::TIER_BRONZE])): ?>
				<?php
				usort($rankedPatrons[Patron::TIER_BRONZE], function(Patron $a, Patron $b) : int {
					return $a->getSince() <=> $b->getSince();
				});
				?>
				<h5 class="tinted-white-text"><strong>Bronze Level</strong></h5>
				<div class="container" style="display: flex; flex-wrap: wrap; align-content: center; justify-content: center;">
					<?php foreach ($rankedPatrons[Patron::TIER_BRONZE] as $patron): ?>
						<div class="about-page-inline-block about-page-inline-block-no-border" data-reveal-clump="bronze-patron" data-reveal="bronze-patron-<?= UniversalFunctions::toDashCase($patron->getName()) ?>">
							<div class="force-square-contents" style="width: 100%;">
								<?= $patron->getImage()->getStrictCircleHtml() ?>
							</div>
							<br>
							<span class="flow-text tinted-white-text"><?= htmlspecialchars($patron->getName()) ?></span>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="container">
					<div>
						<?php foreach ($rankedPatrons[Patron::TIER_BRONZE] as $patron): ?>
							<div id="bronze-patron-<?= UniversalFunctions::toDashCase($patron->getName()) ?>" data-clump="bronze-patron" class="hide">
								<hr style="opacity: 0.3;">
								<p class="flow-text small-margin tinted-white-text"><i>Patron since <?= $patron->getSince()->format("F Y") ?>, total contribution $<?= number_format($patron->getTotalCents()/100, 0) ?>, current contribution $<?= number_format($patron->getPledgedCents()/100, 0) ?>/month.</i></p>
								<p class="flow-text small-margin tinted-white-text raw-inline-markdown"><?= str_replace("\r\n", '</p><p class="flow-text small-margin tinted-white-text raw-inline-markdown">', $patron->getDescription()) ?></p>
								<?php if (count(json_decode($patron->getSocialChips(), true))): ?>
									<div class="social-chips">
										<?= SocialMedia::getChipHtml(SocialMedia::getChipArray(json_decode($patron->getSocialChips(), true))) ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
					<hr>
				</div>
			<?php endif; ?>

			
			<?php if (count($rankedPatrons[Patron::TIER_BASE])): ?>
				<?php
				usort($rankedPatrons[Patron::TIER_BASE], function(Patron $a, Patron $b) : int {
					return $a->getSince() <=> $b->getSince();
				});
				?>
				<h5 class="tinted-white-text"><strong>Base Level</strong></h5>
				<div class="container" style="display: flex; flex-wrap: wrap; align-content: center; justify-content: center;">
					<?php foreach ($rankedPatrons[Patron::TIER_BASE] as $patron): ?>
						<div class="about-page-inline-block about-page-inline-block-no-border" data-reveal-clump="base-patron" data-reveal="base-patron-<?= UniversalFunctions::toDashCase($patron->getName()) ?>">
							<div class="force-square-contents" style="width: 100%;">
								<?= $patron->getImage()->getStrictCircleHtml() ?>
							</div>
							<br>
							<span class="flow-text tinted-white-text"><?= htmlspecialchars($patron->getName()) ?></span>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="container">
					<div>
						<?php foreach ($rankedPatrons[Patron::TIER_BASE] as $patron): ?>
							<div id="base-patron-<?= UniversalFunctions::toDashCase($patron->getName()) ?>" data-clump="base-patron" class="hide">
								<hr style="opacity: 0.3;">
								<p class="flow-text small-margin tinted-white-text"><i>Patron since <?= $patron->getSince()->format("F Y") ?>, total contribution $<?= number_format($patron->getTotalCents()/100, 0) ?>, current contribution $<?= number_format($patron->getPledgedCents()/100, 0) ?>/month.</i></p>
								<p class="flow-text small-margin tinted-white-text raw-inline-markdown"><?= str_replace("\r\n", '</p><p class="flow-text small-margin tinted-white-text raw-inline-markdown">', $patron->getDescription()) ?></p>
								<?php if (count(json_decode($patron->getSocialChips(), true))): ?>
									<div class="social-chips">
										<?= SocialMedia::getChipHtml(SocialMedia::getChipArray(json_decode($patron->getSocialChips(), true))) ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
					<hr>
				</div>
			<?php endif; ?>

			
			<?php if (count($rankedPatrons[Patron::TIER_DEAD])): ?>
				<?php
				usort($rankedPatrons[Patron::TIER_DEAD], function(Patron $a, Patron $b) : int {
					return $b->getTotalCents() <=> $a->getTotalCents();
				});
				?>
				<h5 class="tinted-white-text"><strong>Former Patrons</strong></h5>
				<div class="container" style="display: flex; flex-wrap: wrap; align-content: center; justify-content: center;">
					<?php foreach ($rankedPatrons[Patron::TIER_DEAD] as $patron): ?>
						<div class="about-page-inline-block about-page-inline-block-no-border" data-reveal-clump="dead-patron" data-reveal="dead-patron-<?= UniversalFunctions::toDashCase($patron->getName()) ?>">
							<div class="force-square-contents" style="width: 100%;">
								<?= $patron->getImage()->getStrictCircleHtml() ?>
							</div>
							<br>
							<span class="flow-text tinted-white-text"><?= htmlspecialchars($patron->getName()) ?></span>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="container">
					<div>
						<?php foreach ($rankedPatrons[Patron::TIER_DEAD] as $patron): ?>
							<div id="dead-patron-<?= UniversalFunctions::toDashCase($patron->getName()) ?>" data-clump="dead-patron" class="hide">
								<hr style="opacity: 0.3;">
								<p class="flow-text small-margin tinted-white-text"><i>Total contribution $<?= number_format($patron->getTotalCents()/100, 0) ?>.</i></p>
								<p class="flow-text small-margin tinted-white-text raw-inline-markdown"><?= str_replace("\r\n", '</p><p class="flow-text small-margin tinted-white-text raw-inline-markdown">', $patron->getDescription()) ?></p>
								<?php if (count(json_decode($patron->getSocialChips(), true))): ?>
									<div class="social-chips">
										<?= SocialMedia::getChipHtml(SocialMedia::getChipArray(json_decode($patron->getSocialChips(), true))) ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<div class="row center align-center no-margin">
			<h4>Have any questions or need more information?</h4>
			<p class="flow-text">Please send us an e-mail at <a href="mailto:catalyst@catalystapp.co">catalyst@catalystapp.co</a> and we will try to respond as soon as possible!</p>
		</div>
<div><?php
require_once Values::FOOTER_INC;
