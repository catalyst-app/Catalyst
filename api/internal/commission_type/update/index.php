<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Database\{Column, RawColumn, Tables};
use \Catalyst\Database\QueryAddition\{JoinClause, WhereClause};
use \Catalyst\Database\Query\{DeleteQuery, InsertQuery, MultiInsertQuery, SelectQuery, UpdateQuery};
use \Catalyst\Images\{Image, Folders};
use \Catalyst\Form\Field\MultipleImageWithNsfwCaptionAndInfoField;
use \Catalyst\Form\FormRepository;
use \Catalyst\{HTTPCode, Tokens};
use \LogicException;

Endpoint::init(true, 1);

FormRepository::getEditCommissionTypeForm()->checkServerSide();

if (!$_SESSION["user"]->isArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91740, ErrorCodes::ERR_91740);
}

$commissionTypeId = CommissionType::getIdFromToken($_POST["token"], false);

$pendingCommissionType = $commissionType = null;

if ($commissionTypeId !== -1) {
	$pendingCommissionType = new CommissionType($commissionTypeId);
	if ($pendingCommissionType->getArtistPageId() == $_SESSION["user"]->getArtistPageId()) {
		$commissionType = $pendingCommissionType;
	} else {
		HTTPCode::set(400);
		Response::sendErrorResponse(91746, ErrorCodes::ERR_91746);
	}
} else {
	HTTPCode::set(400);
	Response::sendErrorResponse(91746, ErrorCodes::ERR_91746);
}
// MAKE PHPSTAN HAPPY
if (is_null($commissionType)) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91746, ErrorCodes::ERR_91746);
	die();
}

$stmt = new UpdateQuery();

$stmt->setTable(Tables::COMMISSION_TYPES);

$stmt->addColumn(new Column("NAME", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["name"]);

$stmt->addColumn(new Column("BLURB", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["blurb"]);

$stmt->addColumn(new Column("DESCRIPTION", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["description"]);

$stmt->addColumn(new Column("BASE_COST", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["base-cost"]);

$stmt->addColumn(new Column("BASE_USD_COST", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["base-cost-usd"]);

$stmt->addColumn(new Column("ATTRS", Tables::COMMISSION_TYPES));
$stmt->addValue(implode(" ", $_POST["attributes"]));

$stmt->addColumn(new Column("ACCEPTING_QUOTES", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["accepting-quotes"] == "true");

$stmt->addColumn(new Column("ACCEPTING_REQUESTS", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["accepting-requests"] == "true");

$stmt->addColumn(new Column("ACCEPTING_TRADES", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["accepting-trades"] == "true");

$stmt->addColumn(new Column("ACCEPTING_COMMISSIONS", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["accepting-commissions"] == "true");

$stmt->addColumn(new Column("VISIBLE", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["visible"] == "true");

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::COMMISSION_TYPES), '=', $commissionTypeId]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

$existingImages = $commissionType->getImageSet();
$imageMeta = MultipleImageWithNsfwCaptionAndInfoField::getExtraFields("images", $_POST);

$toDelete = [];

foreach ($existingImages as $image) {
	if (!array_key_exists($image->getPath(), $imageMeta)) {
		$toDelete[] = $image->getPath();
		continue;
	}

	$stmt = new UpdateQuery();

	$stmt->setTable(Tables::COMMISSION_TYPE_IMAGES);

	$stmt->addColumn(new Column("CAPTION", Tables::COMMISSION_TYPE_IMAGES));
	$stmt->addValue($imageMeta[$image["PATH"]]["caption"]);
	$stmt->addColumn(new Column("CREDIT", Tables::COMMISSION_TYPE_IMAGES));
	$stmt->addValue($imageMeta[$image["PATH"]]["info"]);
	$stmt->addColumn(new Column("NSFW", Tables::COMMISSION_TYPE_IMAGES));
	$stmt->addValue($imageMeta[$image["PATH"]]["nsfw"] ? 1 : 0);
	$stmt->addColumn(new Column("SORT", Tables::COMMISSION_TYPE_IMAGES));
	$stmt->addValue($imageMeta[$image["PATH"]]["sort"]);

	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("PATH", Tables::COMMISSION_TYPE_IMAGES), '=', $image->getPath()]);
	$stmt->addAdditionalCapability($whereClause);

	$stmt->execute();
}

if (count($toDelete)) {
	$stmt = new DeleteQuery();

	$stmt->setTable(Tables::COMMISSION_TYPE_IMAGES);

	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("PATH", Tables::COMMISSION_TYPE_IMAGES), "IN", $toDelete]);
	$stmt->addAdditionalCapability($whereClause);

	$stmt->execute();
}

if (isset($_FILES["images"])) {
	$images = Image::uploadMultiple($_FILES["images"], Folders::COMMISSION_TYPE_IMAGE, $_POST["token"]);

	if (count($images)) {
		$stmt = new MultiInsertQuery();

		$stmt->setTable(Tables::COMMISSION_TYPE_IMAGES);

		$stmt->addColumn(new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("CAPTION", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("COMMISSIONER", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("PATH", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("NSFW", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("SORT", Tables::COMMISSION_TYPE_IMAGES));

		foreach ($images as $image) {
			$stmt->addValue($commissionTypeId);
			$stmt->addValue($imageMeta[$image->getUploadName()]["caption"]);
			$stmt->addValue($imageMeta[$image->getUploadName()]["info"]);
			$stmt->addValue($image->getPath());
			$stmt->addValue($imageMeta[$image->getUploadName()]["nsfw"] ? 1 : 0);
			$stmt->addValue($imageMeta[$image->getUploadName()]["sort"]);
		}

		$stmt->execute();
	}
}

$existingModifiers = [];

foreach ($commissionType->getModifiers() as $group) {
	foreach ($group->getModifiers() as $mod) {
		if (is_null($mod->getGroup())) {
			throw new LogicException("Improperly initialized modifier");
		}
		$key = serialize([
			$mod->getName(),
			$mod->getPrice(),
			$mod->getUsdEquivalent(),
			$mod->getGroup()->getId(),
			$mod->getGroup()->isAllowingMultiple(),
		]);
		$existingModifiers[$key] = $mod;
	}
}

if (array_key_exists("modifiers", $_POST) && count($_POST["modifiers"])) {
	$stmt = new MultiInsertQuery();

	$stmt->setTable(Tables::COMMISSION_TYPE_MODIFIERS);

	$stmt->addColumn(new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_MODIFIERS));
	$stmt->addColumn(new Column("NAME", Tables::COMMISSION_TYPE_MODIFIERS));
	$stmt->addColumn(new Column("PRICE", Tables::COMMISSION_TYPE_MODIFIERS));
	$stmt->addColumn(new Column("USDEQ", Tables::COMMISSION_TYPE_MODIFIERS));
	$stmt->addColumn(new Column("GROUP", Tables::COMMISSION_TYPE_MODIFIERS));
	$stmt->addColumn(new Column("MULTIPLE", Tables::COMMISSION_TYPE_MODIFIERS));

	$anyToAdd = false;

	foreach ($_POST["modifiers"] as $modifier) {
		$key = serialize([
			$modifier["modifier-psuedo-field"],
			$modifier["base-cost-psuedo-field"],
			$modifier["base-cost-usd-psuedo-field"],
			$modifier["row"],
			$modifier["multiple"] == true,
		]);
		if (array_key_exists($key, $existingModifiers)) {
			$existingModifiers[$key] = null;
			continue;
		}
		$anyToAdd = true;
		$stmt->addValue($commissionTypeId);
		$stmt->addValue($modifier["modifier-psuedo-field"]);
		$stmt->addValue($modifier["base-cost-psuedo-field"]);
		$stmt->addValue($modifier["base-cost-usd-psuedo-field"]);
		$stmt->addValue($modifier["row"]);
		$stmt->addValue($modifier["multiple"] ? 1 : 0);
	}

	if ($anyToAdd) {
		$stmt->execute();
	}
}

$existingModifiers = array_filter($existingModifiers);

if (count($existingModifiers)) {
	$toDelete = [];

	foreach ($existingModifiers as $mod) {
		$toDelete[] = $mod->getId()
	}

	$stmt = new DeleteQuery();

	$stmt->setTable(Tables::COMMISSION_TYPE_MODIFIERS);

	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("ID", Tables::COMMISSION_TYPE_MODIFIERS), "IN", $toDelete]);
	$stmt->addAdditionalCapability($whereClause);

	$stmt->execute();
}

