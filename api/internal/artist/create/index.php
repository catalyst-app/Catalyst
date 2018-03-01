<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\Query\{InsertQuery, UpdateQuery};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\Images\{Image, Folders};
use \Catalyst\Tokens;

Endpoint::init(true, 1);

FormRepository::getCreateArtistPageForm()->checkServerSide();

if ($_SESSION["user"]->isArtist() || $_SESSION["user"]->wasArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91215, ErrorCodes::ERR_91215);
}

$stmt = new SelectQuery();

$stmt->setTable(Tables::ARTIST_PAGES);

$stmt->addColumn(new Column("ID", Tables::ARTIST_PAGES))

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("URL", Tables::ARTIST_PAGES), '=', $_POST["url"]]);

$stmt->execute();

if (count($stmt->getResult())) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91205, ErrorCodes::ERR_91205);
}

$stmt = new InsertQuery();

$stmt->setTable(Tables::ARTIST_PAGES);

$stmt->addColumn(new Column("USER_ID", Tables::ARTIST_PAGES));
$stmt->addValue($_SESSION["user"]->getId());

$token = Tokens::generateArtistToken();

$stmt->addColumn(new Column("TOKEN", Tables::ARTIST_PAGES));
$stmt->addValue($token);

$stmt->addColumn(new Column("NAME", Tables::ARTIST_PAGES));
$stmt->addValue($_POST["name"]);

$stmt->addColumn(new Column("URL", Tables::ARTIST_PAGES));
$stmt->addValue($_POST["url"]);

$stmt->addColumn(new Column("DESCRIPTION", Tables::ARTIST_PAGES));
$stmt->addValue($_POST["description"]);

$tos = "*Effective as of ".date("l, F jS, Y")."*"."\n".$_POST["tos"];
$tos = json_encode([$tos]);

$stmt->addColumn(new Column("TOS", Tables::ARTIST_PAGES));
$stmt->addValue($tos);

$image = null;
if (isset($_FILES["image"])) {
	$newImage = Image::upload($_FILES["image"], Folders::ARTIST_IMAGE, $token);
	if (!is_null($newImage)) {
		$image = $newImage->getPath();
	}
}
$stmt->addColumn(new Column("IMG", Tables::ARTIST_PAGES));
$stmt->addValue($image);

$stmt->addColumn(new Column("COLOR", Tables::ARTIST_PAGES));
$stmt->addValue(hex2bin($_POST["color"]));

$stmt->execute();

$aid = $stmt->getResult();

$stmt = new UpdateQuery();

$stmt->setTable(Tables::USERS);

$stmt->addColumn(new Column("ARTIST_PAGE_ID", Tables::USERS));
$stmt->addValue($aid);

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::USERS), '=', $_SESSION["user"]->getId()]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

Response::sendSuccessResponse("Success");
