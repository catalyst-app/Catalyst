<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR . "src/php/initializer.php";
use \Catalyst\Page\{Resource, Values};
use \Catalyst\User\{User};

define("PAGE_KEYWORD", Values::ABOUT_US[0]);
define("PAGE_TITLE", Values::createTitle(Values::ABOUT_US[1], []));
define("NO_HEADER", 1);

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;
?>
</div>
<div class="row lighten-half center align-center" id="about-definition">
	<h1 class="white-text no-margin">
		A message from Catalyst
	</h1>
</div>

<div class="container center align-center">
	<p class="small-margin flow-text" style="text-wrap: balance;">Unfortunately, this journey has come to an end, and we
		have to say goodbye. Thanks
		to everyone who was a part of this wonderful dream; we had a good run, and we would love to see someone else
		innovate and fill our shoes in the future!</p>

	<p class="small-margin flow-text">💚 Fauxil</p>
</div>

<div>
</div>
<footer class="page-footer" style="padding-top: 20px; padding-bottom: 20px;">
	<div class="container white-text">
		<p>
			Website &copy;2017-
			<?php echo date("Y"); ?> Catalyst, All rights reserved.
		</p>
		<p>
			Need to talk to us? Email me at <code style="border: 1px solid white">fauxil (at) catalystapp (dot) co</code>.
		</p>
	</div>
</footer>
<?php foreach (Resource::getScripts() as $script): ?>
	<?= $script->getTag() ?>
<?php endforeach; ?>
</body>

</html>
