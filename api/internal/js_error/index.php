<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\API\{Endpoint, Response};

Endpoint::init(true, Endpoint::AUTH_REQUIRE_NONE);

// trigger_error("JavaScript error has occured."."\r\n<br>\r\n".(array_key_exists("message", $_POST) ? $_POST["message"] : "????")."\r\n<br>\r\n".(array_key_exists("url", $_POST) ? $_POST["url"] : "????")."\r\n<br>\r\n".(array_key_exists("lineNumber", $_POST) ? $_POST["lineNumber"] : "????"), E_USER_WARNING);

Response::sendSuccess("Reported");
