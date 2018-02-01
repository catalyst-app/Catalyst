<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Database\SocialMedia;
use \Catalyst\Response;
use \Catalyst\User\User;

if (User::isLoggedOut() || !$_SESSION["user"]->isArtist() || !isset($_POST["id"]) || !is_numeric($_POST["id"])) {
	Response::send500(SocialMedia::PHRASES[SocialMedia::ERROR_UNKNOWN], SocialMedia::ERROR_UNKNOWN);
}

$id = @(int)$_POST["id"];

$artist = $_SESSION["user"]->getArtistPage();

$result = SocialMedia::delFromArtist(
	$artist,
	$id
);

if ($result == SocialMedia::ERROR_UNKNOWN) {
	Response::send500(SocialMedia::PHRASES[SocialMedia::ERROR_UNKNOWN].SocialMedia::$lastErrId, SocialMedia::ERROR_UNKNOWN);
}

if ($result != SocialMedia::SUCCESS) {
	Response::send401($result, SocialMedia::PHRASES[$result]);
}

Response::send200(SocialMedia::PHRASES[SocialMedia::SUCCESS]);
