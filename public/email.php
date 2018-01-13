<?php

require_once "../includes/Secrets.php";

if (!isset($_POST["email"]) || empty($_POST["email"]) || !isset($_POST["context"]) || empty($_POST["context"])) {
	die("Invalid input");
}

define("DB_DRIVER", "mysql");
define("DB_SERVER", "localhost");
define("DB_PORT", 3306);
define("DB_NAME", "CATALYST");
define("DB_USER", "CATALYST");
// define("DB_PASSWORD", .); DEFINED IN SECRETS.PHP, WAS REGENERATED

define("DB_TABLES", [
	"email_list" => "email_list",
]);

$dbh = new PDO(DB_DRIVER.":host=".DB_SERVER.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$context = "Web reg: ".$_POST["context"];

$stmt = $dbh->prepare("REPLACE INTO `".DB_TABLES["email_list"]."` (`EMAIL`, `CONTEXT`) VALUES (:EMAIL, :CONTEXT);");
$stmt->bindParam(":EMAIL", $_POST["email"]);
$stmt->bindParam(":CONTEXT", $context);
$stmt->execute();

die($_POST["email"]." has been added to the email list.");
