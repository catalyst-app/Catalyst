<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Images\{Image, Folders};
use \Catalyst\Form\Field\MultipleImageWithNsfwCaptionAndInfoField;
use \Catalyst\Form\FormRepository;
use \Catalyst\{HTTPCode, Tokens};

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

FormRepository::getDeleteCommissionTypeForm()->checkServerSide();

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

$commissionType->delete();

Response::sendSuccessResponse("Success");
