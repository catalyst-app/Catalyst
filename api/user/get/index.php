<?php

define("ROOTDIR", "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, Response};
use \Catalyst\HTTPCode;

Endpoint::init();

// i'll come back to you i promise
HTTPCode::set(501);

Response::sendErrorResponse(99999, "Unimplemented");
