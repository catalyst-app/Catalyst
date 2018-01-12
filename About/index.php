<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Redacted\Page\UniversalFunctions;
use \Redacted\Page\Values;
use \Redacted\User\User;


define("PAGE_KEYWORD", Values::ABOUT_US[0]);
define("PAGE_TITLE", Values::createTitle(Values::ABOUT_US[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("About Us");
?>
			<div class="row"><div class="col s12 m9 l10">
				<div class="section hide-on-med-and-up">
<?= Values::createInlineTOC([
	["what-we-do", "What We Do"],
	["social-media", "Social Media"],
	["our-staff", "Out Staff"],
	["history", "History"],
]) ?>
				</div>
				<div class="divider hide-on-med-and-up"></div>
				<div class="section" id="what-we-do">
					<h4 style="margin-top: -80px; padding-top: 80px;">What We Do</h4>
					
					<p class="flow-text">We strive to simplify the complex process of commissioning for both the artist and the customer.  We allow for artists and clients to easily connect and find each other on our platform.  We do this through easy listing and searching of artist's information, allowing the customer to find <i>exactly</i> what they want and who they want to commission.  Next, we provide an easy-to-use system to track these commissions, simplifying and improving the whole process and allowing both sides to focus on what matters.</p>
					<p class="flow-text">Additionally, we offer other services such as character management, a messaging system, artist reviews, and much more.</p>
					<p class="flow-text">We stand apart from the competition for several reasons, primarily:</p>
					<ul class="browser-default">
						<li class="flow-text">Our easy to use and powerful search system - no more unpredictable tags</li>
						<li class="flow-text">A responsive, mobile-friendly design - built for the 21<sup>st</sup> century</li>
						<li class="flow-text"><strong>No fees or cuts</strong> - you control your own money</li>
						<li class="flow-text">And so much more!</li>
					</ul>
				</div>
				<div class="divider"></div>
				<div class="section" id="social-media">
					<h4 style="margin-top: -80px; padding-top: 80px;">Social Media</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="our-staff">
					<h4 style="margin-top: -80px; padding-top: 80px;">Our Staff</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="history">
					<h4 style="margin-top: -80px; padding-top: 80px;">History</h4>
					
				</div>
			</div>
			<div class="col s12 m3 l2 hide-on-small-only">
<?= Values::createTOC([
	["what-we-do", "What We Do"],
	["social-media", "Social Media"],
	["our-staff", "Out Staff"],
	["history", "History"],
]) ?>
			</div>
		</div>
<?php
require_once Values::FOOTER_INC;
 
 
