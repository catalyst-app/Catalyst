<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Artist\Artist;
use \Catalyst\Database\Artist\EditArtist;
use \Catalyst\Form\Captcha;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

if (User::isLoggedOut()) {
	\Catalyst\Response::send401(EditArtist::NOT_LOGGED_IN, EditArtist::PHRASES[EditArtist::NOT_LOGGED_IN]);
}

if (empty($_POST)) {
	\Catalyst\Response::send401(EditArtist::IMAGE_INVALID, EditArtist::PHRASES[EditArtist::IMAGE_INVALID]);
}

if (!$_SESSION["user"]->isArtist()) {
	\Catalyst\Response::send401(EditArtist::NOT_AN_ARTIST, EditArtist::PHRASES[EditArtist::NOT_AN_ARTIST]);
}

FormPHP::checkForm(EditArtist::getFormStructure());

$result = EditArtist::update(
	$_SESSION["user"]->getArtistPage(),
	$_POST["name"],
	$_POST["url"],
	$_POST["desc"],
	$_POST["tos"],
	isset($_FILES["img"]) ? $_FILES["img"] : null,
	$_POST["color"]
);

if ($result == EditArtist::ERROR_UNKNOWN) {
	Response::send500(EditArtist::PHRASES[EditArtist::ERROR_UNKNOWN].EditArtist::$lastErrId, EditArtist::ERROR_UNKNOWN);
}

if ($result != EditArtist::SUCCESS) {
	Response::send401($result, EditArtist::PHRASES[$result]);
}

Response::send200($_POST["url"]);
