<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\{HTTPCode, Tokens};
use \Catalyst\Form\FormRepository;
use \Catalyst\Images\{Folders, Image};
use \Catalyst\Page\Values;
use \Catalyst\User\User;

Endpoint::init(true, Endpoint::AUTH_REQUIRED_LOGGED_OUT);

FormRepository::getRegisterForm()->checkServerSide();

if (User::getIdFromUsername($_POST["username"], true) == -1) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90303, ErrorCodes::ERR_90303);
}

// check referrer
if (!empty($_POST["referrer"])) {
	if (User::getIdFromUsername($_POST["user"], true) == -1) {
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
	if (User::getIdFromEmail($_POST["email"], true) != -1) {
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
