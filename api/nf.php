<?php

if (!isset($_GET["in"])) {
	$_GET["in"] = "";
}

define("ROOTDIR", "../".str_repeat("../", substr_count($_GET["in"], "/")));
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\API\{ErrorCodes, Response};
use \Catalyst\HTTPCode;

HTTPCode::set(404);
Response::sendErrorResponse(10001, ErrorCodes::ERR_10001." (".$_GET["in"].")");
