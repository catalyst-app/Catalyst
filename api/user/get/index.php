<?php

define("ROOTDIR", "../../../");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\API\Endpoint;
Endpoint::checkAuthorizationHeaders();
