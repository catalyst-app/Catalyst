<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Database\Artist\EditArtist;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

FormPHP::checkMethod(["method" => "POST"]);

if (!User::isLoggedIn()) {
	Response::send401(EditArtist::NOT_LOGGED_IN, EditArtist::PHRASES[EditArtist::NOT_LOGGED_IN]);
}

if (!$_SESSION["user"]->isArtist()) {
	Response::send401(EditArtist::NOT_AN_ARTIST, EditArtist::PHRASES[EditArtist::NOT_AN_ARTIST]);
}

if (!isset($_POST["order"]) || empty($_POST["order"]) || json_decode($_POST["order"], true) === false) {
	Response::send401(EditArtist::ERROR_UNKNOWN, EditArtist::PHRASES[EditArtist::ERROR_UNKNOWN]);
}

$order = json_decode($_POST["order"], true);

if (!empty(array_filter(array_map(function($in) {
	return !is_numeric($in) || !preg_match('/^\d+$/', $in);
}, $order)))) {
	Response::send401(EditArtist::ERROR_UNKNOWN, EditArtist::PHRASES[EditArtist::ERROR_UNKNOWN]);
}

$result = EditArtist::order(
	$order,
	$_SESSION["user"]->getArtistPage()
);

if ($result == EditArtist::ERROR_UNKNOWN) {
	Response::send500(EditArtist::PHRASES[EditArtist::ERROR_UNKNOWN].EditArtist::$lastErrId, EditArtist::ERROR_UNKNOWN);
}

if ($result != EditArtist::SUCCESS) {
	Response::send401($result, EditArtist::PHRASES[$result]);
}

Response::send200(EditArtist::PHRASES[EditArtist::SUCCESS]);
