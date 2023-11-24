<?php
header("Content-Type: text/css; charset=UTF-8", true);
header("Cache-Control: max-age=259200", true); // 3 days

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../../");

define("NO_SESSION", true);

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\Color;
?>
:root {
	--main-color: #<?= $_GET["hex"] ?>;
	--main-color-quarter-alpha: #<?= $_GET["hex"] ?>44;

	--main-color-light-tinted:  #<?= Color::lightenHex($_GET["hex"], 5) ?>;
	--main-color-dark-tinted:  #<?= Color::lightenHex($_GET["hex"], 2) ?>;
}

.tinted-white-text {
	color: var(--main-color-light-tinted);
}

.dark-tinted-white-text {
	color: var(--main-color-dark-tinted);
}

.lighten-tenth {
	background-color: #<?= Color::lightenHex($_GET["hex"], 0.1) ?>;
}

.lighten-quarter {
	background-color: #<?= Color::lightenHex($_GET["hex"], 0.25) ?>;
}

.lighten-third {
	background-color: #<?= Color::lightenHex($_GET["hex"], 1/3) ?>;
}

.lighten-half {
	background-color: #<?= Color::lightenHex($_GET["hex"], 0.5) ?>;
}

.lighten-one {
	background-color: #<?= Color::lightenHex($_GET["hex"], 1) ?>;
}

.lighten-one-and-a-half {
	background-color: #<?= Color::lightenHex($_GET["hex"], 1.5) ?>;
}

.lighten-two {
	background-color: #<?= Color::lightenHex($_GET["hex"], 2) ?>;
}


.side-nav > li > a:hover, .side-nav > li > ul li:hover {
	background-color: #<?= Color::lightenHex($_GET["hex"], 0.5) ?>33;
}

.side-nav > li.active > a, .side-nav > li.active > ul {
	background-color: #<?= Color::lightenHex($_GET["hex"], 0.5) ?>55;
}
.switch label input[type=checkbox]:checked + .lever {
	background-color: #<?= Color::lightenHex($_GET["hex"], 1.5) ?>;
}

.switch label .lever:before {
	background-color: rgba(<?= implode(",", Color::getRGB($_GET["hex"])) ?>, 0.15);
}

input[type=checkbox]:checked:not(:disabled) ~ .lever:active::before,
input[type=checkbox]:checked:not(:disabled).tabbed:focus ~ .lever::before {
	background-color: rgba(<?= implode(",", Color::getRGB($_GET["hex"])) ?>, 0.15);
}

.progress {
	background-color: #<?= Color::lightenHex($_GET["hex"], 2) ?>;
}

.btn:hover, .btn:focus, .btn-floating:hover, .btn-large:hover, .btn-large:focus {
	background-color: #<?= Color::lightenHexByPercent($_GET["hex"], 5) ?>;
}
