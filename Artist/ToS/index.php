<?php

define("ROOTDIR", "../../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Artist\Artist;
use \Catalyst\HTTPCode;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

$artist = null;
if (!array_key_exists("q", $_GET)) {
	if (User::isLoggedIn()) {
		if ($_SESSION["user"]->isArtist()) {
			$artist = $_SESSION["user"]->getArtistPage();
			HTTPCode::set(302);
			header("Location: ".ROOTDIR."Artist/".$artist->getUrl());
			die("Redirecting...");
		} else {
			HTTPCode::set(400);
		}
	} else {
		HTTPCode::set(401);
	}
} else {
	$artistId = Artist::getIdFromUrl($_GET["q"]);
	if ($artistId !== -1) {
		$artist = new Artist($artistId);
	} else {
		HTTPCode::set(404);
	}
}

if (User::isLoggedIn() && !is_null($artist) && $_SESSION["user"]->getArtistPageId() == $artist->getId()) {
	define("PAGE_KEYWORD", "artist");
} else {
	define("PAGE_KEYWORD", Values::VIEW_ARTIST[0]);
}
define("PAGE_TITLE", Values::createTitle(Values::VIEW_ARTIST[1], ["name" => (!is_null($artist) ? $artist->getName() : "Invalid URL")]));

if (!is_null($artist)) {
	define("PAGE_COLOR", $artist->getColor());
} elseif (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Artist Terms of Service");
?>

<?php if (!is_null($artist)): ?>
			<div class="section">
<?php
$terms = $artist->getAllTos();
?>
				<div class="row">
					<div class="input-field col s12">
						<select id="terms-of-service-picker">
							<?php for ($i=0; $i < count($terms); $i++): ?>
								<option<?= $i != 0 ? '' : ' selected="selected"' ?> value="terms-<?= $i ?>"><?= htmlspecialchars($terms[$i][0]) ?></option>
							<?php endfor; ?>
						</select>
						<label>Choose a revision</label>
					</div>
				</div>
				<div class="terms-of-services">
					<?php for ($i=0; $i < count($terms); $i++): ?>
						<div class="col s12 artist-tos<?= $i != 0 ? ' hide' : '' ?>" id="terms-<?= $i ?>">
							<div class="raw-markdown tos-plain"><?= htmlspecialchars($terms[$i][1]) ?></div>
						</div>
					<?php endfor; ?>
				</div>
			</div>
<?php else: ?>
			<div class="section">
				<p class="flow-text">This artist does not exist!</p>
			</div>
<?php 
endif;

require_once Values::FOOTER_INC;
