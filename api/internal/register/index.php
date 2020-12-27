<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\{HTTPCode, Tokens};
use \Catalyst\Form\FormRepository;
use \Catalyst\Images\{Folders, Image};
use \Catalyst\User\User;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_OUT);

FormRepository::getRegisterForm()->checkServerSide();

// username in use
if (User::getIdFromUsername($_POST["username"], true) != -1) {
	HTTPCode::set(400);
	Response::sendError("username", "alreadyInUse");
}

// check referrer
if (!empty($_POST["referrer"])) {
	if (User::getIdFromUsername($_POST["referrer"], true) == -1) {
		HTTPCode::set(400);
		Response::sendError("referrer", "usernameDoesNotExist");
	}
}

// check email
if (!empty($_POST["email"])) {
	if (User::getIdFromEmail($_POST["email"], true) != -1) {
		HTTPCode::set(400);
		Response::sendError("email", "alreadyInUse");
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
	"FILE_TOKEN" => $fileToken,
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
Response::sendSuccess("Success");
