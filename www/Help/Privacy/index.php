<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::PRIVACY_POLICY[0]);
define("PAGE_TITLE", Values::createTitle(Values::PRIVACY_POLICY[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Privacy Policy");
?>
			<div class="row">
				<div class="col s12 m9 l10">
					<div class="section hide-on-med-and-up">
						<?= Values::createInlineTOC([
							["md-header-scope-and-updates-to-this-policy", "Scope and Updates to this Policy"],
							["md-header-data-collection-and-usage", "Data Collection and Usage"],
							["md-header-cookies-and-tracking-information", "Cookies and Tracking Information"],
							["md-header-disclosure-and-sharing-of-personal-data", "Disclosure and Sharing of Personal Data"],
							["md-header-privacy-of-children-s-data", "Privacy of Children's Data"],
							["md-header-deletion-standards-and-data-retention", "Deletion Standards and Data Retention"],
							["md-header-third-party-links", "Third Party Links"],
							["md-header-security", "Security"],
							["md-header-transfer-of-data", "Transfer of Data"],
							["md-header-rights-to-your-information", "Rights to Your Information"],
							["md-header-contact", "Contact"],
						]) ?>
					</div>
					<div class="divider hide-on-med-and-up"></div>

					<div class="raw-markdown"><?= file_get_contents(REAL_ROOTDIR."legal/PRIVACY_POLICY.md") ?></div>
				</div>
			
				<div class="col s12 m3 l2 hide-on-small-only">
					<?= Values::createTOC([
						["md-header-scope-and-updates-to-this-policy", "Scope and Updates to this Policy"],
						["md-header-data-collection-and-usage", "Data Collection and Usage"],
						["md-header-cookies-and-tracking-information", "Cookies and Tracking Information"],
						["md-header-disclosure-and-sharing-of-personal-data", "Disclosure and Sharing of Personal Data"],
						["md-header-privacy-of-children-s-data", "Privacy of Children's Data"],
						["md-header-deletion-standards-and-data-retention", "Deletion Standards and Data Retention"],
						["md-header-third-party-links", "Third Party Links"],
						["md-header-security", "Security"],
						["md-header-transfer-of-data", "Transfer of Data"],
						["md-header-rights-to-your-information", "Rights to Your Information"],
						["md-header-contact", "Contact"],
					]) ?>
				</div>
			</div>
<?php
require_once Values::FOOTER_INC;


