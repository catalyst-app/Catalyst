<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Database\CommissionType\EditCommissionType;
use \Catalyst\Database\CommissionType\Attributes;
use \Catalyst\Form\FormPHP;
use \Catalyst\Response;
use \Catalyst\User\User;

if (User::isLoggedOut()) {
	Response::send401(EditCommissionType::NOT_LOGGED_IN, EditCommissionType::PHRASES[EditCommissionType::NOT_LOGGED_IN]);
}

if (empty($_POST)) {
	Response::send401(EditCommissionType::IMAGES_INVALID, EditCommissionType::PHRASES[EditCommissionType::IMAGES_INVALID]);
}

if (!$_SESSION["user"]->isArtist()) {
	Response::send401(EditCommissionType::NOT_AN_ARTIST, EditCommissionType::PHRASES[EditCommissionType::NOT_AN_ARTIST]);
}

FormPHP::checkForm(EditCommissionType::getFormStructure());

$typeId = CommissionType::getIdFromToken($_POST["token"]);

if ($typeId == -1) {
	Response::send401(EditCommissionType::INVALID_CT, EditCommissionType::PHRASES[EditCommissionType::INVALID_CT]);
}

$type = new CommissionType($typeId);

if ($type->getArtistPageId() != $_SESSION["user"]->getArtistPageId()) {
	Response::send401(EditCommissionType::INVALID_CT, EditCommissionType::PHRASES[EditCommissionType::INVALID_CT]);
}

if (!isset($_POST["mods"]) || empty($_POST["mods"])) {
	Response::send401(EditCommissionType::MODIFIERS_INVALID, EditCommissionType::PHRASES[EditCommissionType::MODIFIERS_INVALID]);
}

$modGroups = json_decode($_POST["mods"], true);

if ($modGroups === false || !is_array($modGroups) || is_null($modGroups)) {
	Response::send401(EditCommissionType::MODIFIERS_INVALID, EditCommissionType::PHRASES[EditCommissionType::MODIFIERS_INVALID]);
}

foreach ($modGroups as $modGroup) {
	if (count($modGroup) != 2 || 
		count(array_intersect(array_keys($modGroup), ["multiple", "mods"])) != 2 ||
		!is_bool($modGroup["multiple"]) || !is_array($modGroup["mods"])) {
		Response::send401(EditCommissionType::MODIFIERS_INVALID, EditCommissionType::PHRASES[EditCommissionType::MODIFIERS_INVALID]);
	}
	$modGroup["mods"] = (array)$modGroup["mods"];
	foreach ($modGroup["mods"] as $mod) {
		if (count($mod) != 3 || 
			count(array_intersect(array_keys($mod), ["name", "cost", "costUsd"])) != 3 || 
			!is_string($mod["name"]) || !is_numeric($mod["costUsd"]) ||
			!preg_match('/^.{2,60}$/', $mod["name"]) ||
			!preg_match('/^.{2,64}$/', $mod["cost"]) ||
			!preg_match('/^\d{1,4}(|\.\d{1,2})$/', $mod["costUsd"])) {
			Response::send401(EditCommissionType::MODIFIERS_INVALID, EditCommissionType::PHRASES[EditCommissionType::MODIFIERS_INVALID]);
		}
	}
}

$attrs = json_decode($_POST["attrs"], true);

if ($attrs === false || !is_array($attrs) || is_null($attrs)) {
	Response::send401(EditCommissionType::ATTRS_INVALID, EditCommissionType::PHRASES[EditCommissionType::ATTRS_INVALID]);
}

$attrKeys = Attributes::get();
$attrKeys = array_merge(...array_map(function($in) { return array_column($in, 0); }, array_column($attrKeys, "items")));

$attrs = array_unique($attrs);

foreach ($attrs as $attr) {
	if (!in_array($attr, $attrKeys)) {
		Response::send401(EditCommissionType::ATTRS_INVALID, EditCommissionType::PHRASES[EditCommissionType::ATTRS_INVALID]);
	}
}

$payments = json_decode($_POST["payments"], true);

if ($payments === false || !is_array($payments) || is_null($payments)) {
	Response::send401(EditCommissionType::PAYMENT_OPTIONS_INVALID, EditCommissionType::PHRASES[EditCommissionType::PAYMENT_OPTIONS_INVALID]);
}

foreach ($payments as $payment) {
	if (count($payment) != 3 || 
		count(array_intersect(array_keys($payment), ["type", "addr", "instructions"])) != 3 || 
		!is_string($payment["type"]) || !is_string($payment["addr"]) || !is_string($payment["instructions"]) ||
		!preg_match('/^.{2,64}$/', $payment["type"]) ||
		!preg_match('/^.{2,64}$/', $payment["addr"]) ||
		empty($payment["instructions"])) {
		Response::send401(EditCommissionType::PAYMENT_OPTIONS_INVALID, EditCommissionType::PHRASES[EditCommissionType::PAYMENT_OPTIONS_INVALID]);
	}
}

$stages = json_decode($_POST["stages"], true);

if ($stages === false || !is_array($stages) || is_null($stages)) {
	Response::send401(EditCommissionType::STAGES_INVALID, EditCommissionType::PHRASES[EditCommissionType::STAGES_INVALID]);
}

foreach ($stages as $stage) {
	if (!is_string($stage)) {
		Response::send401(EditCommissionType::STAGES_INVALID, EditCommissionType::PHRASES[EditCommissionType::STAGES_INVALID]);
	}
}

$result = EditCommissionType::update(
	$_POST["name"],
	$_POST["blurb"],
	$_POST["desc"],
	$_POST["basecost"],
	$_POST["basecostusd"],
	isset($_FILES["imgs"]) ? $_FILES["imgs"] : null,
	$modGroups,
	$attrs,
	$payments,
	$stages,
	$_POST["addrneeded"] === "true",
	$_POST["open"] === "true",
	$type
);

if ($result == EditCommissionType::ERROR_UNKNOWN) {
	Response::send500(EditCommissionType::PHRASES[EditCommissionType::ERROR_UNKNOWN].EditCommissionType::$lastErrId, EditCommissionType::ERROR_UNKNOWN);
}

if ($result != EditCommissionType::SUCCESS) {
	Response::send401($result, EditCommissionType::PHRASES[$result]);
}

Response::send200(EditCommissionType::SUCCESS);
