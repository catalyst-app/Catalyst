<?php
header("Content-Type: text/css; charset=UTF-8");

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Redacted\Color;
?>
a {
	color: #<?= $_GET["hex"] ?>;
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

.switch label .lever::before {
	background-color: rgba(<?= implode(",", Color::getRGB($_GET["hex"])) ?>, 0.15) !important;
}

.switch label input[type=checkbox]:checked + .lever::after {
	background-color: #<?= $_GET["hex"] ?>;
}

.user-color {
	background-color: #<?= isset(Color::COLOR_BY_CATEGORY[Color::HEX_MAP[$_GET["hex"]][0]]["darken-1"]) ? Color::COLOR_BY_CATEGORY[Color::HEX_MAP[$_GET["hex"]][0]]["darken-1"] : Color::COLOR_BY_CATEGORY[Color::HEX_MAP[$_GET["hex"]][0]][""] ?>;
}

.dropdown-content li:not(.disabled) > a, .dropdown-content li:not(.disabled) > span {
	color: #<?= $_GET["hex"] ?> !important;
}

.img-strict-circle[style*="/default.png');"], img[src$="/default.png"] {
	background-color: #<?= $_GET["hex"] ?>;;
}

[type="checkbox"]:checked + label:before {
  border-right: 2px solid #<?= $_GET["hex"] ?>;
  border-bottom: 2px solid #<?= $_GET["hex"] ?>;
}
[type="checkbox"]:indeterminate + label:before {
  border-right: 2px solid #<?= $_GET["hex"] ?>;
}

[type="checkbox"].filled-in:checked + label:after {
  border: 2px solid #<?= $_GET["hex"] ?>;
  background-color: #<?= $_GET["hex"] ?>;
}

[type="checkbox"].filled-in.tabbed:checked:focus + label:after {
  background-color: #<?= $_GET["hex"] ?>;
  border-color: #<?= $_GET["hex"] ?>;
}

input:not([type]):focus:not([readonly]),
input[type=text]:not(.browser-default):focus:not([readonly]),
input[type=password]:not(.browser-default):focus:not([readonly]),
input[type=email]:not(.browser-default):focus:not([readonly]),
input[type=url]:not(.browser-default):focus:not([readonly]),
input[type=time]:not(.browser-default):focus:not([readonly]),
input[type=date]:not(.browser-default):focus:not([readonly]),
input[type=datetime]:not(.browser-default):focus:not([readonly]),
input[type=datetime-local]:not(.browser-default):focus:not([readonly]),
input[type=tel]:not(.browser-default):focus:not([readonly]),
input[type=number]:not(.browser-default):focus:not([readonly]),
input[type=search]:not(.browser-default):focus:not([readonly]),
textarea.materialize-textarea:focus:not([readonly]) {
	border-bottom: 1px solid #<?= $_GET["hex"] ?>;
	-webkit-box-shadow: 0 1px 0 0 #<?= $_GET["hex"] ?>;
	box-shadow: 0 1px 0 0 #<?= $_GET["hex"] ?>;
}

input:not([type]):focus:not([readonly]) + label,
input[type=text]:not(.browser-default):focus:not([readonly]) + label,
input[type=password]:not(.browser-default):focus:not([readonly]) + label,
input[type=email]:not(.browser-default):focus:not([readonly]) + label,
input[type=url]:not(.browser-default):focus:not([readonly]) + label,
input[type=time]:not(.browser-default):focus:not([readonly]) + label,
input[type=date]:not(.browser-default):focus:not([readonly]) + label,
input[type=datetime]:not(.browser-default):focus:not([readonly]) + label,
input[type=datetime-local]:not(.browser-default):focus:not([readonly]) + label,
input[type=tel]:not(.browser-default):focus:not([readonly]) + label,
input[type=number]:not(.browser-default):focus:not([readonly]) + label,
input[type=search]:not(.browser-default):focus:not([readonly]) + label,
textarea.materialize-textarea:focus:not([readonly]) + label {
	color: #<?= $_GET["hex"] ?>;
}

.progress {
	background-color: #<?= Color::lightenHex($_GET["hex"], 2) ?>;
}

.progress .indeterminate {
	background-color: #<?= $_GET["hex"] ?>;
}

.btn, .btn-large {
	background-color: #<?= $_GET["hex"] ?>;
}

.btn:hover, .btn-large:hover {
	background-color: #<?= Color::lightenHexByPercent($_GET["hex"], 5) ?>;
}

blockquote {
	border-left: 5px solid #<?= $_GET["hex"] ?>;
}
