<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\{SelectQuery, UpdateQuery};
use \Catalyst\{Email, HTTPCode, Tokens};
use \Catalyst\Form\FormRepository;
use \Catalyst\Images\{Image,Folders};
use \Catalyst\Page\Values;
use \Catalyst\User\{TOTP,User};

Endpoint::init(true, 1);

FormRepository::getSettingsForm()->checkServerSide();

$id = $_SESSION["user"]->getId();


// check username is free/own
$stmt = new SelectQuery();
$stmt->setTable(Tables::USERS);
$stmt->addColumn(new Column("ID", Tables::USERS));
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USERNAME", Tables::USERS), "=", $_POST["username"]]);
$whereClause->addToClause(WhereClause::AND);
$whereClause->addToClause([new Column("ID", Tables::USERS), "!=", $id]);
$stmt->addAdditionalCapability($whereClause);
$stmt->execute();

if (count($stmt->getResult()) != 0) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90503, ErrorCodes::ERR_90503);
}

$stmt = new SelectQuery();
$stmt->setTable(Tables::USERS);
$stmt->addColumn(new Column("FILE_TOKEN", Tables::USERS));
$stmt->addColumn(new Column("USERNAME", Tables::USERS));
$stmt->addColumn(new Column("HASHED_PASSWORD", Tables::USERS));
$stmt->addColumn(new Column("PASSWORD_RESET_TOKEN", Tables::USERS));
$stmt->addColumn(new Column("TOTP_KEY", Tables::USERS));
$stmt->addColumn(new Column("TOTP_RESET_TOKEN", Tables::USERS));
$stmt->addColumn(new Column("EMAIL", Tables::USERS));
$stmt->addColumn(new Column("EMAIL_VERIFIED", Tables::USERS));
$stmt->addColumn(new Column("EMAIL_TOKEN", Tables::USERS));
$stmt->addColumn(new Column("PICTURE_LOC", Tables::USERS));
$stmt->addColumn(new Column("PICTURE_NSFW", Tables::USERS));
$stmt->addColumn(new Column("NSFW", Tables::USERS));
$stmt->addColumn(new Column("COLOR", Tables::USERS));
$stmt->addColumn(new Column("NICK", Tables::USERS));
$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::USERS), "=", $id]);
$stmt->addAdditionalCapability($whereClause);
$stmt->execute();

$user = $stmt->getResult()[0];

if (!password_verify($_POST["password"], $user["HASHED_PASSWORD"])) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90522, ErrorCodes::ERR_90522);
}

// check email is free/own
if (!empty($_POST["email"]) && $_POST["email"] != $user["EMAIL"]) {
	$stmt = new SelectQuery();
	$stmt->setTable(Tables::USERS);
	$stmt->addColumn(new Column("ID", Tables::USERS));
	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("EMAIL", Tables::USERS), "=", $_POST["email"]]);
	$whereClause->addToClause(WhereClause::AND);
	$whereClause->addToClause([new Column("ID", Tables::USERS), "!=", $id]);
	$stmt->addAdditionalCapability($whereClause);
	$stmt->execute();
	if (count($stmt->getResult()) != 0) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90508, ErrorCodes::ERR_90508);
	}
	if (strpos($_POST["email"], "@catalystapp.co") !== false) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90523, ErrorCodes::ERR_90523);
	}
}


$stmt = new UpdateQuery();
$stmt->setTable(Tables::USERS);
if ($_POST["username"] != $user["USERNAME"]) {
	$stmt->addColumn(new Column("USERNAME", Tables::USERS));
	$stmt->addValue($_POST["username"]);
}

if (!empty($_POST["new-password"])) {
	$stmt->addColumn(new Column("HASHED_PASSWORD", Tables::USERS));
	$stmt->addValue(password_hash($_POST["new-password"], PASSWORD_BCRYPT, ["cost" => Values::BCRYPT_COST]));

	$stmt->addColumn(new Column("PASSWORD_RESET_TOKEN", Tables::USERS));
	$stmt->addValue(Tokens::generatePasswordResetToken());
}

$redirectToTotp = false;
if (is_null($user["TOTP_KEY"]) != ($_POST["two-factor"] == "false")) {
	if ($_POST["two-factor"] == "true") {
		$redirectToTotp = true;

		$stmt->addColumn(new Column("TOTP_KEY", Tables::USERS));
		$stmt->addValue(TOTP::generateKey());
	} else {
		$stmt->addColumn(new Column("TOTP_KEY", Tables::USERS));
		$stmt->addValue(null);
	}
}
if (is_null($user["TOTP_RESET_TOKEN"])) {
	$stmt->addColumn(new Column("TOTP_RESET_TOKEN", Tables::USERS));
	$stmt->addValue(Tokens::generateTotpResetToken());
}

$resendVerificationEmail = false;
if (empty($_POST["email"]) && !is_null($user["EMAIL"])) {
	$stmt->addColumn(new Column("EMAIL", Tables::USERS));
	$stmt->addValue(null);

	$stmt->addColumn(new Column("EMAIL_TOKEN", Tables::USERS));
	$stmt->addValue(Tokens::generateEmailVerificationToken());
	$stmt->addColumn(new Column("EMAIL_VERIFIED", Tables::USERS));
	$stmt->addValue(false);
} else if ($_POST["email"] != $user["EMAIL"]) {
	$resendVerificationEmail = true;
	$stmt->addColumn(new Column("EMAIL", Tables::USERS));
	$stmt->addValue($_POST["email"]);

	$stmt->addColumn(new Column("EMAIL_TOKEN", Tables::USERS));
	$stmt->addValue(Tokens::generateEmailVerificationToken());
	$stmt->addColumn(new Column("EMAIL_VERIFIED", Tables::USERS));
	$stmt->addValue(false);
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
	$stmt->addColumn(new Column("PICTURE_LOC", Tables::USERS));
	$stmt->addValue($profilePicture);
}

if (is_null($profilePicture) && is_null($user["PICTURE_LOC"])) {
	$stmt->addColumn(new Column("PICTURE_NSFW", Tables::USERS));
	$stmt->addValue(false);
} else if ($_POST["profile-picture-is-nsfw"] == "true") {
	$stmt->addColumn(new Column("PICTURE_NSFW", Tables::USERS));
	$stmt->addValue(true);
} else {
	$stmt->addColumn(new Column("PICTURE_NSFW", Tables::USERS));
	$stmt->addValue(false);
}

if (($_POST["nsfw-access"] == "true") != $user["NSFW"]) {
	$stmt->addColumn(new Column("NSFW", Tables::USERS));
	$stmt->addValue($_POST["nsfw-access"] == "true");
}

if ($_POST["color"] !== $_SESSION["user"]->getColor()) {
	$stmt->addColumn(new Column("COLOR", Tables::USERS));
	$stmt->addValue(hex2bin($_POST["color"]));
}

if ($_POST["nickname"] != $user["NICK"]) {
	$stmt->addColumn(new Column("NICK", Tables::USERS));
	$stmt->addValue($_POST["nickname"] ? $_POST["nickname"] : $_POST["username"]);
}

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("ID", Tables::USERS), "=", $id]);
$stmt->addAdditionalCapability($whereClause);
$stmt->execute();

if ($resendVerificationEmail) {
	$_SESSION["user"]->clearCache("EMAIL");
	$_SESSION["user"]->clearCache("EMAIL_VERIFIED");
	$_SESSION["user"]->sendVerificationEmail();
}

Response::sendSuccessResponse("Success", ["redirect_to_totp" => $redirectToTotp]);
