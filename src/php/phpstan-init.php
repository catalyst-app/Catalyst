<?php

define("ROOTDIR", "");
define("REAL_ROOTDIR", "../");

require_once __DIR__."/initializer.php";
use \Catalyst\User\User;
restore_error_handler();

define("PAGE_COLOR", \Catalyst\Page\Values::DEFAULT_COLOR);
define("PAGE_KEYWORD", \Catalyst\Page\Values::ABOUT_US[0]);
define("PAGE_TITLE", \Catalyst\Page\Values::createTitle(\Catalyst\Page\Values::ABOUT_US[1], []));

$_SESSION["user"] = new User(1);
