<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/initializer.php";
use \Catalyst\Database\FeatureBoard\Groups;
use \Catalyst\Database\FeatureBoard\Item;
use \Catalyst\Response;
use \Catalyst\User\User;

if (!isset($_POST["feature"]) || !preg_match('/^\d+$/', $_POST["feature"]) || !User::isLoggedIn() || !in_array($_POST["vote"], ["YES","MAYBE","NO","IRRELEVANT"]) || Item::hasVoted((int)($_POST["feature"]), $_SESSION["user"]->getId()) || Item::get((int)($_POST["feature"]))["STATUS_KEY"] != "VOTE") {
	Response::send401(1, "Invalid Request");
}

$result = Item::castVote(
	$_POST["feature"],
	$_POST["vote"],
	$_SESSION["user"]->getId()
);

if (!$result) {
	Response::send500("Unknown error", 2);
}

Response::send200("Vote submitted");
