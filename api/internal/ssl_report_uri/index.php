<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\{Endpoint, Response};
use \Catalyst\HTTPCode;

Endpoint::init(true, 0);
HTTPCode::set(204);

trigger_error("Report URI handler recieved data: ".file_get_contents('php://input') , E_USER_ERROR);
