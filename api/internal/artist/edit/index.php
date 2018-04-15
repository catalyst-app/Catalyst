<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."includes/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\{JoinClause, WhereClause};
use \Catalyst\Database\Query\{DeleteQuery, SelectQuery, UpdateQuery};
use \Catalyst\Images\{Image, Folders};
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;

Endpoint::init(true, 1);

FormRepository::getEditArtistPageForm()->checkServerSide();

if (!$_SESSION["user"]->isArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91415, ErrorCodes::ERR_91415);
}

$artist = $_SESSION["user"]->getArtistPage();

$stmt = new UpdateQuery();
$stmt->setTable(Tables::ARTIST_PAGES);

$stmt->addColumn(new Column("NAME", Tables::ARTIST_PAGES));
$stmt->addValue($_POST["name"]);

$stmt->addColumn(new Column("URL", Tables::ARTIST_PAGES));
$stmt->addValue($_POST["url"]);

$stmt->addColumn(new Column("DESCRIPTION", Tables::ARTIST_PAGES));
$stmt->addValue($_POST["description"]);

if ($_POST["tos"] != $artist->getCurrentTosWithoutDate()) {
	$tos = $artist->getAllTos();

	array_unshift($tos, [date("l, F jS, Y"), $_POST["tos"]]);
	// so we dont sodomize the JSON, PHP likes to do that
	$tos = array_values($tos);
	
	$tos = json_encode($tos);

	$stmt->addColumn(new Column("TOS", Tables::ARTIST_PAGES));
	$stmt->addValue($tos);
}

if (isset($_FILES["image"])) {
	$newImage = Image::upload($_FILES["image"], Folders::ARTIST_IMAGE, $artist->getToken());
	if (!is_null($newImage)) {
		$image = $newImage->getPath();

		$stmt->addColumn(new Column("IMG", Tables::ARTIST_PAGES));
		$stmt->addValue($image);
	}
}

$stmt->addColumn(new Column("COLOR", Tables::ARTIST_PAGES));
$stmt->addValue(hex2bin($_POST["color"]));

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USER_ID", Tables::ARTIST_PAGES), '=', $_SESSION["user"]->getId()]);
$whereClause->addToClause(WhereClause::AND);
$whereClause->addToClause([new Column("ID", Tables::ARTIST_PAGES), '=', $_SESSION["user"]->getArtistPageId()]);

$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

Response::sendSuccessResponse("Success");
