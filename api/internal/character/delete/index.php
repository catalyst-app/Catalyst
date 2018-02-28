<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Character\Character;
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\{DeleteQuery, UpdateQuery};
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\Images\{Folders,Image};
use \Catalyst\Page\Values;

Endpoint::init(true, 1);

FormRepository::getDeleteCharacterForm()->checkServerSide();

if (!array_key_exists("token", $_POST)) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90901, ErrorCodes::ERR_90901);
}

$id = Character::getIdFromToken($_POST["token"]);
if ($id === -1) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90902, ErrorCodes::ERR_90902);
}

$character = new Character($id);
if ($character->getOwnerId() != $_SESSION["user"]->getId()) {
	HTTPCode::set(403);
	Response::sendErrorResponse(90903, ErrorCodes::ERR_90903);
}

$stmt = new UpdateQuery();

$stmt->setTable(Tables::CHARACTERS);

$stmt->addColumn(new Column("DELETED", Tables::CHARACTERS));
$stmt->addValue(1);

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::CHARACTERS), '=', $id]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

// delete images from disk
$images = $character->getImageSet();
foreach ($images as $image) {
	$image->delete();
}

$stmt = new DeleteQuery();

$stmt->setTable(Tables::CHARACTER_IMAGES);

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("CHARACTER_ID", Tables::CHARACTER_IMAGES), '=', $id]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

Response::sendSuccessResponse("Success");
