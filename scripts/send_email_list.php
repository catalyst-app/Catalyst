<?php

if (php_sapi_name() !== 'cli') {
	die("No");
}

$textInput = @file_get_contents($argv[1]);

if ($textInput === false) {
	throw new InvalidArgumentException("Give me a 1st parameter of text input");
}

$htmlInput = @file_get_contents($argv[2]);

if ($htmlInput === false) {
	throw new InvalidArgumentException("Give me a 2nd file of HTML input");
}

// register with the internal api
define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\Endpoint;
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\Query\SelectQuery;
use \Catalyst\Email\Email;
use \Catalyst\Page\{UniversalFunctions, Values};

Endpoint::init(true, Endpoint::AUTH_REQUIRE_NONE);

$emails = [];

if (!file_exists(".emaillist")) {
	echo "Reading e-mail list from database\n";
	$stmt = new SelectQuery();

	$stmt->setTable(Tables::EMAIL_LIST);

	$stmt->addColumn(new Column("EMAIL", Tables::EMAIL_LIST));

	$stmt->execute();

	$emails = array_column($stmt->getResult(), "EMAIL");
} else {
	echo "Reading e-mail list from .emaillist\n";
	$emails = array_filter(explode("\n", file_get_contents(".emaillist")));
}

sort($emails);

// lowercase and uniq
$emails = array_map("strtolower", $emails);
$emails = array_unique($emails);

foreach ($emails as $email) {
	if (!preg_match('/^.{1,254}@.{1,254}\..{1,32}$/', $email) || strlen($email) > 255) {
		throw new InvalidArgumentException($email." is not a valid e-mail address");
	}
}

$date = date("M j, Y");

$subject = "Catalyst Updates - ".$date;

echo "\nCatalyst email list mailer\n";
echo "------------\n";
echo "There are (".count($emails).") addresses which will be sent e-mails, with no definitive alias: \n".implode(",", $emails)."\n";
echo "------------\n";
echo "Subject: ".$subject."\n";
echo "------------\n";
echo "The HTML body is as follows:\n";
echo "  ".str_replace("\n", "\n  ", $htmlInput)."\n";
echo "------------\n";
echo "The text body is as follows:\n";
echo "  ".str_replace("\n", "\n  ", $textInput)."\n";
echo "------------\n";
echo "It is ".$date."\n";
echo "------------\n";
echo "If this is correct, please enter \"Y\" and press enter: ";

if (trim(strtoupper(readline())) != "Y") {
	die("Exiting\n");
}

$htmlEmail = "";

$htmlEmail .= Email::getEmailHeadHtml(Values::DEFAULT_COLOR);

$htmlEmail .= '<div';
$htmlEmail .= ' class="container"';
$htmlEmail .= '>';

$htmlEmail .= UniversalFunctions::createHeading("Catalyst Updates - ".$date);

$htmlEmail .= $htmlInput;

$htmlEmail .= '</div>';

$htmlEmail .= '</body>';

$htmlEmail .= '</html>';

$textEmail = "Catalyst Updates - ".$date."\r\n\r\n".str_replace("\n", "\r\n", $textInput);

echo date("c")." - Ready!\n";

foreach ($emails as $email) {
	echo date("c")." - Sending to ".$email."...\n";
	Email::sendEmail([[$email]], $subject, $htmlEmail, $textEmail, Email::NO_REPLY_EMAIL, Email::NO_REPLY_PASSWORD, Email::NO_REPLY_SMIME_PATH, Email::NO_REPLY_SMIME_PASSWORD, Email::EMAIL_SMTP, false);
}

echo date("c")." - Done!\n";
