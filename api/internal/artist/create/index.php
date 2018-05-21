<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\Query\{InsertQuery, SelectQuery, UpdateQuery};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\Images\{Image, Folders};
use \Catalyst\Tokens;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

FormRepository::getCreateArtistPageForm()->checkServerSide();

if ($_SESSION["user"]->isArtist() || $_SESSION["user"]->wasArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91215, ErrorCodes::ERR_91215);
}

$token = Tokens::generateArtistToken();

$image = null;
if (isset($_FILES["image"])) {
	$image = Image::upload($_FILES["image"], Folders::ARTIST_IMAGE, $token);
}

Artist::create([
	"USER_ID" => $_SESSION["user"]->getId(),
	"TOKEN" => $token,
	"NAME" => $_POST["name"],
	"URL" => $_POST["url"],
	"DESCRIPTION" => $_POST["description"],
	"TOS" => json_encode([
		[
			date("l, F jS, Y"),
			$_POST["tos"]
		],
	]),
	"IMG" => !is_null($image) ? $image : null,
	"COLOR" => hex2bin($_POST["color"]),
]);

Response::sendSuccessResponse("Success");
