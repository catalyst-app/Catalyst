<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\{HTTPCode, Tokens};
use \Catalyst\Form\FormRepository;
use \Catalyst\Images\{Image,Folders};
use \Catalyst\User\{TOTP,User};

Endpoint::init(true, Endpoint::AUTH_REQUIRE_LOGGED_IN);

FormRepository::getSettingsForm()->checkServerSide();

if ($_POST["username"] != $_SESSION["user"]->getUsername()) {
	if (User::getIdFromUsername($_POST["username"], true) !== -1) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90503, ErrorCodes::ERR_90503);
	}
}

if (!$_SESSION["user"]->verifyPassword($_POST["password"])) {
	HTTPCode::set(400);
	Response::sendErrorResponse(90522, ErrorCodes::ERR_90522);
}

// check email is free/own
if (!empty($_POST["email"]) && $_POST["email"] != $_SESSION["user"]->getEmail()) {
	if (User::getIdFromEmail($_POST["email"], true) != -1) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90508, ErrorCodes::ERR_90508);
	}
	if (strpos($_POST["email"], "@catalystapp.co") !== false) {
		HTTPCode::set(400);
		Response::sendErrorResponse(90523, ErrorCodes::ERR_90523);
	}
}

$_SESSION["user"]->setUsername($_POST["username"]);

if (!empty($_POST["new-password"])) {
	$_SESSION["user"]->setPassword($_POST["new-password"]);
	$_SESSION["user"]->setPasswordResetToken(Tokens::generatePasswordResetToken());
}

$redirectToTotp = false;
if (is_null($_SESSION["user"]->getTotpKey()) != ($_POST["two-factor"] == "false")) {
	if ($_POST["two-factor"] == "true") {
		$redirectToTotp = true;
		$_SESSION["user"]->setTotpKey(TOTP::generateKey());
	} else {
		$_SESSION["user"]->setTotpKey(null);
	}
}

if (is_null($_SESSION["user"]->getTotpResetToken())) {
	$_SESSION["user"]->setTotpResetToken(Tokens::generateTotpResetToken());
}

$resendVerificationEmail = false;
if (empty($_POST["email"]) && !empty($_SESSION["user"]->getEmail())) {
	$_SESSION["user"]->setEmail(null);
	$_SESSION["user"]->setEmailToken(Tokens::generateEmailVerificationToken());
	$_SESSION["user"]->setEmailVerified(true);
	$_SESSION["user"]->setEmailVerificationSendable(false); 
	$_SESSION["user"]->setPasswordResetToken(Tokens::generatePasswordResetToken());
} else if ($_POST["email"] != $_SESSION["user"]->getEmail()) {
	$resendVerificationEmail = true;
	$_SESSION["user"]->setEmail($_POST["email"]);
	$_SESSION["user"]->setEmailToken(Tokens::generateEmailVerificationToken());
	$_SESSION["user"]->setEmailVerified(false);
	$_SESSION["user"]->setEmailVerificationSendable(false); 
	$_SESSION["user"]->setPasswordResetToken(Tokens::generatePasswordResetToken());
}

$profilePicture = $_SESSION["user"]->getImage()->getPath();
if (isset($_FILES["profile-picture"])) {
	$newImage = Image::upload($_FILES["profile-picture"], Folders::PROFILE_PHOTO, $_SESSION["user"]->getToken());
	if (!is_null($newImage)) {
		$profilePicture = $newImage->getPath();
		$_SESSION["user"]->getImage()->delete();
	}
}

$_SESSION["user"]->setProfilePicturePath($profilePicture);

if (is_null($profilePicture) && is_null($_SESSION["user"]->getProfilePicturePath())) {
	$_SESSION["user"]->setProfilePictureNsfw(false);
} else if ($_POST["profile-picture-is-nsfw"] == "true") {
	$_SESSION["user"]->setProfilePictureNsfw(true);
} else {
	$_SESSION["user"]->setProfilePictureNsfw(false);
}

$_SESSION["user"]->setNsfw($_POST["nsfw-access"] == "true");
$_SESSION["user"]->setColor($_POST["color"]);
$_SESSION["user"]->setNickname($_POST["nickname"] ? $_POST["nickname"] : $_POST["username"]);

if ($resendVerificationEmail) {
	$_SESSION["user"]->clearCache();
	$_SESSION["user"]->sendVerificationEmail();
}

Response::sendSuccessResponse("Success", ["redirect_to_totp" => $redirectToTotp]);
