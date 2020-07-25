<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\Endpoint;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_NONE);

header("Content-Type: image/svg+xml");

$errorSvg = <<<ERR_SVG
	<?xml version="1.0" standalone="no"?>
	<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 12">
		<style>
			text {
				fill: red;
				font-family: sans-serif;
			}
		</style>
		<text x="0" y="12">ERROR</text>
	</svg>
ERR_SVG;

if (!array_key_exists("badge", $_GET)) {
	die($errorSvg);
}

switch ($_GET["badge"]) {
	case "cpu":
		die(file_get_contents("http://catalystapp.co:19999/api/v1/badge.svg?chart=system.cpu"))
	default:
		die($errorSvg);
}
