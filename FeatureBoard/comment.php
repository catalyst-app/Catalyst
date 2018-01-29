<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Database\FeatureBoard\Comment;
use \Catalyst\Response;
use \Catalyst\User\User;

if (!isset($_POST["feature"]) || !preg_match('/^\d+$/', $_POST["feature"]) || User::isLoggedOut() || !isset($_POST["comment"])) {
	Response::send401(Comment::ERROR_UNKNOWN, Comment::PHRASES[Comment::ERROR_UNKNOWN]);
}

$result = Comment::new(
	$_POST["feature"],
	$_SESSION["user"]->getId(),
	$_POST["comment"]
);

if ($result == Comment::ERROR_UNKNOWN) {
	Response::send500(Comment::PHRASES[Comment::ERROR_UNKNOWN], Comment::ERROR_UNKNOWN);
}

if ($result != Comment::SUCCESS) {
	Response::send401($result, Comment::PHRASES[$result]);
}

Response::send200("Comment submitted");
