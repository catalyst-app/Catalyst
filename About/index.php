<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;
use \Catalyst\Integrations\SocialMedia;


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
]) ?>
				</div>
				<div class="divider hide-on-med-and-up"></div>
				<div class="section" id="what-we-do">
					<h4 style="margin-top: -80px; padding-top: 80px;">What We Do</h4>
					
					<p class="flow-text">We strive to simplify the complex process of commissioning for both the artist and the customer.  We allow for artists and clients to easily connect and find each other on our platform.  We do this through easy listing and searching of artist's information, allowing the customer to find <i>exactly</i> what they want and who they want to commission.  Next, we provide an easy-to-use system to track these commissions, simplifying and improving the whole process and allowing both sides to focus on what matters.</p>
					<p class="flow-text">Additionally, we offer other services such as character management, a messaging system, artist reviews, and much more.</p>
					<p class="flow-text">Our name, Catalyst, comes from its definition.  Merriam-Webster defines a catalyst as "an agent that provokes or speeds significant change or action," and that is what we do to commissions.  We facilitate the whole process, making it easier, faster, and more enjoyable.</p>
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
					<div class="row">
						<div class="col s12 m6 hide-on-large-only">
							<a href="mailto:catalyst@catalystapp.co" class="black-text center col s12">
								<i class="large material-icons">mail</i>
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">catalyst@catalystapp.co</h5></span>
							</a>
							<a href="https://github.com/smileytechguy/Catalyst" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/gh_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">smileytechguy/Catalyst</h5></span>
							</a>
							<a href="https://twitter.com/catalystapp_co" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/tw_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">@catalystapp_co</h5></span>
							</a>
							<a href="https://discord.gg/EECUcnT" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/discord_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">discord.gg/EECUcnT</h5></span>
							</a>
							<a href="https://furaffinity.net/user/catalystapp" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/fa_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">~Catalystapp</h5></span>
							</a>
							<a href="https://catalystapp.deviantart.com/" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/da_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">catalystapp.deviantart.com</h5></span>
							</a>
							<a href="https://weasyl.com/~catalystapp/" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/w_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">weasyl.com/~catalystapp</h5></span>
							</a>
							<a href="https://beta.furrynetwork.com/catalyst/" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/fn_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">@catalyst</h5></span>
							</a>
							<a href="https://reddit.com/user/catalystapp" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/reddit_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">/u/catalystapp</h5></span>
							</a>
						</div>
						<div class="col s12 m6 hide-on-large-only">
							<a href="https://instagram.com/catalyst.app" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/ig_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">@catalyst.app</h5></span>
							</a>
							<a href="https://ko-fi.com/catalystapp" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/ko-fi_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">ko-fi.com/catalystapp</h5></span>
							</a>
							<a href="https://patreon.com/catalyst" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/patreon_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">patreon.com/catalyst</h5></span>
							</a>
							<a href="https://t.me/catalystapp" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/telegram_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">@catalystapp</h5></span>
							</a>
							<a href="https://t.me/catalystapp_announcements" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/telegram_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">@catalystapp_announcements</h5></span>
							</a>
							<a href="https://plus.google.com/102762464787584663279" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/gp_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">+Catalyst</h5></span>
							</a>
							<a href="https://catalystapp-co.tumblr.com/" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/t_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">catalystapp-co.tumblr.com</h5></span>
							</a>
							<a href="https://facebook.com/catalystapp.co" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/fb_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">/catalystapp.co</h5></span>
							</a>
						</div>
						<div class="col l4 hide-on-med-and-down">
							<a href="mailto:catalyst@catalystapp.co" class="black-text center col s12">
								<i class="large material-icons">mail</i>
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">catalyst@catalystapp.co</h5></span>
							</a>
							<a href="https://github.com/smileytechguy/Catalyst" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/gh_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">smileytechguy/Catalyst</h5></span>
							</a>
							<a href="https://twitter.com/catalystapp_co" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/tw_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">@catalystapp_co</h5></span>
							</a>
							<a href="https://discord.gg/EECUcnT" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/discord_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">discord.gg/EECUcnT</h5></span>
							</a>
							<a href="https://catalystapp.deviantart.com/" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/da_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">catalystapp.deviantart.com</h5></span>
							</a>
							<a href="https://weasyl.com/~catalystapp/" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/w_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">weasyl.com/~catalystapp</h5></span>
							</a>
						</div>
						<div class="col l4 hide-on-med-and-down">
							<a href="http://furaffinity.net/user/catalystapp" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/fa_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">~Catalystapp</h5></span>
							</a>
							<a href="https://instagram.com/catalyst.app" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/ig_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">@catalyst.app</h5></span>
							</a>
							<a href="https://ko-fi.com/catalystapp" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/ko-fi_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">ko-fi.com/catalystapp</h5></span>
							</a>
							<a href="https://beta.furrynetwork.com/catalyst/" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/fn_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">@catalyst</h5></span>
							</a>
							<a href="https://plus.google.com/102762464787584663279" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/gp_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">+Catalyst</h5></span>
							</a>
							<a href="https://reddit.com/user/catalystapp" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/reddit_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">/u/catalystapp</h5></span>
							</a>
						</div>
						<div class="col l4 hide-on-med-and-down">
							<a href="https://patreon.com/catalyst" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/patreon_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">patreon.com/catalyst</h5></span>
							</a>
							<a href="https://t.me/catalystapp" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/telegram_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">@catalystapp</h5></span>
							</a>
							<a href="https://t.me/catalystapp_announcements" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/telegram_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">@catalystapp_announcements</h5></span>
							</a>
							<a href="https://catalystapp-co.tumblr.com/" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/t_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">catalystapp-co.tumblr.com</h5></span>
							</a>
							<a href="https://facebook.com/catalystapp.co" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/fb_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">/catalystapp.co</h5></span>
							</a>
						</div>
					</div>
				</div>
				<div class="divider"></div>
				<div class="section" id="our-staff">
					<h4 style="margin-top: -80px; padding-top: 80px;">Our Staff</h4>
					
					<div class="row">
						<div class="col s6 offset-s3 m4 center force-square-contents">
							<div class="img-strict-circle" style="background-image: url('<?= ROOTDIR ?>img/staff/fauxil.png');"></div>
						</div>
						<div class="col s12 m7 offset-m1">
							<div class="col s12 center-on-small-only">
								<h3 class="header hide-on-small-only no-margin">Fauxil Fox</h3>
								
								<br class="hide-on-med-and-up">
								<h4 class="header hide-on-med-and-up no-margin">Fauxil Fox</h4>

								<p class="flow-text">Fauxil is the founder of Catalyst!  He has developed most every aspect of the platform.  He oversees all operations and decisions as well.  He is studying Computer Science, and loves to code!  He especially loves backend web-developement or general programming, his primary language being PHP.</p>

								<p class="flow-text">Image by Echo-80/hamburgerfox (<a href="https://www.furaffinity.net/view/25777547/">Fur Affinity</a>, <a href="https://echo-80.deviantart.com/art/C-Fauxil-2-2-720595610">DeviantArt</a>, and <a href="https://www.instagram.com/p/Bc0gkXlFlV5/?taken-by=hamburgerfox">Instagram</a>)</p>

								<p class="flow-text"><strong>Roles: </strong> Owner, Lead Developer</p>
<?= SocialMedia::getChipHTML(SocialMedia::getChipArray([
	[
		"NETWORK" => "SELF",
		"SERVICE_URL" => ROOTDIR."User/Fauxil",
		"DISP_NAME" => "Profile",
	],
	[
		"NETWORK" => "TWITTER",
		"SERVICE_URL" => "https://twitter.com/Fauxil_Fox",
		"DISP_NAME" => "@Fauxil_Fox",
	],
	[
		"NETWORK" => "TELEGRAM",
		"SERVICE_URL" => "https://t.me/Fauxil_Fox",
		"DISP_NAME" => "@Fauxil_Fox",
	],
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "@Fauxil_Fox#5881",
	],
	[
		"NETWORK" => "FURAFFINITY",
		"SERVICE_URL" => "http://furaffinity.net/user/fauxilfox/",
		"DISP_NAME" => "~Fauxil_Fox",
	],
	[
		"NETWORK" => "EMAIL",
		"SERVICE_URL" => "mailto:fauxil@catalystapp.co",
		"DISP_NAME" => "fauxil@catalystapp.co",
	],
])) ?>
							</div>
						</div>
					</div>
					<div class="divider"></div>

					<br>

					<div class="row">
						<div class="col s12 m7">
							<div class="col s12 center-on-small-only right right-align">
								<h3 class="header hide-on-small-only no-margin">Foxxo</h3>
								
								<br class="hide-on-med-and-up">
								<h4 class="header hide-on-med-and-up no-margin">Foxxo</h4>

								<p class="flow-text">From the land of maple syrup, moose, and beavers.  AKA Canada.  Also is a blue fox thing.  Speaking of blue things, Cherry MX Blue™ key switches are great. (We are not endoresed or paid by Cherry)</p>

								<p>Image by wo7f (<a href="https://www.furaffinity.net/view/24726073/">Fur Affinity</a>)</p>

								<p class="flow-text"><strong>Roles: </strong> Social Media Manager (Twitter)</p>
<?= SocialMedia::getChipHTML(SocialMedia::getChipArray([
	[
		"NETWORK" => "TWITTER",
		"SERVICE_URL" => "https://twitter.com/fluffracing",
		"DISP_NAME" => "@fluffracing",
	],
	[
		"NETWORK" => "FURAFFINITY",
		"SERVICE_URL" => "http://furaffinity.net/user/foxxoracing",
		"DISP_NAME" => "~foxxoracing",
	],
	[
		"NETWORK" => "EMAIL",
		"SERVICE_URL" => "mailto:foxxo@catalystapp.co",
		"DISP_NAME" => "foxxo@catalystapp.co",
	],
])) ?>
							</div>
						</div>
						<div class="col s6 offset-s3 m4 offset-m1 center force-square-contents">
							<div class="img-strict-circle" style="background-image: url('<?= ROOTDIR ?>img/staff/foxxo.png');"></div>
						</div>
					</div>
					<div class="divider"></div>

					<br>
					<div class="row">
						<div class="col s6 offset-s3 m4 center force-square-contents">
							<div class="img-strict-circle" style="background-image: url('<?= ROOTDIR ?>img/staff/soul.png');"></div>
						</div>
						<div class="col s12 m7 offset-m1">
							<div class="col s12 center-on-small-only">
								<h3 class="header hide-on-small-only no-margin">Soul Wesson</h3>
								
								<br class="hide-on-med-and-up">
								<h4 class="header hide-on-med-and-up no-margin">Soul Wesson</h4>

								<p class="flow-text">Soul has been an artist for almost a decade now, and loves growing her style with the help of the community. Always one to help others, she loves taking the time to help others via Twitter, Twitch, and several other communities and art sites. Great at diffusing tense situations, and always ready to lend a hand, Soul often offers her friendship first, and her services second. Her messages are always open for anyone who needs advice, or just someone to talk to.</p>

								<p class="flow-text">Image by Jasmae (<a href="https://www.furaffinity.net/view/22443950/">Fur Affinity</a>)</p>

								<p class="flow-text"><strong>Roles: </strong> PR manager</p>
<?= SocialMedia::getChipHTML(SocialMedia::getChipArray([
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "@Soul Wesson#7693",
	],
	[
		"NETWORK" => "TWITTER",
		"SERVICE_URL" => "https://twitter.com/SoulWesson/",
		"DISP_NAME" => "@SoulWesson",
	],
	[
		"NETWORK" => "FURAFFINITY",
		"SERVICE_URL" => "http://www.furaffinity.net/user/soulcommissions/",
		"DISP_NAME" => "~SoulCommissions",
	],
	[
		"NETWORK" => "TWITCH",
		"SERVICE_URL" => "https://twitch.tv/soulwesson/",
		"DISP_NAME" => "SoulWesson",
	],
	[
		"NETWORK" => "EMAIL",
		"SERVICE_URL" => "mailto:soul@catalystapp.co",
		"DISP_NAME" => "soul@catalystapp.co",
	],
])) ?>
							</div>
						</div>
					</div>
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
]) ?>
			</div>
		</div>
<?php
require_once Values::FOOTER_INC;
 
 
