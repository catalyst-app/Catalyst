<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::FAQ[0]);
define("PAGE_TITLE", Values::createTitle(Values::FAQ[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("FAQ");
?>
			<div class="row"><div class="col s12 m9 l10">
				<div class="section hide-on-med-and-up">
<?= Values::createInlineTOC([
	["glossary", "Important definitions"],
	["getting-started", "How do I get started?"],
	["rules", "What rules are in place?"],
	["users", "Who can use Catalyst?"],
	["cost", "What does it cost?"],
	["difference", "What sets us apart?"],
	["security", "Is my information protected?"],
	["payment", "How do artists get paid?"],
	["protect", "How do we protect artists?"],
	["scam", "What if I get scammed?"],
	["color", "Why can I change the site's colors?"],
	["markdown", "How can I format text?"],
]) ?>
				</div>
				<div class="divider hide-on-med-and-up"></div>
				<div class="section" id="important-definitions">
					<h4 style="margin-top: -80px; padding-top: 80px;">Important definitions</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="getting-started">
					<h4 style="margin-top: -80px; padding-top: 80px;">How do I get started?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="rules">
					<h4 style="margin-top: -80px; padding-top: 80px;">What rules are in place?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="users">
					<h4 style="margin-top: -80px; padding-top: 80px;">Who can use Catalyst?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="cost">
					<h4 style="margin-top: -80px; padding-top: 80px;">What does it cost?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="difference">
					<h4 style="margin-top: -80px; padding-top: 80px;">What sets us apart?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="security">
					<h4 style="margin-top: -80px; padding-top: 80px;">Is my information protected?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="payment">
					<h4 style="margin-top: -80px; padding-top: 80px;">How do artists get paid?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="protect">
					<h4 style="margin-top: -80px; padding-top: 80px;">How do we protect artists?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="scam">
					<h4 style="margin-top: -80px; padding-top: 80px;">What if I get scammed?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="color">
					<h4 style="margin-top: -80px; padding-top: 80px;">Why can I change the site's colors?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="markdown">
					<h4 style="margin-top: -80px; padding-top: 80px;">How can I format text?</h4>
					
				</div>
			</div>
			<div class="col s12 m3 l2 hide-on-small-only">
<?= Values::createTOC([
	["glossary", "Important definitions"],
	["getting-started", "How do I get started?"],
	["rules", "What rules are in place?"],
	["users", "Who can use Catalyst?"],
	["cost", "What does it cost?"],
	["difference", "What sets us apart?"],
	["security", "Is my information protected?"],
	["payment", "How do artists get paid?"],
	["protect", "How do we protect artists?"],
	["scam", "What if I get scammed?"],
	["color", "Why can I change the site's colors?"],
	["markdown", "How can I format text?"],
]) ?>
			</div>
		</div>
<?php
require_once Values::FOOTER_INC;
