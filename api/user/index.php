<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\API\Response;
use \Catalyst\HTTPCode;

HTTPCode::set(404);
Response::sendErrorResponse(10001, "The specified endpoint (".$_GET["in"].") was not found.");
