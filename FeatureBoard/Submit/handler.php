<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Database\FeatureBoard\NewFeature;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

if (User::isLoggedOut()) {
	\Catalyst\Response::send401(NewFeature::ERROR_UNKNOWN, NewFeature::PHRASES[NewFeature::ERROR_UNKNOWN]);
}

FormPHP::checkForm(NewFeature::getFormStructure());

$url = "";

$result = NewFeature::new(
	$_SESSION["user"],
	$_POST["name"],
	$_POST["group"],
	$_POST["intro"],
	$_POST["proposal"],
	$_POST["acknowledgement"],
	$_POST["future"],
	$url
);

if ($result == NewFeature::ERROR_UNKNOWN) {
	Response::send500(NewFeature::PHRASES[NewFeature::ERROR_UNKNOWN].NewFeature::$lastErrId, NewFeature::ERROR_UNKNOWN);
}

if ($result != NewFeature::SUCCESS) {
	Response::send401($result, NewFeature::PHRASES[$result]);
}

Response::send200($url);
