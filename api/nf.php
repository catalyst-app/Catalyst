<?php

if (!isset($_GET["in"])) {
	$_GET["in"] = "";
}

define("ROOTDIR", "../".str_repeat("../", substr_count($_GET["in"], "/")));
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\API\Response;
use \Catalyst\HTTPCode;

HTTPCode::set(404);
Response::sendErrorResponse(99901, "The specified endpoint (".$_GET["in"].") was not found.");
