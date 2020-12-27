<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Artist\Artist;
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

FormRepository::getAddNetworkLinkForm()->checkServerSide();

if ($_POST["dest"] !== "User" && $_POST["dest"] !== "Artist") {
	HTTPCode::set(400);
	Response::sendErrorResponse(99903, ErrorCodes::ERR_99903);
}

if ($_POST["dest"] === "Artist") {
	if (!$_SESSION["user"]->isArtist()) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90701, ErrorCodes::ERR_90701);
	}
}

if (strpos($_POST["url"], "javascript:") === 0) {
	HTTPCode::set(400);
	Response::sendError("url", "javascriptScheme");
}

if (preg_match('/^.{1,}@.{1,}\..{1,}$/', $_POST["url"]) && strpos($_POST["url"], "://") === false) {
	$finalUrl = 'mailto:'.$_POST["url"];
} else {
	// we already verify regex
	$parsed = parse_url($_POST["url"]);
	if ($parsed === false) {
		HTTPCode::set(400);
		Response::sendError("url", "patternMismatch");
		throw new Exception("Unable to parse URL"); // mostly redundant to make phpstan happy
	}

	$parsed = array_merge([
		"scheme" => "",
		"user" => "",
		"pass" => "",
		"port" => "",
		"host" => "",
	], $parsed);

	// scheme check
	if (!array_key_exists("scheme", $parsed)) {
		HTTPCode::set(400);
		Response::sendError("url", "patternMismatch");
	}
	if ($parsed["scheme"] !== "http" && $parsed["scheme"] !== "https") {
		HTTPCode::set(400);
		Response::sendError("url", "invalidScheme");
	}

	// inline auth (often a sign of phishing or malware)
	if (strlen($parsed["user"]."") || strlen($parsed["pass"]."")) {
		HTTPCode::set(400);
		Response::sendError("url", "inlineAuthentication");
	}

	// weird ports O_o
	if (strlen($parsed["port"]) && $parsed["port"] != 80 && $parsed["port"] != 443 && 
		$parsed["port"] != 8080 && $parsed["port"] != 8081) {
		HTTPCode::set(400);
		Response::sendError("url", "invalidPort");
	}

	// and finally, host
	if (!array_key_exists("host", $parsed)) {
		HTTPCode::set(400);
		Response::sendError("url", "patternMismatch");
	}
	if (in_array(str_replace("www.", "", (string)$parsed["host"]), Values::DISALLOWED_DOMAINS)) {
		HTTPCode::set(400);
		Response::sendError("url", "disallowedDomain");
	}
	if (preg_match('/(?:\d{1,3}\.){3}\d{1,3}/', $parsed["host"])) {
		HTTPCode::set(400);
		Response::sendError("url", "ipAddress");
	}

	$finalUrl = $_POST["url"];
}

$type = SocialMedia::getTypeFromUrl($finalUrl);

if ($_POST["dest"] === "Artist") {
	$resource = $_SESSION["user"]->getArtistPage();
} else {
	$resource = $_SESSION["user"];
}

Response::sendSuccess("Success", [
	"html" => $resource->addSocialChip($type, $_POST["url"], $_POST["label"]),
]);
