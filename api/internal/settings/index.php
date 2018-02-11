<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, SelectQuery, Tables, UpdateQuery, WhereClause};
use \Catalyst\{Email, HTTPCode, Tokens};
use \Catalyst\Form\FormRepository;
use \Catalyst\Images\{Image,Folders};
use \Catalyst\Page\Values;
use \Catalyst\User\{TOTP,User};

Endpoint::init(true, 1);

FormRepository::getSettingsForm()->checkServerSide();

$id = $_SESSION["user"]->getId();


// check username is free/own
$query = new SelectQuery();
$query->setTable(Tables::USERS);
$query->addColumn(new Column("ID", Tables::USERS));
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USERNAME", Tables::USERS), "=", $_POST["username"]]);
$whereClause->addToClause(WhereClause::AND);
$whereClause->addToClause([new Column("ID", Tables::USERS), "!=", $id]);
$query->addAdditionalCapability($whereClause);
$query->execute();

if (count($query->getResult()) != 0) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90503, ErrorCodes::ERR_90503);
}

// check email is free/own
if (!empty($_POST["email"])) {
	$query = new SelectQuery();
	$query->setTable(Tables::USERS);
	$query->addColumn(new Column("ID", Tables::USERS));
	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("EMAIL", Tables::USERS), "=", $_POST["email"]]);
	$whereClause->addToClause(WhereClause::AND);
	$whereClause->addToClause([new Column("ID", Tables::USERS), "!=", $id]);
	$query->addAdditionalCapability($whereClause);
	$query->execute();
	if (count($query->getResult()) != 0) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90503, ErrorCodes::ERR_90503);
	}
	if (strpos($_POST["email"], "@catalystapp.co") !== false) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90523, ErrorCodes::ERR_90523);
	}
}

$query = new SelectQuery();
$query->setTable(Tables::USERS);
$query->addColumn(new Column("FILE_TOKEN", Tables::USERS));
$query->addColumn(new Column("USERNAME", Tables::USERS));
$query->addColumn(new Column("HASHED_PASSWORD", Tables::USERS));
$query->addColumn(new Column("PASSWORD_RESET_TOKEN", Tables::USERS));
$query->addColumn(new Column("TOTP_KEY", Tables::USERS));
$query->addColumn(new Column("TOTP_RESET_TOKEN", Tables::USERS));
$query->addColumn(new Column("EMAIL", Tables::USERS));
$query->addColumn(new Column("EMAIL_VERIFIED", Tables::USERS));
$query->addColumn(new Column("EMAIL_TOKEN", Tables::USERS));
$query->addColumn(new Column("PICTURE_LOC", Tables::USERS));
$query->addColumn(new Column("PICTURE_NSFW", Tables::USERS));
$query->addColumn(new Column("NSFW", Tables::USERS));
$query->addColumn(new Column("COLOR", Tables::USERS));
$query->addColumn(new Column("NICK", Tables::USERS));
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::USERS), "=", $id]);
$query->addAdditionalCapability($whereClause);
$query->execute();

$user = $query->getResult()[0];

if (!password_verify($_POST["password"], $user["HASHED_PASSWORD"])) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90522, ErrorCodes::ERR_90522);
}


$query = new UpdateQuery();
$query->setTable(Tables::USERS);
if ($_POST["username"] != $user["USERNAME"]) {
	$query->addColumn(new Column("USERNAME", Tables::USERS));
	$query->addValue($_POST["username"]);
}

if (!empty($_POST["new-password"])) {
	$query->addColumn(new Column("HASHED_PASSWORD", Tables::USERS));
	$query->addValue(password_hash($_POST["new-password"], PASSWORD_BCRYPT, ["cost" => Values::BCRYPT_COST]));
	
	$query->addColumn(new Column("PASSWORD_RESET_TOKEN", Tables::USERS));
	$query->addValue(Tokens::generatePasswordResetToken());
}

$redirectToTotp = false;
if (is_null($user["TOTP_KEY"]) != ($_POST["two-factor"] == "false")) {
	if ($_POST["two-factor"] == "true") {
		$redirectToTotp = true;

		$query->addColumn(new Column("TOTP_KEY", Tables::USERS));
		$query->addValue(TOTP::generateKey());
	} else {
		$query->addColumn(new Column("TOTP_KEY", Tables::USERS));
		$query->addValue(null);
	}
}
if (is_null($user["TOTP_RESET_TOKEN"])) {
	$query->addColumn(new Column("TOTP_RESET_TOKEN", Tables::USERS));
	$query->addValue(Tokens::generateTotpResetToken());
}

$resendVerificationEmail = false;
if (empty($_POST["email"]) && !is_null($user["EMAIL"])) {
	$query->addColumn(new Column("EMAIL", Tables::USERS));
	$query->addValue(null);

	$query->addColumn(new Column("EMAIL_TOKEN", Tables::USERS));
	$query->addValue(Tokens::generateEmailVerificationToken());
	$query->addColumn(new Column("EMAIL_VERIFIED", Tables::USERS));
	$query->addValue(false);
} else if ($_POST["email"] != $user["EMAIL"]) {
	$resendVerificationEmail = true;
	$query->addColumn(new Column("EMAIL", Tables::USERS));
	$query->addValue($_POST["email"]);

	$query->addColumn(new Column("EMAIL_TOKEN", Tables::USERS));
	$query->addValue(Tokens::generateEmailVerificationToken());
	$query->addColumn(new Column("EMAIL_VERIFIED", Tables::USERS));
	$query->addValue(false);
}

$profilePicture = $user["PICTURE_LOC"];
if (isset($_FILES["profile-picture"])) {
	$newImage = Image::upload($_FILES["profile-picture"], Folders::PROFILE_PHOTO, $user["FILE_TOKEN"]);
	if (!is_null($newImage)) {
		$profilePicture = $newImage->getPath();
		$_SESSION["user"]->getImage()->delete();
	}
}

if ($user["PICTURE_LOC"] !== $profilePicture) {
	$query->addColumn(new Column("PICTURE_LOC", Tables::USERS));
	$query->addValue($profilePicture);
}

if (is_null($profilePicture) && is_null($user["PICTURE_LOC"])) {
	$query->addColumn(new Column("PICTURE_NSFW", Tables::USERS));
	$query->addValue(false);
} else if ($_POST["profile-picture-is-nsfw"] == "true") {
	$query->addColumn(new Column("PICTURE_NSFW", Tables::USERS));
	$query->addValue(true);
} else {
	$query->addColumn(new Column("PICTURE_NSFW", Tables::USERS));
	$query->addValue(false);
}

if (($_POST["nsfw-access"] == "true") != $user["NSFW"]) {
	$query->addColumn(new Column("NSFW", Tables::USERS));
	$query->addValue($_POST["nsfw-access"] == "true");
}

if ($_POST["color"] !== $_SESSION["user"]->getColorHex()) {
	$query->addColumn(new Column("COLOR", Tables::USERS));
	$query->addValue(hex2bin($_POST["color"]));
}

if ($_POST["nickname"] != $user["NICK"]) {
	$query->addColumn(new Column("NICK", Tables::USERS));
	$query->addValue($_POST["nickname"] ? $_POST["nickname"] : $_POST["username"]);
}

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::USERS), "=", $id]);
$query->addAdditionalCapability($whereClause);
$query->execute();

Response::sendSuccessResponse("Success", ["redirect_to_totp" => $redirectToTotp]);
