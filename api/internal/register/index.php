<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\{InsertQuery, SelectQuery};
use \Catalyst\{Email, HTTPCode, Tokens};
use \Catalyst\Form\FormRepository;
use \Catalyst\Images\{Folders,Image};
use \Catalyst\Page\Values;
use \Catalyst\User\User;

Endpoint::init(true, 2);

FormRepository::getRegisterForm()->checkServerSide();

// check username
$stmt = new SelectQuery();
$stmt->setTable(Tables::USERS);
$stmt->addColumn(new Column("ID", Tables::USERS));

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USERNAME", Tables::USERS), "=", $_POST["username"]]);
$stmt->addAdditionalCapability($whereClause);

$stmt->execute();

$result = $stmt->getResult();

if (count($result) != 0) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90303, ErrorCodes::ERR_90303);
}

// check referrer
if (!empty($_POST["referrer"])) {
	$stmt = new SelectQuery();
	$stmt->setTable(Tables::USERS);
	$stmt->addColumn(new Column("ID", Tables::USERS));

	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("USERNAME", Tables::USERS), "=", $_POST["referrer"]]);
	$stmt->addAdditionalCapability($whereClause);

	$stmt->execute();

	$result = $stmt->getResult();

	if (count($result) == 0) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90326, ErrorCodes::ERR_90326);
	}
}

// check email
if (!empty($_POST["email"])) {
	if (strpos($_POST["email"], "@catalystapp.co") !== false) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90324, ErrorCodes::ERR_90324);
	}

	$stmt = new SelectQuery();
	$stmt->setTable(Tables::USERS);
	$stmt->addColumn(new Column("ID", Tables::USERS));

	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("EMAIL", Tables::USERS), "=", $_POST["email"]]);
	$stmt->addAdditionalCapability($whereClause);

	$stmt->execute();

	$result = $stmt->getResult();

	if (count($result) != 0) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90308, ErrorCodes::ERR_90308);
	}
}

$stmt = new InsertQuery();
$stmt->setTable(Tables::USERS);

$fileToken = Tokens::generateUserFileToken();
$password = password_hash($_POST["password"], PASSWORD_BCRYPT, ["cost" => Values::BCRYPT_COST]);

$profilePicture = null;
if (isset($_FILES["profile-picture"])) {
	$newImage = Image::upload($_FILES["profile-picture"], Folders::PROFILE_PHOTO, $fileToken);
	if (!is_null($newImage)) {
		$profilePicture = $newImage->getPath();
	}
}

$stmt->addColumn(new Column("FILE_TOKEN", Tables::USERS));
$stmt->addValue($fileToken);
$stmt->addColumn(new Column("USERNAME", Tables::USERS));
$stmt->addValue($_POST["username"]);
$stmt->addColumn(new Column("HASHED_PASSWORD", Tables::USERS));
$stmt->addValue($password);
$stmt->addColumn(new Column("PASSWORD_RESET_TOKEN", Tables::USERS));
$stmt->addValue(Tokens::generatePasswordResetToken());
$stmt->addColumn(new Column("EMAIL", Tables::USERS));
$stmt->addValue($_POST["email"] ? $_POST["email"] : null); // empty = false = null
$stmt->addColumn(new Column("EMAIL_TOKEN", Tables::USERS));
$stmt->addValue(Tokens::generateEmailVerificationToken());
$stmt->addColumn(new Column("PICTURE_LOC", Tables::USERS));
$stmt->addValue($profilePicture);
$stmt->addColumn(new Column("PICTURE_NSFW", Tables::USERS));
$stmt->addValue($_POST["profile-picture-is-nsfw"] == "true");
$stmt->addColumn(new Column("NSFW", Tables::USERS));
$stmt->addValue($_POST["nsfw-access"] == "true");
$stmt->addColumn(new Column("COLOR", Tables::USERS));
$stmt->addValue(hex2bin($_POST["color"]));
$stmt->addColumn(new Column("NICK", Tables::USERS));
$stmt->addValue($_POST["nickname"] ? $_POST["nickname"] : $_POST["username"]); // if none is set set it as the username
$stmt->addColumn(new Column("REFERRER", Tables::USERS));
$stmt->addValue($_POST["referrer"] ? $_POST["referrer"] : null); // empty = false = null

$stmt->execute();

$_SESSION["user"] = new User($stmt->getResult());

// if the user's email is null, this will silently return
$_SESSION["user"]->sendVerificationEmail();

HTTPCode::set(201);
Response::sendSuccessResponse("Success");
