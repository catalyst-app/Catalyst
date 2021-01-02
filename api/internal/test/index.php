<?php

define("ROOTDIR", isset($_POST["rootdir"]) ? $_POST["rootdir"] : "");
define("REAL_ROOTDIR", "../../../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\API\{Endpoint, ErrorCodes, Response};
use \Catalyst\{Email, HTTPCode};
use \Catalyst\Form\FormRepository;
use \Catalyst\User\{TOTP,User};

Endpoint::init(true, Endpoint::AUTH_REQUIRE_NONE);

FormRepository::getTestForm()->checkServerSide();

Response::sendError("_global", "Server side verification worked, but here's this for debug.");
