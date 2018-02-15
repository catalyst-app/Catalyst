<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Database\SocialMedia;
use \Catalyst\Response;
use \Catalyst\User\User;

if (!User::isLoggedIn() || !$_SESSION["user"]->isArtist() || !isset($_POST["order"]) || json_decode($_POST["order"]) === false || !is_array(json_decode($_POST["order"]))) {
	Response::send500(SocialMedia::PHRASES[SocialMedia::ERROR_UNKNOWN], SocialMedia::ERROR_UNKNOWN);
}

$arr = json_decode($_POST["order"]);

$artist = $_SESSION["user"]->getArtistPage();

$result = SocialMedia::arrangeArtist(
	$artist,
	$arr
);

if ($result == SocialMedia::ERROR_UNKNOWN) {
	Response::send500(SocialMedia::PHRASES[SocialMedia::ERROR_UNKNOWN].SocialMedia::$lastErrId, SocialMedia::ERROR_UNKNOWN);
}

if ($result != SocialMedia::SUCCESS) {
	Response::send401($result, SocialMedia::PHRASES[$result]);
}

Response::send200(SocialMedia::PHRASES[SocialMedia::SUCCESS]);
