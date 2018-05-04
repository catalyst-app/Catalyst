<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, RawColumn, Tables};
use \Catalyst\Database\QueryAddition\{JoinClause, WhereClause};
use \Catalyst\Database\Query\{InsertQuery, MultiInsertQuery, SelectQuery};
use \Catalyst\Images\{Image, Folders};
use \Catalyst\Form\Field\MultipleImageWithNsfwCaptionAndInfoField;
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\Tokens;

Endpoint::init(true, 1);

FormRepository::getNewCommissionTypeForm()->checkServerSide();

if (!$_SESSION["user"]->isArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91540, ErrorCodes::ERR_91540);
}

$stmt = new InsertQuery();

$stmt->setTable(Tables::COMMISSION_TYPES);

$stmt->addColumn(new Column("ARTIST_PAGE_ID", Tables::COMMISSION_TYPES));
$stmt->addValue($_SESSION["user"]->getArtistPageId());

$stmt->addColumn(new Column("TOKEN", Tables::COMMISSION_TYPES));
$stmt->addValue($token = Tokens::generateCommissionTypeToken());

$stmt->addColumn(new Column("NAME", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["name"]);

$stmt->addColumn(new Column("BLURB", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["blurb"]);

$stmt->addColumn(new Column("DESCRIPTION", Tables::COMMISSION_TYPES));
$stmt->addValue($_POST["description"]);

$nextSortStmt = new SelectQuery();

$nextSortStmt->setTable(Tables::COMMISSION_TYPES);

$nextSortStmt->addColumn(new RawColumn('MAX(`SORT`) AS `NEXT_SORT`'));

$nextSortWhereClause = new WhereClause();
$nextSortWhereClause->addToClause([new Column("ARTIST_PAGE_ID", Tables::COMMISSION_TYPES), '=', $_SESSION["user"]->getArtistPageId()]);
$nextSortStmt->addAdditionalCapability($nextSortWhereClause);

$nextSortStmt->execute();

$nextSort = $nextSortStmt->getResult()[0]["NEXT_SORT"];

if (is_null($nextSort)) {
	$nextSort = 0;
} else {
	$nextSort++;
}

$stmt->addColumn(new Column("SORT", Tables::COMMISSION_TYPES));
$stmt->addValue($nextSort);

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

$stmt->execute();

$commissionTypeId = $stmt->getResult();

if (isset($_FILES["images"])) {
	$images = Image::uploadMultiple($_FILES["images"], Folders::COMMISSION_TYPE_IMAGE, $token);
	$imageMeta = MultipleImageWithNsfwCaptionAndInfoField::getExtraFields("images", $_POST);

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

if (array_key_exists("modifiers", $_POST) && count($_POST["modifiers"])) {
	$stmt = new MultiInsertQuery();

	$stmt->setTable(Tables::COMMISSION_TYPE_MODIFIERS);

	$stmt->addColumn(new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_MODIFIERS));
	$stmt->addColumn(new Column("NAME", Tables::COMMISSION_TYPE_MODIFIERS));
	$stmt->addColumn(new Column("PRICE", Tables::COMMISSION_TYPE_MODIFIERS));
	$stmt->addColumn(new Column("USDEQ", Tables::COMMISSION_TYPE_MODIFIERS));
	$stmt->addColumn(new Column("GROUP", Tables::COMMISSION_TYPE_MODIFIERS));
	$stmt->addColumn(new Column("MULTIPLE", Tables::COMMISSION_TYPE_MODIFIERS));

	foreach ($_POST["modifiers"] as $modifier) {
		$stmt->addValue($commissionTypeId);
		$stmt->addValue($modifier["modifier-psuedo-field"]);
		$stmt->addValue($modifier["base-cost-psuedo-field"]);
		$stmt->addValue($modifier["base-cost-usd-psuedo-field"]);
		$stmt->addValue($modifier["row"]);
		$stmt->addValue($modifier["multiple"] ? 1 : 0);
	}

	$stmt->execute();
}

if (array_key_exists("payments", $_POST) && count($_POST["payments"])) {
	$stmt = new MultiInsertQuery();

	$stmt->setTable(Tables::COMMISSION_TYPE_PAYMENT_OPTIONS);

	$stmt->addColumn(new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
	$stmt->addColumn(new Column("TYPE", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
	$stmt->addColumn(new Column("ADDRESS", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
	$stmt->addColumn(new Column("INSTRUCTIONS", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));

	foreach ($_POST["payments"] as $payment) {
		$stmt->addValue($commissionTypeId);
		$stmt->addValue($payment["type-psuedo-field"]);
		$stmt->addValue($payment["address-psuedo-field"]);
		$stmt->addValue($payment["instructions-psuedo-field"]);
	}

	$stmt->execute();
}

if (array_key_exists("stages", $_POST) && count($_POST["stages"])) {
	$stmt = new MultiInsertQuery();

	$stmt->setTable(Tables::COMMISSION_TYPE_STAGES);

	$stmt->addColumn(new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_STAGES));
	$stmt->addColumn(new Column("STAGE", Tables::COMMISSION_TYPE_STAGES));

	foreach ($_POST["stages"] as $stage) {
		$stmt->addValue($commissionTypeId);
		$stmt->addValue($stage["stage-psuedo-field"]);
	}

	$stmt->execute();
}

Response::sendSuccessResponse("Success");
