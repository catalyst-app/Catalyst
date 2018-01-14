<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";

if (!isset($_POST["email"]) || empty($_POST["email"]) || !isset($_POST["context"]) || empty($_POST["context"])) {
	die("Invalid input");
}

$context = "Web reg: ".$_POST["context"];

$stmt = $GLOBALS["dbh"]->prepare("REPLACE INTO `".DB_TABLES["email_list"]."` (`EMAIL`, `CONTEXT`) VALUES (:EMAIL, :CONTEXT);");
$stmt->bindParam(":EMAIL", $_POST["email"]);
$stmt->bindParam(":CONTEXT", $context);
$stmt->execute();

die($_POST["email"]." has been added to the email list.");
