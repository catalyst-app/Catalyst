<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, RawColumn, Tables};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\{InsertQuery, SelectQuery};
use \Catalyst\Form\FormRepository;
use \Catalyst\{HTTPCode, Tokens};
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Page\Values;

Endpoint::init(true, 1);

FormRepository::getAddNetworkOtherForm()->checkServerSide();

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

if ($_POST["dest"] === "User") {
	$table = Tables::USER_SOCIAL_MEDIA;
} else {
	$table = Tables::ARTIST_SOCIAL_MEDIA;
}

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
$stmt->addValue($_POST["type"]);
$stmt->addColumn(new Column("DISP_NAME", $table));
$stmt->addValue($_POST["label"]);

$stmt->execute();

Response::sendSuccessResponse("Success", [
	"html" => SocialMedia::getChipHtml([
		[
			"id" => $stmt->getResult(),
			"src" => SocialMedia::getMeta()[$_POST["type"]]["path"],
			"label" => $_POST["label"],
			"classes" => SocialMedia::getMeta()[$_POST["type"]]["classes"],
			"tooltip" => SocialMedia::getMeta()[$_POST["type"]]["name"]
		]
	], true)
]);
