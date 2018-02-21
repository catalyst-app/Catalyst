<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, InsertQuery, MultiInsertQuery, RawColumn, SelectQuery, Tables, UpdateQuery, WhereClause};
use \Catalyst\Form\FormRepository;
use \Catalyst\{HTTPCode, Tokens};
use \Catalyst\Images\{Folders,Image};
use \Catalyst\Page\Values;

Endpoint::init(true, 1);

FormRepository::getNewCharacterForm()->checkServerSide();

$token = Tokens::generateCharacterToken();

$stmt = new InsertQuery();

$stmt->setTable(Tables::CHARACTERS);

$stmt->addColumn(new Column("USER_ID", Tables::CHARACTERS));
$stmt->addValue($_SESSION["user"]->getId());
$stmt->addColumn(new Column("CHARACTER_TOKEN", Tables::CHARACTERS));
$stmt->addValue($token);
$stmt->addColumn(new Column("NAME", Tables::CHARACTERS));
$stmt->addValue($_POST["name"]);
$stmt->addColumn(new Column("DESCRIPTION", Tables::CHARACTERS));
$stmt->addValue($_POST["description"]);
$stmt->addColumn(new Column("COLOR", Tables::CHARACTERS));
$stmt->addValue(hex2bin($_POST["color"]));
$stmt->addColumn(new Column("PUBLIC", Tables::CHARACTERS));
$stmt->addValue($_POST["public"] == "true");

$stmt->execute();

$characterId = $stmt->getResult();

if (isset($_FILES["images"])) {
	$images = Image::uploadMultiple($_FILES["images"], Folders::CHARACTER_IMAGE, $token);
	if (count($images)) {
		$stmt = new MultiInsertQuery();

		$stmt->setTable(Tables::CHARACTER_IMAGES);

		$stmt->addColumn(new Column("CHARACTER_ID", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("PATH", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("PRIMARY", Tables::CHARACTER_IMAGES));

		$primarySet = false;

		foreach ($images as $image) {
			$stmt->addValue($characterId);
			$stmt->addValue($image->getPath());
			$stmt->addValue($primarySet ? 0 : 1); // if already set, then 0
			$primarySet = true;
		}

		$stmt->execute();
	}
}

Response::sendSuccessResponse("Success", [
	"redirect" => "Character/".$token
]);
