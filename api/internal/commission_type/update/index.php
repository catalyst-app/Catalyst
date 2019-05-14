<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\CommissionType\{CommissionType, CommissionTypeModifier, CommissionTypePaymentOption, CommissionTypeStage};
use \Catalyst\Images\{DBImage, Folders, Image};
use \Catalyst\Form\Field\MultipleImageWithNsfwCaptionAndInfoField;
use \Catalyst\Form\FormRepository;
use \Catalyst\HTTPCode;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

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

$commissionType->setName($_POST["name"]);
$commissionType->setBlurb($_POST["blurb"]);
$commissionType->setDescription($_POST["description"]);
$commissionType->setBaseCost($_POST["base-cost"]);
$commissionType->setBaseUsdCost($_POST["base-cost-usd"]);
$commissionType->setAttributeString(implode(" ", $_POST["attributes"]));
$commissionType->setAcceptingQuotes($_POST["accepting-quotes"] == "true");
$commissionType->setAcceptingRequests($_POST["accepting-requests"] == "true");
$commissionType->setAcceptingTrades($_POST["accepting-trades"] == "true");
$commissionType->setAcceptingCommissions($_POST["accepting-commissions"] == "true");
$commissionType->setVisible($_POST["visible"] == "true");

$imageMeta = MultipleImageWithNsfwCaptionAndInfoField::getExtraFields("images", $_POST);

$existingImages = $commissionType->getImageSet();

foreach ($existingImages as $image) {
	if (!array_key_exists($image->getPath()."", $imageMeta)) {
		$image->delete();
		continue;
	}
	if (!$image instanceof DBImage) {
		continue;
	}
	$image->setNsfw(!!$imageMeta[$image->getPath()]["nsfw"]);
	$image->setCaption($imageMeta[$image->getPath()]["caption"]);
	$image->setInfo($imageMeta[$image->getPath()]["info"]);
	$image->setSort($imageMeta[$image->getPath()]["sort"]);
}

if (isset($_FILES["images"])) {
	$images = Image::uploadMultiple($_FILES["images"], Folders::COMMISSION_TYPE_IMAGE, $_POST["token"]);

	foreach ($images as $image) {
		if (is_null($image->getPath())) {
			continue;
		}
		$commissionType->addImage(
			$image->getPath(),
			!!$imageMeta[$image->getUploadName()]["nsfw"],
			$imageMeta[$image->getUploadName()]["caption"],
			$imageMeta[$image->getUploadName()]["info"],
			$imageMeta[$image->getUploadName()]["sort"]
		);
	}
}

// delete unless otherwise specified
$toDelete = $commissionType->getModifiers();

if (array_key_exists("modifiers", $_POST) && count($_POST["modifiers"])) {
	$sort = 0;
	foreach ($_POST["modifiers"] as $modifier) {
		for ($i=0; $i < count($toDelete); $i++) { 
			if ($toDelete[$i]->getName() == $modifier["modifier-psuedo-field"]) {
				$toDelete[$i]->setBaseCost($modifier["base-cost-psuedo-field"]);
				$toDelete[$i]->setBaseUsdCost($modifier["base-cost-usd-psuedo-field"]);
				$toDelete[$i]->setGroupId($modifier["row"]);
				$toDelete[$i]->setAllowingMultiple(!!$modifier["multiple"]);
				$toDelete[$i]->setSort($sort++);

				// remove from toDelete
				unset($toDelete[$i]);
				$toDelete = array_values($toDelete);

				continue 2;
			}
		}
		CommissionTypeModifier::create([
			"COMMISSION_TYPE_ID" => $commissionType->getId(),
			"NAME" => $modifier["modifier-psuedo-field"],
			"PRICE" => $modifier["base-cost-psuedo-field"],
			"USDEQ" => $modifier["base-cost-usd-psuedo-field"],
			"GROUP" => $modifier["row"],
			"MULTIPLE" => !!$modifier["multiple"],
			"SORT" => $sort++,
		]);
	}
}

foreach ($toDelete as $modifier) {
	$modifier->delete();
}

// delete unless otherwise specified
$toDelete = $commissionType->getPaymentOptions();

if (array_key_exists("payments", $_POST) && count($_POST["payments"])) {
	$sort = 0;
	foreach ($_POST["payments"] as $paymentOption) {
		for ($i=0; $i < count($toDelete); $i++) { 
			if ($toDelete[$i]->getType() == $paymentOption["type-psuedo-field"]) {
				$toDelete[$i]->setAddress($paymentOption["address-psuedo-field"]);
				$toDelete[$i]->setInstructions($paymentOption["instructions-psuedo-field"]);
				$toDelete[$i]->setSort($sort++);

				// remove from toDelete
				unset($toDelete[$i]);
				$toDelete = array_values($toDelete);

				continue 2;
			}
		}
		CommissionTypePaymentOption::create([
			"COMMISSION_TYPE_ID" => $commissionType->getId(),
			"TYPE" => $paymentOption["type-psuedo-field"],
			"ADDRESS" => $paymentOption["address-psuedo-field"],
			"INSTRUCTIONS" => $paymentOption["instructions-psuedo-field"],
			"SORT" => $sort++,
		]);
	}
}

foreach ($toDelete as $paymentOption) {
	$paymentOption->delete();
}

$toDelete = $commissionType->getStages();

if (array_key_exists("stages", $_POST) && count($_POST["stages"])) {
	$sort = 0;
	foreach ($_POST["stages"] as $stage) {
		for ($i=0; $i < count($toDelete); $i++) { 
			if ($toDelete[$i]->getStage() == $stage["stage-psuedo-field"]) {
				$toDelete[$i]->setSort($sort++);

				// remove from toDelete
				unset($toDelete[$i]);
				$toDelete = array_values($toDelete);

				continue 2;
			}
		}
		CommissionTypeStage::create([
			"COMMISSION_TYPE_ID" => $commissionType->getId(),
			"STAGE" => $stage["stage-psuedo-field"],
			"SORT" => $sort++,
		]);
	}
}

foreach ($toDelete as $stage) {
	$stage->delete();
}

Response::sendSuccessResponse("Success");
