<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\HTTPCode;
use \Catalyst\Images\Image;
use \Catalyst\User\User;

define("PAGE_KEYWORD", "Unimplemented");
define("PAGE_TITLE", "Unimplemented");

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Unimplemented");

?>
		<p class="flow-text center">We haven't gotten here yet, but we hope to soon!  We're <em>hard at work</em> to bring you the newest features!  Watch our <a href="https://trello.com/b/X37KEv4A/catalyst" target="_blank">trello</a> for updates.</p>
		<div class="row">
			<?php
			$images = Image::getUnimplementedImages();
			?>
			<div class="col s12 l4 offset-l1 m5">
				<?= $images[0]->getImgElementHtml(["col", "s12"]) ?>
			</div>
			<div class="hide-on-med-and-up small-margin col s12">
			</div>
			<div class="col s12 l4 offset-l3 m5 offset-m2">
				<?= $images[1]->getImgElementHtml(["col", "s12"]) ?>
			</div>
		</div>
<?php
require_once Values::FOOTER_INC;
