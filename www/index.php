<?php

define("ROOTDIR", "");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\HTTPCode;

HTTPCode::set(302);
header("Location: ".ROOTDIR."About/", true);
die("Redirecting...");
