<?php

define("ROOTDIR", "");
define("REAL_ROOTDIR", "");

require_once __DIR__."/controller.php";
define("PAGE_COLOR", \Catalyst\Page\Values::DEFAULT_COLOR);

define("PAGE_KEYWORD", \Catalyst\Page\Values::ABOUT_US[0]);
define("PAGE_TITLE", \Catalyst\Page\Values::createTitle(\Catalyst\Page\Values::ABOUT_US[1], []));

