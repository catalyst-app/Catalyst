<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, Response};

Endpoint::init(true, 0);

trigger_error("JavaScript error has occured."."\r\n<br>\r\n".$_POST["message"]."\r\n<br>\r\n".$_POST["url"]."\r\n<br>\r\n".$_POST["lineNumber"], E_USER_WARNING);

Response::sendSuccessResponse("Reported");
