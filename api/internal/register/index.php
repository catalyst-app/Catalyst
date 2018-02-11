<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\Database\{Column, InsertQuery, SelectQuery, Tables, WhereClause};
use \Catalyst\{Email, HTTPCode, Tokens};
use \Catalyst\Form\FormRepository;
use \Catalyst\Images\{Folders,Image};
use \Catalyst\Page\Values;
use \Catalyst\User\User;

Endpoint::init(true, 2);

FormRepository::getRegisterForm()->checkServerSide();

// check username
$query = new SelectQuery();
$query->setTable(Tables::USERS);
$query->addColumn(new Column("ID", Tables::USERS));

$whereClause = new WhereClause();
$whereClause->addToClause([new Column("USERNAME", Tables::USERS), "=", $_POST["username"]]);
$query->addAdditionalCapability($whereClause);

$query->execute();

$result = $query->getResult();

if (count($result) != 0) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90303, ErrorCodes::ERR_90303);
}

// check email
if (!empty($_POST["email"])) {
	if (strpos($_POST["email"], "@catalystapp.co") !== false) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90324, ErrorCodes::ERR_90324);
	}

	$query = new SelectQuery();
	$query->setTable(Tables::USERS);
	$query->addColumn(new Column("ID", Tables::USERS));

	$whereClause = new WhereClause();
	$whereClause->addToClause([new Column("EMAIL", Tables::USERS), "=", $_POST["email"]]);
	$query->addAdditionalCapability($whereClause);

	$query->execute();

	$result = $query->getResult();

	if (count($result) != 0) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90308, ErrorCodes::ERR_90308);
	}
}

$query = new InsertQuery();
$query->setTable(Tables::USERS);

$fileToken = Tokens::generateUniqueUserFileToken();
$password = password_hash($_POST["password"], PASSWORD_BCRYPT, ["cost" => Values::BCRYPT_COST]);

$profilePicture = null;
if (isset($_FILES["profile-picture"])) {
	$newImage = Image::upload($_FILES["profile-picture"], Folders::PROFILE_PHOTO, $fileToken);
	if (!is_null($newImage)) {
		$profilePicture = $newImage->getPath();
	}
}

$query->addColumn(new Column("FILE_TOKEN", Tables::USERS));
$query->addValue($fileToken);
$query->addColumn(new Column("USERNAME", Tables::USERS));
$query->addValue($_POST["username"]);
$query->addColumn(new Column("HASHED_PASSWORD", Tables::USERS));
$query->addValue($password);
$query->addColumn(new Column("PASSWORD_RESET_TOKEN", Tables::USERS));
$query->addValue(Tokens::generatePasswordResetToken());
$query->addColumn(new Column("EMAIL", Tables::USERS));
$query->addValue($_POST["email"] ? $_POST["email"] : null); // empty = false = null
$query->addColumn(new Column("EMAIL_TOKEN", Tables::USERS));
$query->addValue(Tokens::generateEmailVerificationToken());
$query->addColumn(new Column("PICTURE_LOC", Tables::USERS));
$query->addValue($profilePicture);
$query->addColumn(new Column("PICTURE_NSFW", Tables::USERS));
$query->addValue($_POST["profile-picture-is-nsfw"] == "true");
$query->addColumn(new Column("NSFW", Tables::USERS));
$query->addValue($_POST["nsfw-access"] == "true");
$query->addColumn(new Column("COLOR", Tables::USERS));
$query->addValue(hex2bin($_POST["color"]));
$query->addColumn(new Column("NICK", Tables::USERS));
$query->addValue($_POST["nickname"] ? $_POST["nickname"] : $_POST["username"]); // if none is set set it as the username

$query->execute();

$_SESSION["user"] = new User($query->getResult());

// if the user's email is null, this will silently return
$_SESSION["user"]->sendVerificationEmail();

HTTPCode::set(201);
Response::sendSuccessResponse("Success");
