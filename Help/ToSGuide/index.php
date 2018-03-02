<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::TOS_GUIDE[0]);
define("PAGE_TITLE", Values::createTitle(Values::TOS_GUIDE[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Terms of Service Guide");
?>
		<div class="row">
			<div class="col s12 m9 l10">
				<div class="section hide-on-med-and-up">
					<?= Values::createInlineTOC([
						["intro", "Introduction"],
						["limits", "Limits"],
						["contact-preferences", "Contact Preferences"],
						["payment", "Payment"],
						["cancellation", "Cancellation"],
						["shipping", "Shipping"],
						["sharing-of-finished-art", "Sharing Of Finished Art"],
						["copyright", "Copyright"],
						["changes", "Changes"],
						["hall-of-fame", "Hall of Fame"],
					]) ?>
				</div>
				<div class="divider hide-on-med-and-up"></div>
				<div class="section" id="intro">
					<h4>Introduction</h4>
				</div>
				<div class="divider"></div>
				<div class="section" id="limits">
					<h4>Limits</h4>
				</div>
				<div class="divider"></div>
				<div class="section" id="contact-preferences">
					<h4>Contact Preferences</h4>
				</div>
				<div class="divider"></div>
				<div class="section" id="payment">
					<h4>Payment</h4>
				</div>
				<div class="divider"></div>
				<div class="section" id="cancellation">
					<h4>Cancellation</h4>
				</div>
				<div class="divider"></div>
				<div class="section" id="shipping">
					<h4>Shipping</h4>
				</div>
				<div class="divider"></div>
				<div class="section" id="sharing-of-finished-art">
					<h4>Sharing Of Finished Art</h4>
				</div>
				<div class="divider"></div>
				<div class="section" id="copyright">
					<h4>Copyright</h4>
				</div>
				<div class="divider"></div>
				<div class="section" id="changes">
					<h4>Changes</h4>
				</div>
				<div class="divider"></div>
				<div class="section" id="hall-of-fame">
					<h4>Hall of Fame</h4>
				</div>
			</div>
			<div class="col s12 m3 l2 hide-on-small-only">
				<?= Values::createTOC([
					["intro", "Introduction"],
					["limits", "Limits"],
					["contact-preferences", "Contact Preferences"],
					["payment", "Payment"],
					["cancellation", "Cancellation"],
					["shipping", "Shipping"],
					["sharing-of-finished-art", "Sharing Of Finished Art"],
					["copyright", "Copyright"],
					["changes", "Changes"],
					["hall-of-fame", "Hall of Fame"],
				]) ?>
			</div>
		</div>
<?php
require_once Values::FOOTER_INC;


