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
	if (User::getIdFromUsername($_POST["user"]) == -1) {
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

$fileToken = Tokens::generateUserFileToken();

$profilePicture = null;
if (isset($_FILES["profile-picture"])) {
	$newImage = Image::upload($_FILES["profile-picture"], Folders::PROFILE_PHOTO, $fileToken);
	if (!is_null($newImage)) {
		$profilePicture = $newImage->getPath();
	}
}

$_SESSION["user"] = User::create([
	"FILE_TOKEN" => $fileToken = Tokens::generateUserFileToken(),
	"USERNAME" => $_POST["username"],
	"HASHED_PASSWORD" => User::hashPassword($_POST["password"]),
	"EMAIL" => $_POST["email"] ? $_POST["email"] : null,
	"PICTURE_LOC" => $profilePicture,
	"PICTURE_NSFW" => $_POST["profile-picture-is-nsfw"] == "true" && !is_null($profilePicture),
	"NSFW" => $_POST["nsfw-access"] == "true",
	"COLOR" => hex2bin($_POST["color"]),
	"NICK" => $_POST["nickname"] ? $_POST["nickname"] : $_POST["username"],
	"REFERRER" => $_POST["referrer"] ? $_POST["referrer"] : null,
]);

HTTPCode::set(201);
Response::sendSuccessResponse("Success");
