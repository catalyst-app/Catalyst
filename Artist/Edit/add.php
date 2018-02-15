<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Database\SocialMedia;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

if (!User::isLoggedIn()) {
	Response::send500(SocialMedia::PHRASES[SocialMedia::ERROR_UNKNOWN], SocialMedia::ERROR_UNKNOWN);
}

if (!$_SESSION["user"]->isArtist()) {
	Response::send500(SocialMedia::PHRASES[SocialMedia::ERROR_UNKNOWN], SocialMedia::ERROR_UNKNOWN);
}

FormPHP::checkForm(SocialMedia::getFormStructure());

$artist = $_SESSION["user"]->getArtistPage();

$url = ($_POST["url"] ? $_POST["url"] : null);
if (!is_null($url)) {
	$url = preg_match('/https?:\/\/.{2,}/', $url) ? $url : "mailto:".$url;
}

$result = SocialMedia::addToArtist(
	$artist,
	$_POST["network"], $_POST["name"], $url
);

if ($result == SocialMedia::ERROR_UNKNOWN) {
	Response::send500(SocialMedia::PHRASES[SocialMedia::ERROR_UNKNOWN].SocialMedia::$lastErrId, SocialMedia::ERROR_UNKNOWN);
}

if ($result != SocialMedia::SUCCESS) {
	Response::send401($result, SocialMedia::PHRASES[$result]);
}

$meta = \Catalyst\Database\Integrations\Meta::get();

Response::send200(\Catalyst\Integrations\SocialMedia::getChipHtml([[
	"id" => $GLOBALS["dbh"]->lastInsertId(),
	"src" => $meta[$_POST["network"]][0],
	"label" => $_POST["name"],
	"href" => $url,
	"classes" => $meta[$_POST["network"]][2],
	"tooltip" => $meta[$_POST["network"]][1]
]]));
