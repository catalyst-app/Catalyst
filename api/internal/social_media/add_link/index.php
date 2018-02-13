<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, InsertQuery, RawColumn, SelectQuery, Tables, UpdateQuery, WhereClause};
use \Catalyst\Form\FormRepository;
use \Catalyst\{HTTPCode, Tokens};
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Page\Values;

Endpoint::init(true, 1);

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

if (strpos($_POST["url"], "javascript:")) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90711, ErrorCodes::ERR_90711);
}

if (preg_match('/^.{2,}@.{2,}\..{2,}$/', $_POST["url"])) {
	$finalUrl = 'mailto:'.$_POST["url"];
} else {
	// we have regex n stuff too don't worry!!
	if (filter_var($_POST["url"], FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED) === false) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90705, ErrorCodes::ERR_90705);
	}
	$parsed = parse_url($_POST["url"]);
	if ($parsed === false) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90705, ErrorCodes::ERR_90705);
	}

	// scheme check
	if (!array_key_exists("scheme", $parsed)) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90705, ErrorCodes::ERR_90705);
	}
	if ($parsed["scheme"] !== "http" && $parsed["scheme"] !== "https") {
		HTTPCode::set(400);
		Response::sendErrorResponse(90708, ErrorCodes::ERR_90708);
	}

	// inline auth (often a sign of phishing or malware)
	if (array_key_exists("user", $parsed) || array_key_exists("pass", $parsed)) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90709, ErrorCodes::ERR_90709);
	}

	// weird ports O_o
	if (array_key_exists("port", $parsed) && $parsed["port"] != 80 && $parsed["port"] != 443 && 
		$parsed["port"] != 8080 && $parsed["port"] != 8081) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90710, ErrorCodes::ERR_90710);
	}

	// and finally, host
	if (!array_key_exists("host", $parsed)) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90705, ErrorCodes::ERR_90705);
	}
	if (in_array(str_replace("www.", "", $parsed["host"]), Values::DISALLOWED_DOMAINS)) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90706, ErrorCodes::ERR_90706);
	}
	if (preg_match('/(?:\d{1,3}\.){3}\d{1,3}/', $parsed["host"])) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90707, ErrorCodes::ERR_90707);
	}

	$finalUrl = $_POST["url"];
}

if ($_POST["dest"] === "User") {
	$table = Tables::USER_SOCIAL_MEDIA;
} else {
	$table = Tables::ARTIST_SOCIAL_MEDIA;
}

$type = SocialMedia::getTypeFromUrl($finalUrl);

$stmt = new SelectQuery();

$stmt->setTable($table);

$stmt->addColumn(new RawColumn('MAX(`SORT`) AS `NEXT_SORT`'));

$whereClause = new WhereClause();

if ($_POST["dest"] === "User") {
	$whereClause->addToClause([new Column("USER_ID", $table), "=", $_SESSION["user"]->getId()]);
} else {
	$whereClause->addToClause([new Column("ARTIST_ID", $table), "=", $_SESSION["user"]->getArtistPageId()]);
}

$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

$nextSort = $stmt->getResult()[0]["NEXT_SORT"];

if (is_null($nextSort)) {
	$nextSort = 0;
} else {
	$nextSort++;
}

$stmt = new InsertQuery();

$stmt->setTable($table);

$stmt->addColumn(new Column("SORT", $table));
$stmt->addValue($nextSort);

if ($_POST["dest"] === "User") {
	$stmt->addColumn(new Column("USER_ID", $table));
	$stmt->addValue($_SESSION["user"]->getId());
} else {
	$stmt->addColumn(new Column("ARTIST_ID", $table));
	$stmt->addValue($_SESSION["user"]->getArtistPageId());
}
$stmt->addColumn(new Column("NETWORK", $table));
$stmt->addValue($type);
$stmt->addColumn(new Column("SERVICE_URL", $table));
$stmt->addValue($_POST["url"]);
$stmt->addColumn(new Column("DISP_NAME", $table));
$stmt->addValue($_POST["label"]);

$stmt->execute();

Response::sendSuccessResponse("Success", [
	"html" => SocialMedia::getChipHtml([
		[
			"id" => $stmt->getResult(),
			"src" => SocialMedia::getMeta()[$type]["path"],
			"label" => $_POST["label"],
			"href" => $_POST["url"],
			"classes" => SocialMedia::getMeta()[$type]["classes"],
			"tooltip" => SocialMedia::getMeta()[$type]["name"]
		]
	], true)
]);
