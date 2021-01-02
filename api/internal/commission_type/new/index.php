<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Database\{Column, RawColumn, Tables};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\SelectQuery;
use \Catalyst\Images\{Image, Folders};
use \Catalyst\Form\Field\MultipleImageWithNsfwCaptionAndInfoField;
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;
use \Catalyst\Tokens;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

FormRepository::getNewCommissionTypeForm()->checkServerSide();

if (!$_SESSION["user"]->isArtist()) {
	HTTPCode::set(400);
	Response::sendErrorResponse(91540, ErrorCodes::ERR_91540);
}

$nextSortStmt = new SelectQuery();

$nextSortStmt->setTable(Tables::COMMISSION_TYPES);

$nextSortStmt->addColumn(new RawColumn('MAX(`SORT`) AS `NEXT_SORT`'));

$nextSortWhereClause = new WhereClause();
$nextSortWhereClause->addToClause([new Column("ARTIST_PAGE_ID", Tables::COMMISSION_TYPES), '=', $_SESSION["user"]->getArtistPageId()]);
$nextSortStmt->addAdditionalCapability($nextSortWhereClause);

$nextSortStmt->execute();

if (is_null($nextSortStmt->getResult()[0]["NEXT_SORT"])) {
	$nextSort = 0;
} else {
	$nextSort = $nextSortStmt->getResult()[0]["NEXT_SORT"]+1;
}

$token = Tokens::generateCommissionTypeToken();

$images = $imageMeta = [];
if (isset($_FILES["images"])) {
	$images = Image::uploadMultiple($_FILES["images"], Folders::COMMISSION_TYPE_IMAGE, $token);
	$imageMeta = MultipleImageWithNsfwCaptionAndInfoField::getExtraFields("images", $_POST);
}

$modifiers = [];
if (array_key_exists("modifiers", $_POST) && count($_POST["modifiers"])) {
	$i=0;
	foreach ($_POST["modifiers"] as $modifier) {
		$modifiers[] = [
			"NAME" => $modifier["modifier-psuedo-field"],
			"PRICE" => $modifier["base-cost-psuedo-field"],
			"USDEQ" => $modifier["base-cost-usd-psuedo-field"],
			"GROUP" => $modifier["row"],
			"MULTIPLE" => $modifier["multiple"] ? 1 : 0,
			"SORT" => $i++,
		];
	}
}

$paymentOptions = [];
if (array_key_exists("payments", $_POST) && count($_POST["payments"])) {
	$i=0;
	foreach ($_POST["payments"] as $paymentOption) {
		$paymentOptions[] = [
			"TYPE" => $paymentOption["type-psuedo-field"],
			"ADDRESS" => $paymentOption["address-psuedo-field"],
			"INSTRUCTIONS" => $paymentOption["instructions-psuedo-field"],
			"SORT" => $i++,
		];
	}
}

$stages = [];
if (array_key_exists("stages", $_POST) && count($_POST["stages"])) {
	$i=0;
	foreach ($_POST["stages"] as $stage) {
		$stages[] = [
			"STAGE" => $stage["stage-psuedo-field"],
			"SORT" => $i++,
		];
	}
}

CommissionType::create([
	"ARTIST_PAGE_ID" => $_SESSION["user"]->getArtistPageId(),
	"TOKEN" => $token,
	"NAME" => $_POST["name"],
	"BLURB" => $_POST["blurb"],
	"DESCRIPTION" => $_POST["description"],
	"SORT" => $nextSort,
	"BASE_COST" => $_POST["base-cost"],
	"BASE_USD_COST" => $_POST["base-cost-usd"],
	"ATTRS" => implode(" ", $_POST["attributes"]),
	"ACCEPTING_QUOTES" => $_POST["accepting-quotes"] == "true",
	"ACCEPTING_REQUESTS" => $_POST["accepting-requests"] == "true",
	"ACCEPTING_TRADES" => $_POST["accepting-trades"] == "true",
	"ACCEPTING_COMMISSIONS" => $_POST["accepting-commissions"] == "true",
	"VISIBLE" => $_POST["visible"] == "true",
	"_images" => $images,
	"_image_meta" => $imageMeta,
	"_modifiers" => $modifiers,
	"_payment_options" => $paymentOptions,
	"_stages" => $stages,
]);

Response::sendSuccess("Success");
