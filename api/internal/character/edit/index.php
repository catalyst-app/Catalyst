<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Character\Character;
use \Catalyst\Database\{Column, DeleteQuery, InsertQuery, MultiInsertQuery, RawColumn, SelectQuery, Tables, UpdateQuery, WhereClause};
use \Catalyst\Form\Field\MultipleImageWithNsfwCaptionAndInfoField;
use \Catalyst\Form\FormRepository;
use \Catalyst\{HTTPCode, Tokens};
use \Catalyst\Images\{Folders,Image};
use \Catalyst\Page\Values;

Endpoint::init(true, 1);

FormRepository::getEditCharacterForm()->checkServerSide();

$id = Character::getIdFromToken($_POST["token"]);

if ($id == -1) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91012, ErrorCodes::ERR_91012);
}

$stmt = new SelectQuery();

$stmt->setTable(Tables::CHARACTERS);

$stmt->addColumn(new Column("USER_ID", Tables::CHARACTERS));
$stmt->addColumn(new Column("NAME", Tables::CHARACTERS));
$stmt->addColumn(new Column("DESCRIPTION", Tables::CHARACTERS));
$stmt->addColumn(new Column("COLOR", Tables::CHARACTERS));
$stmt->addColumn(new Column("PUBLIC", Tables::CHARACTERS));

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::CHARACTERS), '=', $id]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

$character = $stmt->getResult()[0];

if ($character["USER_ID"] != $_SESSION["user"]->getId()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91012, ErrorCodes::ERR_91012);
}

$stmt = new UpdateQuery();

$stmt->setTable(Tables::CHARACTERS);

if ($character["NAME"] != $_POST["name"]) {
	$stmt->addColumn(new Column("NAME", Tables::CHARACTERS));
	$stmt->addValue($_POST["name"]);
}
if ($character["DESCRIPTION"] != $_POST["description"]) {
	$stmt->addColumn(new Column("DESCRIPTION", Tables::CHARACTERS));
	$stmt->addValue($_POST["description"]);
}
if (bin2hex($character["COLOR"]) != $_POST["color"]) {
	$stmt->addColumn(new Column("COLOR", Tables::CHARACTERS));
	$stmt->addValue(hex2bin($_POST["color"]));
}
// must have at least one column, so lets do a simple bool
$stmt->addColumn(new Column("PUBLIC", Tables::CHARACTERS));
$stmt->addValue($_POST["public"] == "true");

$stmt->execute();

