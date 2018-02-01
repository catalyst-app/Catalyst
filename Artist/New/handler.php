<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Artist\Artist;
use \Catalyst\Database\Artist\NewArtist;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

if (User::isLoggedOut()) {
	\Catalyst\Response::send401(NewArtist::NOT_LOGGED_IN, NewArtist::PHRASES[NewArtist::NOT_LOGGED_IN]);
}

if (empty($_POST)) {
	\Catalyst\Response::send401(NewArtist::IMAGE_INVALID, NewArtist::PHRASES[NewArtist::IMAGE_INVALID]);
}

if ($_SESSION["user"]->isArtist()) {
	\Catalyst\Response::send401(NewArtist::ALREADY_AN_ARTIST, NewArtist::PHRASES[NewArtist::ALREADY_AN_ARTIST]);
}

FormPHP::checkForm(NewArtist::getFormStructure());

$result = NewArtist::create(
	$_POST["name"],
	$_POST["url"],
	$_POST["desc"],
	$_POST["tos"],
	isset($_FILES["img"]) ? $_FILES["img"] : null,
	$_POST["color"]
);

if ($result == NewArtist::ERROR_UNKNOWN) {
	Response::send500(NewArtist::PHRASES[NewArtist::ERROR_UNKNOWN].NewArtist::$lastErrId, NewArtist::ERROR_UNKNOWN);
}

if ($result != NewArtist::SUCCESS) {
	Response::send401($result, NewArtist::PHRASES[$result]);
}

Response::send201($_POST["url"]);
