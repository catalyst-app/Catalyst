<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Database\CommissionType\Wishlist;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

if (!User::isLoggedIn()) {
	Response::send500(Wishlist::PHRASES[Wishlist::ERROR_UNKNOWN], Wishlist::ERROR_UNKNOWN);
}

if (!isset($_POST["id"]) || empty($_POST["id"]) || !is_numeric($_POST["id"]) || !preg_match('/^\d+$/', $_POST["id"])) {
	Response::send401(Wishlist::ERROR_UNKNOWN, Wishlist::PHRASES[Wishlist::ERROR_UNKNOWN]);
}

$result = Wishlist::add(
	$_SESSION["user"],
	$_POST["id"]
);

if ($result == Wishlist::ERROR_UNKNOWN) {
	Response::send500(Wishlist::PHRASES[Wishlist::ERROR_UNKNOWN].Wishlist::$lastErrId, Wishlist::ERROR_UNKNOWN);
}

if ($result != Wishlist::SUCCESS) {
	Response::send401($result, Wishlist::PHRASES[$result]);
}

Response::send201(Wishlist::PHRASES[Wishlist::SUCCESS]);
