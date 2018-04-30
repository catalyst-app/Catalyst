<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Form\FormRepository;
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;


define("PAGE_KEYWORD", Values::ABOUT_US[0]);
define("PAGE_TITLE", Values::createTitle(Values::ABOUT_US[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
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
	["our-staff", "Our Staff"],
	["patreon", "Patrons"]
]) ?>
				</div>
				<div class="divider hide-on-med-and-up"></div>
				<div class="section" id="what-we-do">
					<h4>What We Do</h4>
					
					<p class="flow-text">Hello and welcome to Catalyst!</p>
					<p class="flow-text">We strive to simplify the often complex and difficult process of commissioning, for both the artist and customer!</p>
					<p class="flow-text">Our site allows for artists and clients to easily connect and find each other on our platform!</p>
					<p class="flow-text">We aim to be as inclusive of all forms of art - be it a picture, writing, sculpting or crafting - Catalyst has a place for artists of all kinds, genres, and backgrounds!</p>
					<p class="flow-text">Through easy listing and searching of artists' information, the customer can find exactly what they want and who they want to commission and how much it will cost, without having to wade through hundreds of feeds.</p>
					<p class="flow-text">Our system allows for an in-depth customization of your commission as well, keeping it simple yet feature-rich!  Some describe it like ordering a pizza with custom toppings: choose what you want, how you want it, and get an estimate of how much it'll be!</p>
					<p class="flow-text">Have you ever placed a commission and are wondering how far the artist is?  We have a system where artists can mark individual stages (sketching, coloring, etc.) as "Completed", as well as set deadlines, allowing you to check your commission's status without having to message them each time, letting the artist focus on their work without distraction!</p>
					<p class="flow-text">Another defining feature of Catalyst is the lack of most social-media aspects.  While communicating with the artist can be done, it lacks the usual galleries, journal and comment features.  Other sites are made for social media - so let them handle that, and let us focus on what we do best: simplifying commissions.</p>
					<p class="flow-text">There is a ton of additional, great features, some of them being:</p>
					<ul class="browser-default">
						<li class="flow-text">
							Character storages!
							<p class="no-margin">No more re-uploading ref-sheets, or having to send tons of refs!</p>
							<p class="no-top-margin">Had something drawn by an artist previously? You can upload it and comment on what might deviate from the official ref, on what you think has turned out great, or things you want other artists to focus on, too!</p>
						</li>
						<li class="flow-text">Commission tracking</li>
						<li class="flow-text">
							Wish-Lists!
							<p class="no-top-margin">Want to commission someone, but don't have the funds yet? - No worries! Put them on your wish-list so you'll remember later!</p>
						</li>
						<li class="flow-text">
							No Charges, fees or cuts of any kind!
							<p class="no-top-margin">Catalyst is entirely free to use by both the artist and customer!</p>
						</li>
						<li class="flow-text">
							User-powered
							<p class="flow-text no-top-margin">We are powered by our community!  We make it easy for members of the community to share ideas for improvements, new features, etc!  We value your input!</p>
						</li>
					</ul>
					<p class="flow-text">We love to hear all your ideas, so please, don't hesitate to share them with us!  You can find us at any of the social media platforms listed below!</p>
					<p>This text was originally written by Naahva on Telegram - huge thanks to him!</p>
				</div>
				<div class="divider"></div>
				<div class="section" id="social-media">
					<h4>Social Media</h4>
					<p class="flow-text">Italic networks are social groups which are used for direct user interaction and are preferred.</p>
					<div class="row">
						<div class="col s12 m6 hide-on-large-only">
							<a href="mailto:catalyst@catalystapp.co" class="black-text center col s12">
								<i class="large material-icons">mail</i>
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">catalyst@catalystapp.co</h5></span>
							</a>
							<a href="https://github.com/catalyst-app/Catalyst" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/gh_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">catalyst-app/Catalyst</h5></span>
							</a>
							<a href="https://twitter.com/catalystapp_co" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/tw_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">@catalystapp_co</h5></span>
							</a>
							<a href="https://discord.gg/EECUcnT" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/discord_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin"><i>discord.gg/EECUcnT</i></h5></span>
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
							<a href="https://steamcommunity.com/groups/catalystapp" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/sc_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">Catalystapp</h5></span>
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
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin"><i>@catalystapp</i></h5></span>
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
							<a href="https://reddit.com/r/catalystapp" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/reddit_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">/r/CatalystApp</h5></span>
							</a>
						</div>
						<div class="col l4 hide-on-med-and-down">
							<a href="mailto:catalyst@catalystapp.co" class="black-text center col s12">
								<i class="large material-icons">mail</i>
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">catalyst@catalystapp.co</h5></span>
							</a>
							<a href="https://github.com/catalyst-app/Catalyst" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/gh_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">catalyst-app/Catalyst</h5></span>
							</a>
							<a href="https://twitter.com/catalystapp_co" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/tw_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">@catalystapp_co</h5></span>
							</a>
							<a href="https://discord.gg/EECUcnT" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/discord_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin"><i>discord.gg/EECUcnT</i></h5></span>
							</a>
							<a href="https://catalystapp.deviantart.com/" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/da_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">catalystapp.deviantart.com</h5></span>
							</a>
							<a href="https://weasyl.com/~catalystapp/" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/w_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">weasyl.com/~catalystapp</h5></span>
							</a>
							<a href="https://steamcommunity.com/groups/catalystapp" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/sc_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">Catalystapp</h5></span>
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
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin"><i>@catalystapp</i></h5></span>
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
							<a href="https://reddit.com/r/catalystapp" class="black-text center col s12">
								<img src="<?= ROOTDIR ?>/img/reddit_logo.png" style="width: 6rem;">
								<span style="overflow-x: auto;white-space: nowrap;" class="col s12"><h5 class="no-top-margin">/r/CatalystApp</h5></span>
							</a>
						</div>
					</div>
					
					<h5 class="header">
						Email List
					</h5>

					<?= FormRepository::getEmailListAdditionForm()->getHtml() ?> 
				</div>
				<div class="divider"></div>
				<div class="section" id="our-staff">
					<h4>Our Staff</h4>
					
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

								<p class="flow-text">Image by blackmustang13 (<a href="http://www.furaffinity.net/user/blackmustang13/">Fur Affinity</a>)</p>

								<p class="flow-text"><strong>Roles: </strong> Owner, Lead Developer</p>
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		"NETWORK" => "SELF",
		"SERVICE_URL" => ROOTDIR."User/Fauxil",
		"DISP_NAME" => "Fauxil",
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
						<div class="col push-m8 s6 offset-s3 m4 center force-square-contents">
							<div class="img-strict-circle" style="background-image: url('<?= ROOTDIR ?>img/staff/lykai.png');"></div>
						</div>
						<div class="col s12 m7 pull-m4">
							<div class="col s12 right-align">
								<div class="center-on-small-only">
									<h3 class="header hide-on-small-only no-margin">Lykai</h3>

									<br class="hide-on-med-and-up">
									<h4 class="header hide-on-med-and-up no-margin">Lykai</h4>

									<p class="flow-text">A wonderful and sweet wolf that keeps everything going.  Studying computer science and machine learning!</p>

									<p class="flow-text">Image by blackmustang13 (<a href="https://www.furaffinity.net/view/25588593/">Fur Affinity</a>)</p>

									<p class="flow-text"><strong>Roles: </strong> Assistant, General Helper</p>
								</div>
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		"NETWORK" => "FURAFFINITY",
		"SERVICE_URL" => "http://www.furaffinity.net/user/lykai/",
		"DISP_NAME" => "~Lykai",
	],
	[
		"NETWORK" => "TELEGRAM",
		"SERVICE_URL" => "https://t.me/Lykai",
		"DISP_NAME" => "@Lykai",
	],
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "@Lykai#2495",
	],
	[
		"NETWORK" => "EMAIL",
		"SERVICE_URL" => "mailto:lykai@catalystapp.co",
		"DISP_NAME" => "lykai@catalystapp.co",
	],
])) ?>
							</div>
						</div>
					</div>
					<div class="divider"></div>

					<br>
					<div class="row">
						<div class="col s6 offset-s3 m4 center force-square-contents">
							<div class="img-strict-circle" style="background-image: url('<?= ROOTDIR ?>img/staff/disco.png');"></div>
						</div>
						<div class="col s12 m7 offset-m1">
							<div class="col s12 center-on-small-only">
								<h3 class="header hide-on-small-only no-margin">Disco Bob</h3>
								
								<br class="hide-on-med-and-up">
								<h4 class="header hide-on-med-and-up no-margin">Disco Bob</h4>

								<p class="flow-text">Disco Bob (Discombobulation) loves to help out others with whatever problems they encounter.  He studies Chemistry and English and loves all sorts of animals (primarily his dogs).  Disco Bob is always happy to get a message from someone who needs help or simply wants to talk.</p>

								<p class="flow-text">Image by Orlando Fox (<a href="https://twitter.com/Orlando_Fox/">Twitter</a>, <a href="http://afoxdraws.com/index.html">Website</a>)</p>

								<p class="flow-text"><strong>Roles: </strong> Social Media Manager (Patreon, Tumblr)</p>
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "@Discombobulation#5558",
	],
	[
		"NETWORK" => "EMAIL",
		"SERVICE_URL" => "mailto:blackoak@catalystapp.co",
		"DISP_NAME" => "blackoak@catalystapp.co",
	],
])) ?>
							</div>
						</div>
					</div>
					<div class="divider"></div>

					<br>

					<div class="row">
						<div class="col push-m8 s6 offset-s3 m4 center force-square-contents">
							<div class="img-strict-circle" style="background-image: url('<?= ROOTDIR ?>img/staff/foxxo.png');"></div>
						</div>
						<div class="col s12 m7 pull-m4">
							<div class="col s12 right-align">
								<div class="center-on-small-only">
									<h3 class="header hide-on-small-only no-margin">Foxxo</h3>
									
									<br class="hide-on-med-and-up">
									<h4 class="header hide-on-med-and-up no-margin">Foxxo</h4>

									<p class="flow-text">From the land of maple syrup, moose, and beavers.  AKA Canada.  Also is a blue fox thing.  Speaking of blue things, Cherry MX Blue™ key switches are great. (We are not endorsed or paid by Cherry)</p>

								<p class="flow-text">Image by wo7f (<a href="https://www.furaffinity.net/view/24726073/">Fur Affinity</a>)</p>

								<p class="flow-text"><strong>Roles: </strong> Social Media Manager (Twitter)</p>
								</div>
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
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
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "@Foxxo#7183",
	],
	[
		"NETWORK" => "EMAIL",
		"SERVICE_URL" => "mailto:foxxo@catalystapp.co",
		"DISP_NAME" => "foxxo@catalystapp.co",
	],
])) ?>
							</div>
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
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
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
		"SERVICE_URL" => "mailto:soul.wesson@catalystapp.co",
		"DISP_NAME" => "soul.wesson@catalystapp.co",
	],
])) ?>
							</div>
						</div>
					</div>

					<div class="divider"></div>

					<br>

					<div class="row">
						<div class="col push-m8 s6 offset-s3 m4 center force-square-contents">
							<div class="img-strict-circle" style="background-image: url('<?= ROOTDIR ?>img/staff/jiki.png');"></div>
						</div>
						<div class="col s12 m7 pull-m4">
							<div class="col s12 right-align">
								<div class="center-on-small-only">
									<h3 class="header hide-on-small-only no-margin">Jiki Scott</h3>
									
									<br class="hide-on-med-and-up">
									<h4 class="header hide-on-med-and-up no-margin">Jiki Scott</h4>

									<p class="flow-text">Jiki is a social media tycoonist who's studying marketing and the principles needed to make social platforms.</p>

									<p class="flow-text">Image by ShadowNinja976 (<a href="https://www.furaffinity.net/view/19702583/">Fur Affinity</a>)</p>

									<p class="flow-text"><strong>Roles: </strong> Social Media Manager (Fur Affinity, Facebook)</p>
								</div>
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		"NETWORK" => "TELEGRAM",
		"SERVICE_URL" => "https://t.me/JikiScott",
		"DISP_NAME" => "@JikiScott",
	],
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "Jiki Scott#7840",
	],
	[
		"NETWORK" => "EMAIL",
		"SERVICE_URL" => "mailto:jiki@catalystapp.co",
		"DISP_NAME" => "jiki@catalystapp.co",
	],
])) ?>
							</div>
						</div>
					</div>
				</div>
				<div class="divider"></div>
				<div class="section" id="patreon">
					<h4>Patrons</h4>

					<p class="flow-text"><a href="https://patreon.com/Catalyst">Get your name on this list!</a></p>					

					<p>Catalyst does not endorse/support any of the below names, and the patrons listed below do not officially represent Catalyst.</p>

					<h5 style="color: #b1560f;">Bronze Patrons ($10+):</h5>
					<ul class="browser-default">
						<li class="pink-text flow-text no-bottom-margin"><strong><s>SINNERSCOUT</s></strong></li>
						<p class="pink-text no-margin"><em><s>January 2018 to February, $10 highest pledged, total $13</s></em></p>
<?= /*SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		"NETWORK" => "FURAFFINITY",
		"SERVICE_URL" => "https://www.furaffinity.net/user/sinnerscout/",
		"DISP_NAME" => "~sinnerscout",
	],
	[
		"NETWORK" => "INSTAGRAM",
		"SERVICE_URL" => "https://www.instagram.com/sinnerscout/",
		"DISP_NAME" => "@sinnerscout",
	],
	[
		"NETWORK" => "TELEGRAM",
		"SERVICE_URL" => "https://telegram.dog/sinnerscout",
		"DISP_NAME" => "@sinnerscout",
	],
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "SINNERSCOUT#1276",
	],
]))*/"" ?>
					</ul>

					<h5 class="red-text">Patrons ($1+):</h5>
					<ul class="browser-default">
						<li class="red-text flow-text no-bottom-margin"><strong>Styx Y. Renegade</strong></li>
						<p class="red-text no-margin"><em>Since March 2018, $5 currently pledged, $5 lifetime</em></p>
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "@Styx Y. Renegade#5836",
	],
	[
		"NETWORK" => "FURAFFINITY",
		"SERVICE_URL" => "https://www.furaffinity.net/user/eonlover380/",
		"DISP_NAME" => "~EonLover380",
	],
	[
		"NETWORK" => "YOUTUBE",
		"SERVICE_URL" => "https://www.youtube.com/channel/UCXhmlLoK6clgNGrZ-02tsYg",
		"DISP_NAME" => "Styx Y. Renegade",
	],
	[
		"NETWORK" => "REDDIT",
		"SERVICE_URL" => "https://www.reddit.com/user/Styx_Renegade/",
		"DISP_NAME" => "/u/Styx_Renegade",
	],
])) ?>
						<li class="red-text flow-text no-bottom-margin indigo-text text-darken-1"><strong>AnalogHorse</strong></li>
						<p class="red-text no-margin indigo-text text-darken-1"><em>Since April 2018, $5 currently pledged</em></p>
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "@AnalogHorse#0621",
	],
])) ?>
						<li class="deep-purple-text flow-text no-bottom-margin"><strong>King Amdusias</strong></li>
						<p class="deep-purple-text no-margin"><em>Since February 2018, $1 currently pledged, lifetime $2</em></p>
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "King Amdusias#0276",
	],
	[
		"NETWORK" => "PATREON",
		"SERVICE_URL" => "https://www.patreon.com/KingAmdusias",
		"DISP_NAME" => "KingAmdusias",
	],
	[
		"NETWORK" => "INSTAGRAM",
		"SERVICE_URL" => "https://instagram.com/amdusias.png",
		"DISP_NAME" => "@amdusias.png",
	],
	[
		"NETWORK" => "TWITTER",
		"SERVICE_URL" => "https://twitter.com/kingamdusias",
		"DISP_NAME" => "@kingamdusias",
	],
])) ?>
						<li class="flow-text no-bottom-margin" style="color: #c09a69;"><strong>Lykai</strong></li>
						<p class="no-margin" style="color: #c09a69;"><em>Since March 2018, $1 currently pledged, $1 lifetime</em></p>
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		"NETWORK" => "FURAFFINITY",
		"SERVICE_URL" => "http://www.furaffinity.net/user/lykai/",
		"DISP_NAME" => "~Lykai",
	],
	[
		"NETWORK" => "TELEGRAM",
		"SERVICE_URL" => "https://t.me/Lykai",
		"DISP_NAME" => "@Lykai",
	],
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "@Lykai#2495",
	],
	[
		"NETWORK" => "EMAIL",
		"SERVICE_URL" => "mailto:lykai@catalystapp.co",
		"DISP_NAME" => "lykai@catalystapp.co",
	],
])) ?>
						<li class="flow-text no-bottom-margin teal-text text-darken-2"><strong>nyawenyye</strong></li>
						<p class="no-margin teal-text text-darken-2"><em>Since March 2018, $1 currently pledged, $1 lifetime</em></p>
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "@nyawenyye#8583",
	],
])) ?>
						<li class="flow-text no-bottom-margin" style="color: #36393e;"><strong>RD</strong></li>
						<p class="no-margin" style="color: #36393e;"><em>Since April 2018, $1 currently pledged</em></p>
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "@RD#8935",
	],
	[
		"NETWORK" => "EMAIL",
		"SERVICE_URL" => "mailto:readrdo@gmail.com",
		"DISP_NAME" => "@RD#8935",
	],
])) ?>
						<li class="yellow-text flow-text no-bottom-margin"><strong><s>Coyote-Lovely</s></strong></li>
						<p class="yellow-text no-margin"><s><em>February 2018</em></s></p>
<?= /*SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		"NETWORK" => "TWITTER",
		"SERVICE_URL" => "https://twitter.com/CoyoteLovelyDA/",
		"DISP_NAME" => "@CoyoteLovelyDA",
	],
	[
		"NETWORK" => "INSTAGRAM",
		"SERVICE_URL" => "https://www.instagram.com/CoyoteLovelyDA/",
		"DISP_NAME" => "@CoyoteLovelyDA",
	],
	[
		"NETWORK" => "DISCORD",
		"SERVICE_URL" => null,
		"DISP_NAME" => "Coyote-Lovely#2810",
	],
	[
		"NETWORK" => "AMINO",
		"SERVICE_URL" => "https://aminoapps.com/c/furry-amino/page/user/coyote-lovely/2vKW_21gi6fxgP1Z23WPn4ba2WbRYXJPeNW",
		"DISP_NAME" => "Coyote-Lovely"
	]
]))*/'' ?>
					</ul>
				</div>
			</div>
			<div class="col s12 m3 l2 hide-on-small-only">
<?= Values::createTOC([
	["what-we-do", "What We Do"],
	["social-media", "Social Media"],
	["our-staff", "Our Staff"],
	["patreon", "Patrons"]
]) ?>
			</div>
		</div>
<?php
require_once Values::FOOTER_INC;
