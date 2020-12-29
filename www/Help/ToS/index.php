<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::TERMS_OF_SERVICE[0]);
define("PAGE_TITLE", Values::createTitle(Values::TERMS_OF_SERVICE[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Terms of Service");
?>
			<div class="row">
				<div class="col s12 m9 l10">
					<div class="section hide-on-med-and-up">
						<?= Values::createInlineTOC([
							["md-header-definitions", "Definitions"],
							["md-header-acceptance", "Acceptance"],
							["md-header-account", "Account"],
							["md-header-cost", "Cost"],
							["md-header-acceptable-uses", "Acceptable Uses"],
							["md-header-user-content", "User Content"],
							["md-header-our-role", "Our Role"],
							["md-header-copyright-protection", "Copyright Protection"],
							["md-header-api", "API"],
							["md-header-modifying-and-terminating-our-services", "Modifying and Terminating our Services"],
							["md-header-open-source-licensing", "Open Source Licensing"],
							["md-header-warranties-and-disclaimers", "Warranties and Disclaimers"],
							["md-header-liability", "Liability"],
							["md-header-business-usage-of-our-services", "Business Usage of Our Services"],
							["md-header-communication", "Communication"],
							["md-header-release-and-indemnification", "Release and Indemnification"],
						]) ?>
					</div>
					<div class="divider hide-on-med-and-up"></div>

					<div class="raw-markdown"><?= file_get_contents(REAL_ROOTDIR."internal_assets/legal/TERMS_OF_SERVICE.md") ?></div>
				</div>
			
				<div class="col s12 m3 l2 hide-on-small-only">
					<?= Values::createTOC([
						["md-header-definitions", "Definitions"],
						["md-header-acceptance", "Acceptance"],
						["md-header-account", "Account"],
						["md-header-cost", "Cost"],
						["md-header-acceptable-uses", "Acceptable Uses"],
						["md-header-user-content", "User Content"],
						["md-header-our-role", "Our Role"],
						["md-header-copyright-protection", "Copyright Protection"],
						["md-header-api", "API"],
						["md-header-modifying-and-terminating-our-services", "Modifying and Terminating our Services"],
						["md-header-open-source-licensing", "Open Source Licensing"],
						["md-header-warranties-and-disclaimers", "Warranties and Disclaimers"],
						["md-header-liability", "Liability"],
						["md-header-business-usage-of-our-services", "Business Usage of Our Services"],
						["md-header-communication", "Communication"],
						["md-header-release-and-indemnification", "Release and Indemnification"],
					]) ?>
				</div>
			</div>
<?php
require_once Values::FOOTER_INC;


