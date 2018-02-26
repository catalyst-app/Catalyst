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

$imageMeta = MultipleImageWithNsfwCaptionAndInfoField::getExtraFields("images", $_POST);

$stmt = new SelectQuery();

$stmt->setTable(Tables::CHARACTER_IMAGES);

$stmt->addColumn(new Column("ID", Tables::CHARACTER_IMAGES));
$stmt->addColumn(new Column("PATH", Tables::CHARACTER_IMAGES));

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("CHARACTER_ID", Tables::CHARACTER_IMAGES), '=', $id]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

$existingImages = $stmt->getResult();

$toDelete = [];

foreach ($existingImages as $image) {
	if (!array_key_exists($image["PATH"], $imageMeta)) {
		$toDelete[] = $image["ID"];
		continue;
	}
	$stmt = new UpdateQuery();

	$stmt->setTable(Tables::CHARACTER_IMAGES);

	$stmt->addColumn(new Column("CAPTION", Tables::CHARACTER_IMAGES));
	$stmt->addValue($imageMeta[$image["PATH"]]["caption"]);
	$stmt->addColumn(new Column("CREDIT", Tables::CHARACTER_IMAGES));
	$stmt->addValue($imageMeta[$image["PATH"]]["info"]);
	$stmt->addColumn(new Column("NSFW", Tables::CHARACTER_IMAGES));
	$stmt->addValue($imageMeta[$image["PATH"]]["nsfw"] ? 1 : 0);
	$stmt->addColumn(new Column("PRIMARY", Tables::CHARACTER_IMAGES));
	$stmt->addValue($imageMeta[$image["PATH"]]["sort"] == 0);
	$stmt->addColumn(new Column("SORT", Tables::CHARACTER_IMAGES));
	$stmt->addValue($imageMeta[$image["PATH"]]["sort"]);

	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("ID", Tables::CHARACTER_IMAGES), '=', $image["ID"]]);
	$stmt->addAdditionalCapability($whereClause);

	$stmt->execute();
}

if (count($toDelete)) {
	$stmt = new DeleteQuery();

	$stmt->setTable(Tables::CHARACTER_IMAGES);

	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("ID", Tables::CHARACTER_IMAGES), "IN", $toDelete]);
	$stmt->addAdditionalCapability($whereClause);

	$stmt->execute();
}

if (isset($_FILES["images"])) {
	$images = Image::uploadMultiple($_FILES["images"], Folders::CHARACTER_IMAGE, $_POST["token"]);

	if (count($images)) {
		$stmt = new MultiInsertQuery();

		$stmt->setTable(Tables::CHARACTER_IMAGES);

		$stmt->addColumn(new Column("CHARACTER_ID", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("CAPTION", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("CREDIT", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("PATH", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("NSFW", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("PRIMARY", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("SORT", Tables::CHARACTER_IMAGES));

		$primaryHasBeenAdded = false;

		foreach ($images as $image) {
			$stmt->addValue($id);
			$stmt->addValue($imageMeta[$image->getUploadName()]["caption"]);
			$stmt->addValue($imageMeta[$image->getUploadName()]["info"]);
			$stmt->addValue($image->getPath());
			$stmt->addValue($imageMeta[$image->getUploadName()]["nsfw"] ? 1 : 0);
			$stmt->addValue($primaryHasBeenAdded ? 0 : 1); // if already set, then 0
			$stmt->addValue($imageMeta[$image->getUploadName()]["sort"]);
			$primaryHasBeenAdded = true;
		}

		$stmt->execute();
	}
}

Response::sendSuccessResponse("Success", [
	"redirect" => "Character/View/".$_POST["token"]
]);
