<?php

if (php_sapi_name() !== 'cli') {
	die("No");
}

// register with the internal api
define("ROOTDIR", "/var/www/beta.catalystapp.co/");
define("REAL_ROOTDIR", "/var/www/beta.catalystapp.co/");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\Email\Email;

/**
 * Log a string to console
 *
 * @param string $in data
 * @param bool $forceSend
 */
function logLine(string $in, bool $forceSend=false) : void {
	global $fullLog;
	global $logStart;
	global $cyclesInLog;

	$fullLog[] = $in;
	if ($forceSend || count($fullLog) > 1000) {
		Email::sendEmail(
			[["error_logs@catalystapp.co","Error Log"]],
			"Staff reminder e-mails log",
			'<pre>'.htmlspecialchars(implode("\n", $fullLog)).'</pre>',
			implode("\n", $fullLog),
			Email::ERROR_LOG_EMAIL,
			Email::ERROR_LOG_PASSWORD,
			Email::ERROR_LOG_SMIME_PATH,
			Email::ERROR_LOG_SMIME_PASSWORD
		);

		$fullLog = [];
		$logStart = date("r");
		$cyclesInLog = 0;
	}
	echo $in."\n";
}

logLine("Running staff reminders script");

$reminderMaster = [
	"weekly" => [
		"blackoak" => [
			"Update/interact with the Tumblr",
		],
		"fauxil" => [
			"Update the Instagram with at least a story or something if ace hasn't done it",
		],
		"foxxo" => [
			"Update the twitter",
			"Post to the Fur Affinity page if possible (and Jiki hasn't done it), or at least be somewhat active on it",
		],
		"jiki" => [
			"Post to the Fur Affinity page if possible, or at least be somewhat active on it",
			"Do something with the Facebook page",
		],
		"lykai" => [
			"Do the weasyl thing",
			"Do the furry network thing",
		],
		"acey" => [
			"Update the Instagram with at least a story or something",
			"Post/update/interact/idk with the Deviant Art page",
		],
		"soul.wesson" => [
			"Post/update/interact with the Deviant Art page if ace hasn't",
			"Update/interact with the twitter (if Foxxo hasn't already)",
		],
	],
	"biweekly" => [
		"blackoak" => [],
		"fauxil" => [],
		"foxxo" => [
			"Do something with the Reddit",
		],
		"jiki" => [],
		"lykai" => [],
		"acey" => [],
		"soul.wesson" => [],
	],
	"triweekly" => [
		"blackoak" => [],
		"fauxil" => [
			"Send an e-mail list update out",
			"Post to Patreon",
			"Post to Ko-Fi",
		],
		"foxxo" => [],
		"jiki" => [],
		"lykai" => [],
		"acey" => [],
		"soul.wesson" => [],
	],
];

$reminders = [
	"blackoak" => [],
	"fauxil" => [],
	"foxxo" => [],
	"jiki" => [],
	"lykai" => [],
	"acey" => [],
	"soul.wesson" => [],
];

foreach ($reminders as $name => &$list) {
	logLine("Adding ".$name."'s weekly list");
	$list = array_merge($list, $reminderMaster["weekly"][$name]);
	if (date("W") % 2 == 0) {
		logLine("Adding ".$name."'s biweekly list");
		$list = array_merge($list, $reminderMaster["biweekly"][$name]);
	}
	if (date("W") % 3 == 0) {
		logLine("Adding ".$name."'s triweekly list");
		$list = array_merge($list, $reminderMaster["triweekly"][$name]);
	}
}
unset($list);

$reminders = array_filter($reminders);

logLine("Reminding ".implode(", ", array_keys($reminders)));

foreach ($reminders as $staff => $list) {
	logLine("Reminding ".$staff." of:");
	foreach ($list as $item) {
		logLine("  ".$item);
	}
	logLine(Email::sendEmail(
		[[$staff."@catalystapp.co", $staff]],
		"Staff Reminders - ".date("r"),
		"<h4>Here are your reminders for this week</h4>".
		"<div class=\"divider\"></div>".
		"<ul>".
		"<li>".implode("</li><li>", array_map("htmlspecialchars", $list))."</li>".
		"</ul>".
		"<p class=\"flow-text\">Please ask any questions/defer/delegate in the appropriate staff areas</p>",
		"Here are your reminders for this week:\n".
		implode("\n", $list).
		"Please ask any questions/defer/delegate in the appropriate staff areas",
		Email::NO_REPLY_EMAIL,
		Email::NO_REPLY_PASSWORD,
		Email::NO_REPLY_SMIME_PATH,
		Email::NO_REPLY_SMIME_PASSWORD
	) ? "Mail sent" : "Mail could not be sent");
}

logLine("Done!", true);
