<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Database\FeatureBoard\NewFeature;
use \Catalyst\Form\FormHTML;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::FEATURE_BOARD[0]);
define("PAGE_TITLE", Values::createTitle(Values::FEATURE_BOARD[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Submit a Feature");

if (FormHTML::testAjaxSubmissionFailed()) {
	echo FormHTML::getAjaxSubmissionHtml();
} elseif (User::isLoggedOut()) {
	echo User::getNotLoggedInHTML();
} else {
?>
		<div class="section">
			<p class="flow-text">Please, please, please please <strong>please</strong> (yes five pleases!) check the current list of feature requests before submitting.  If your idea is similar, feel free to add it as a comment - duplicate requests will be deleted.</p>
			<p class="flow-text">It is <strong>highly recommended</strong> that you speak with staff and users on Discord or Telegram to ensure that people want this, and that your request fully represents what people want.</p>
			<p class="flow-text">If an overwhelming amount think the feature would detract from the site, please do not waste yours and our time with the request.</p>
			<p class="flow-text">Your feature request should move Catalyst forward and towards our vision.  As <a href="http://news.php.net/php.internals/66065">said by Zeev Suraski</a> “Consider only features which have significant traction to a large chunk of our userbase, and not something that could be useful in some extremely specialized edge cases […] Make sure you think about the full context, the huge audience out there, the consequences of making the learning curve steeper with every new feature, and the scope of the goodness that those new features bring.”</p>
			<p>A lot of the layout for this is based off the PHP RFC system (the above line is straight off the RFC howto page), which I (Fauxil) really like for some reason.</p>
		</div>
		<div class="divider"></div>
<?php
	echo FormHTML::generateForm(NewFeature::getFormStructure());
}

require_once Values::FOOTER_INC;
