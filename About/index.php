<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Controller;
use \Catalyst\Form\FormRepository;
use \Catalyst\Images\{Folders, Image};
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
							Wish-lists!
							<p class="no-top-margin">Want to commission someone, but don't have the funds yet? - No worries! Put them on your wish-list so you'll remember later!</p>
						</li>
						<li class="flow-text">
							No charges, fees or cuts of any kind!
							<p class="no-top-margin">Catalyst is entirely free to use by both the artist and customer!</p>
						</li>
						<li class="flow-text">
							User-powered
							<p class="flow-text no-top-margin">We are powered by our community!  We make it easy for members of the community to share ideas for improvements, new features, etc!  We value your input!</p>
						</li>
					</ul>
					<p class="flow-text">There's even more than this in the plan, this is just the start!  Have any feedback or questions?  We'd love to hear all your ideas and answer any questions you may have, so please, don't hesitate to share them with us!  You can find us at any of the social media platforms listed below!</p>
					<p>This text was originally written by Naahva on Telegram - huge thanks to him!</p>
				</div>
				<div class="divider"></div>
				<div class="section" id="social-media">
					<h4>Social Media</h4>
					<p class="flow-text">Italic networks are social groups which are used for direct user interaction and are preferred.</p>
					<?php
					$networks = [
						[
							"href" => "mailto:catalyst@catalystapp.co",
							"primary" => false,
							"img" => "mail",
							"label" => "Email Us",
						],
						[
							"href" => "https://github.com/catalyst-app/Catalyst",
							"primary" => false,
							"img" => "github",
							"label" => "catalyst-app/Catalyst",
						],
						[
							"href" => "https://twitter.com/catalystapp_co",
							"primary" => false,
							"img" => "twitter",
							"label" => "@catalystapp_co",
						],
						[
							"href" => "https://discord.gg/EECUcnT",
							"primary" => true,
							"img" => "discord",
							"label" => "EECUcnT",
						],
						[
							"href" => "https://furaffinity.net/user/catalystapp",
							"primary" => false,
							"img" => "furaffinity",
							"label" => "~Catalystapp",
						],
						[
							"href" => "https://catalystapp.deviantart.com/",
							"primary" => false,
							"img" => "deviantart",
							"label" => "catalystapp",
						],
						[
							"href" => "https://weasyl.com/~catalystapp/",
							"primary" => false,
							"img" => "weasyl",
							"label" => "~catalystapp",
						],
						[
							"href" => "https://beta.furrynetwork.com/catalyst/",
							"primary" => false,
							"img" => "furrynetwork",
							"label" => "@catalyst",
						],
						[
							"href" => "https://reddit.com/user/catalystapp",
							"primary" => false,
							"img" => "reddit",
							"label" => "u/catalystapp",
						],
						[
							"href" => "https://steamcommunity.com/groups/catalystapp",
							"primary" => false,
							"img" => "steam",
							"label" => "Catalystapp",
						],
						[
							"href" => "https://instagram.com/catalyst.app",
							"primary" => false,
							"img" => "instagram",
							"label" => "@catalyst.app",
						],
						[
							"href" => "https://ko-fi.com/catalystapp",
							"primary" => false,
							"img" => "kofi",
							"label" => "catalystapp",
						],
						[
							"href" => "https://patreon.com/catalyst",
							"primary" => false,
							"img" => "patreon",
							"label" => "catalyst",
						],
						[
							"href" => "https://plus.google.com/102762464787584663279",
							"primary" => false,
							"img" => "googleplus",
							"label" => "+Catalyst",
						],
						[
							"href" => "https://catalystapp-co.tumblr.com/",
							"primary" => false,
							"img" => "tumblr",
							"label" => "catalystapp-co",
						],
						[
							"href" => "https://facebook.com/catalystapp.co",
							"primary" => false,
							"img" => "facebook",
							"label" => "/catalystapp.co",
						],
						[
							"href" => "https://reddit.com/r/catalystapp",
							"primary" => false,
							"img" => "reddit",
							"label" => "/r/CatalystApp",
						],
						[
							"href" => "https://t.me/catalystapp",
							"primary" => true,
							"img" => "telegram",
							"label" => "@catalystapp",
						],
						[
							"href" => "https://t.me/catalystapp_announcements",
							"primary" => false,
							"img" => "telegram",
							"label" => "@..._announcements",
						],
						[
							"href" => "https://trello.com/b/X37KEv4A/catalyst",
							"primary" => false,
							"img" => "trello",
							"label" => "Trello",
						],
					];

					$items = [];

					foreach ($networks as $network) {
						$str = '';

						$str .= '<a';
						$str .= ' href="'.htmlspecialchars($network["href"]).'"';
						$str .= ' class="black-text about-page-social-icon-block center col s12"';
						$str .= ' rel="noopener"';
						$str .= '>';

						$image = new Image(Folders::ABOUT_ICONS, "", $network["img"].'.png');

						$str .= '<div';
						$str .= ' class="about-page-social-icon-wrapper valign-wrapper"';
						$str .= '>';

						$str .= $image->getImgElementHtml(["about-page-social-icon"]);

						$str .= '</div>';

						$str .= '<span';
						$str .= ' class="col s12"';
						$str .= '>';

						$str .= '<h5>';

						if ($network["primary"]) {
							$str .= '<i>';
						}

						$str .= htmlspecialchars($network["label"]);

						if ($network["primary"]) {
							$str .= '</i>';
						}

						$str .= '</h5>';

						$str .= '</a>';

						$items[] = $str;
					}

					$bisected = [[],[]];
					$trisected = [[],[],[]];

					for ($i=0; $i < count($items); $i++) { 
						$bisected[$i%2][] = $items[$i];
						$trisected[$i%3][] = $items[$i];
					}
					?>
					<div class="row">
						<div class="col s12 hide-on-med-and-up">
							<?= implode("", $items) ?>
						</div>
						<div class="col s12 m6 hide-on-large-only hide-on-small-only">
							<?= implode("", $bisected[0]) ?>
						</div>
						<div class="col s12 m6 hide-on-large-only hide-on-small-only">
							<?= implode("", $bisected[1]) ?>
						</div>
						<div class="col l4 hide-on-med-and-down">
							<?= implode("", $trisected[0]) ?>
						</div>
						<div class="col l4 hide-on-med-and-down">
							<?= implode("", $trisected[1]) ?>
						</div>
						<div class="col l4 hide-on-med-and-down">
							<?= implode("", $trisected[2]) ?>
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
						<div class="force-square-contents col s6 offset-s3 m4 center">
							<?php
							$image = new Image(Folders::STAFF_ICONS, "", "fauxil.jpg");
							echo $image->getStrictCircleHtml();
							?>
						</div>
						<div class="col s12 m7 offset-m1">
							<div class="col s12 center-on-small-only">
								<h3 class="header hide-on-small-only no-margin">Fauxil Fox</h3>
								
								<br class="hide-on-med-and-up">
								<h4 class="header hide-on-med-and-up no-margin">Fauxil Fox</h4>

								<p class="flow-text">Fauxil is the founder of Catalyst!  He has developed most every aspect of the platform.  He oversees all operations and decisions as well.  He is studying Computer Science, and loves to code!  He especially loves backend web-developement or general programming, his primary language being PHP.</p>

								<p class="flow-text">Image by FawnButt (<a href="http://www.furaffinity.net/user/feve/">Fur Affinity</a>)</p>

								<p class="flow-text"><strong>Roles: </strong> Owner, Lead Developer</p>
								<div class="social-chips">
<?= SocialMedia::getChipHtml(SocialMedia::getChipArray([
	[
		'NETWORK' => 'SELF',
		'SERVICE_URL' => 'https://catalystapp.co/',
		'DISP_NAME' => 'I built this!',
	],
	[
		'NETWORK' => 'DOMAIN',
		'SERVICE_URL' => 'http://xn--fp8h58f.ws',
		'DISP_NAME' => 'Fauxkai Site!',
	],
	[
		'NETWORK' => 'EMAIL',
		'SERVICE_URL' => 'fauxil@catalystapp.co',
		'DISP_NAME' => '@catalystapp.co',
	],
	[
		'NETWORK' => 'DISCORD',
		'SERVICE_URL' => NULL,
		'DISP_NAME' => 'Fauxil_Fox#5881',
	],
	[
		'NETWORK' => 'EMAIL',
		'SERVICE_URL' => 'fauxil_fox@furmail.net',
		'DISP_NAME' => '@furmail.net',
	],
	[
		'NETWORK' => 'EMAIL',
		'SERVICE_URL' => 'hostmaster@catalystapp.co',
		'DISP_NAME' => 'hostmaster',
	],
	[
		'NETWORK' => 'EMAIL',
		'SERVICE_URL' => 'webmaster@catalystapp.co',
		'DISP_NAME' => 'webmaster',
	],
	[
		'NETWORK' => 'EMAIL',
		'SERVICE_URL' => 'postmaster@catalystapp.co',
		'DISP_NAME' => 'postmaster',
	],
	[
		'NETWORK' => 'FURAFFINITY',
		'SERVICE_URL' => 'https://furaffinity.net/user/fauxilfox',
		'DISP_NAME' => '~Fauxil_Fox',
	],
	[
		'NETWORK' => 'INSTAGRAM',
		'SERVICE_URL' => 'https://instagram.com/fauxil_fox',
		'DISP_NAME' => '@fauxil_fox',
	],
	[
		'NETWORK' => 'INSTAGRAM',
		'SERVICE_URL' => 'https://instagram.com/furry_irl',
		'DISP_NAME' => '@furry_irl meme bot',
	],
	[
		'NETWORK' => 'KOFI',
		'SERVICE_URL' => 'https://ko-fi.com/catalystapp',
		'DISP_NAME' => 'Ko-Fi',
	],
	[
		'NETWORK' => 'PATREON',
		'SERVICE_URL' => 'https://patreon.com/catalyst',
		'DISP_NAME' => 'Patreon',
	],
	[
		'NETWORK' => 'PAYPAL',
		'SERVICE_URL' => NULL,
		'DISP_NAME' => 'stglolin5@gmail.com',
	],
	[
		'NETWORK' => 'REDDIT',
		'SERVICE_URL' => 'https://reddit.com/u/fauxil_fox',
		'DISP_NAME' => 'Reddit',
	],
	[
		'NETWORK' => 'SOUNDCLOUD',
		'SERVICE_URL' => 'https://soundcloud.com/fauxil-fox/',
		'DISP_NAME' => 'sick jamzz',
	],
	[
		'NETWORK' => 'DEVIANTART',
		'SERVICE_URL' => 'https://fauxil-fox.deviantart.com/',
		'DISP_NAME' => 'DA',
	],
	[
		'NETWORK' => 'TELEGRAM',
		'SERVICE_URL' => 'https://telegram.dog/Fauxil_Fox',
		'DISP_NAME' => '@Fauxil_Fox',
	],
	[
		'NETWORK' => 'TWITTER',
		'SERVICE_URL' => 'https://twitter.com/fauxilfox',
		'DISP_NAME' => '@fauxilfox',
	],
])) ?>
								</div>
							</div>
						</div>
					</div>
					<div class="divider"></div>

					<br>

					<div class="row">
						<div class="force-square-contents col push-m8 s6 offset-s3 m4 center">
							<?php
							$image = new Image(Folders::STAFF_ICONS, "", "lykai.jpg");
							echo $image->getStrictCircleHtml();
							?>
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
								<div class="social-chips">
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
		"NETWORK" => "TWITTER",
		"SERVICE_URL" => "https://twitter.com/GoldDingus",
		"DISP_NAME" => "@GoldDingus",
	],
	[
		"NETWORK" => "SELF",
		"SERVICE_URL" => "https://beta.catalystapp.co/Character/View/Lykai",
		"DISP_NAME" => "Reference images",
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
					</div>
					<div class="divider"></div>

					<br>
					<div class="row">
						<div class="force-square-contents col s6 offset-s3 m4 center">
							<?php
							$image = new Image(Folders::STAFF_ICONS, "", "disco.jpg");
							echo $image->getStrictCircleHtml();
							?>
						</div>
						<div class="col s12 m7 offset-m1">
							<div class="col s12 center-on-small-only">
								<h3 class="header hide-on-small-only no-margin">Disco Bob</h3>
								
								<br class="hide-on-med-and-up">
								<h4 class="header hide-on-med-and-up no-margin">Disco Bob</h4>

								<p class="flow-text">Disco Bob (Discombobulation) loves to help out others with whatever problems they encounter.  He studies Chemistry and English and loves all sorts of animals (primarily his dogs).  Disco Bob is always happy to get a message from someone who needs help or simply wants to talk.</p>

								<p class="flow-text">Image by Orlando Fox (<a href="https://twitter.com/Orlando_Fox/">Twitter</a>, <a href="http://afoxdraws.com/index.html">Website</a>)</p>

								<p class="flow-text"><strong>Roles: </strong> Social Media Manager (Patreon, Tumblr)</p>
								<div class="social-chips">
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
					</div>
					<div class="divider"></div>

					<br>

					<div class="row">
						<div class="force-square-contents col push-m8 s6 offset-s3 m4 center">
							<?php
							$image = new Image(Folders::STAFF_ICONS, "", "foxxo.jpg");
							echo $image->getStrictCircleHtml();
							?>
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
								<div class="social-chips">
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
					</div>
					<div class="divider"></div>

					<br>

					<div class="row">
						<div class="force-square-contents col s6 offset-s3 m4 center">
							<?php
							$image = new Image(Folders::STAFF_ICONS, "", "soul.jpg");
							echo $image->getStrictCircleHtml();
							?>
						</div>
						<div class="col s12 m7 offset-m1">
							<div class="col s12 center-on-small-only">
								<h3 class="header hide-on-small-only no-margin">Soul Wesson</h3>
								
								<br class="hide-on-med-and-up">
								<h4 class="header hide-on-med-and-up no-margin">Soul Wesson</h4>

								<p class="flow-text">Soul has been an artist for almost a decade now, and loves growing her style with the help of the community. Always one to help others, she loves taking the time to help others via Twitter, Twitch, and several other communities and art sites. Great at diffusing tense situations, and always ready to lend a hand, Soul often offers her friendship first, and her services second. Her messages are always open for anyone who needs advice, or just someone to talk to.</p>

								<p class="flow-text">Image by Jasmae (<a href="https://www.furaffinity.net/view/22443950/">Fur Affinity</a>)</p>

								<p class="flow-text"><strong>Roles: </strong> PR manager</p>
								<div class="social-chips">
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
					</div>

					<div class="divider"></div>

					<br>

					<div class="row">
						<div class="force-square-contents col push-m8 s6 offset-s3 m4 center">
							<?php
							$image = new Image(Folders::STAFF_ICONS, "", "jiki.jpg");
							echo $image->getStrictCircleHtml();
							?>
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
								<div class="social-chips">
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
				</div>
				<div class="divider"></div>
				<div class="section" id="patreon">
					<h4>Patrons</h4>

					<p class="flow-text"><a href="https://patreon.com/Catalyst">Get your name on this list!</a></p>					

					<p>Catalyst does not endorse/support any of the below names, and the patrons listed below do not officially represent Catalyst.</p>

<?php file_exists(REAL_ROOTDIR."About/patreon.inc.php") ? require_once REAL_ROOTDIR."About/patreon.inc.php" : trigger_error("Patrons are not generated for About page", E_USER_NOTICE); ?>
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
