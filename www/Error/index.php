<?php

// just for testing and such haha

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."src/php/initializer.php";

throw new Exception("Simulated error page");
