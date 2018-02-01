<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{Endpoint, Response};
use \Catalyst\HTTPCode;
use \Catalyst\User\User;

Endpoint::init();

if (isset($_GET["name"])) {
	if (!preg_match('/^([A-Za-z0-9._-]){2,64}$/', $_GET["name"])) {
		HTTPCode::set(404);
		Response::sendErrorResponse(20001, "The specified user does not exist.");
	}
	$id = User::getIdFromUsername($_GET["name"]);
	if ($id == -1) {
		HTTPCode::set(404);
		Response::sendErrorResponse(20001, "The specified user does not exist.");
	}
	if ($id == $_SESSION["user"]->getId()) {
		$user = $_SESSION["user"];
		$isOwnUser = true;
	} else {
		$user = new User($id);
		$isOwnUser = false;
	}
} else {
	$user = $_SESSION["user"];
	$isOwnUser = true;
}

$result = [];
$result["username"] = $user->getUsername();
if ($isOwnUser) {
	$result["email"] = $user->getEmail();
	$result["email_verified"] = $user->emailIsVerified();
}
$result["artist_page_url"] = $user->getArtistPage();
if (!is_null($result["artist_page_url"])) {
	$result["artist_page_url"] = $result["artist_page_url"]->getUrl();
}
$result["picture_loc"] = "profile_pictures/".$user->getProfilePicture();
$result["picture_nsfw"] = $user->getProfilePictureNsfw();
if ($isOwnUser) {
	$result["nsfw"] = $user->isNsfw();
}
$result["color"] = $user->getColorHex();


Response::sendSuccessResponse("Success", $result);
