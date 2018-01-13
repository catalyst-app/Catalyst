<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Database\Artist\EditArtist;
use \Catalyst\Response;
use \Catalyst\User\User;

if (User::isLoggedOut() || !$_SESSION["user"]->isArtist()) {
	Response::send500(EditArtist::PHRASES[EditArtist::ERROR_UNKNOWN], EditArtist::ERROR_UNKNOWN);
}

$artist = $_SESSION["user"]->getArtistPage();

$result = EditArtist::delete(
	$artist
);

if ($result == EditArtist::ERROR_UNKNOWN) {
	Response::send500(EditArtist::PHRASES[EditArtist::ERROR_UNKNOWN].EditArtist::$lastErrId, EditArtist::ERROR_UNKNOWN);
}

if ($result != EditArtist::SUCCESS) {
	Response::send401($result, EditArtist::PHRASES[$result]);
}

Response::send200(EditArtist::PHRASES[EditArtist::SUCCESS]);
