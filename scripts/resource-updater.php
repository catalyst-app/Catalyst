<?php

if (php_sapi_name() !== 'cli') {
	die("No");
}

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\Endpoint;
use \Catalyst\Email\Email;
use \Catalyst\Secrets;
use \Catalyst\Page\Resource;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_NONE);

$fullLog = [];

function logStr(string $message, bool $forceSend=false) : void {
	global $fullLog;

	echo ($fullLog[] = $message)."\n";

	if ($forceSend) {
		$fullLog[] = "Maximum memory usage: ".memory_get_usage();

		Email::sendEmail(
			[["error_logs@catalystapp.co","Error Log"]],
			"Resource updating log",
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
}

function githubApiRequest(string $endpoint, array $additionalHttpContext=[]) : array {
	$url = "https://api.github.com/".ltrim($endpoint, "/");
	
	logStr("Pulling ".$url);

	$data = file_get_contents(
		$url,
		false,
		stream_context_create(
			[
				"http" => array_merge([
					"ignore_errors" => true,
					"user_agent" => "catalyst-app",
				], $additionalHttpContext)
			]
		)
	);

	logStr("Decoding response");
	return json_decode($data, true);
}

// no longer needed
// function extractVersionNumber(string $input) : string {
// 	$dotted = preg_replace('/[^0-9]/', ".", $input);
// 	$cleaned = preg_replace('/\.+/', ".", $dotted);
// 	return trim($cleaned, ".");
// }

// no longer needed
// function compareVersionNumbers(string $a, string $b) : int {
// 	$a = explode(".", extractVersionNumber($a));
// 	$b = explode(".", extractVersionNumber($b));
//
// 	for ($i=0; $i < max(count($a), count($b)); $i++) { 
// 		if ($i >= count($a)) {
// 			return 1; // account for 1.0rc1 < 1.0
// 		} elseif ($i >= count($b)) {
// 			return -1;
// 		}
// 		if ($a[$i] != $b[$i]) {
// 			return $a[$i] - $b[$i];
// 		}
// 	}
// 	return 0;
// }

if (!is_dir(REAL_ROOTDIR."scripts/tmp/")) {
	logStr("Creating scripts/tmp");
	if (!mkdir(REAL_ROOTDIR."scripts/tmp/")) {
		throw new Exception("Unable to create scripts/tmp");
	}
}

$resources = Resource::getAll();

foreach ($resources as $resource) {
	if (!$resource->isVersioned()) {
		continue;
	}

	$dir = "scripts/tmp/".$resource->getGithubRepoName();
	if (!is_dir(REAL_ROOTDIR.$dir)) {
		logStr("Creating ".$dir);
		if (!mkdir(REAL_ROOTDIR.$dir, 0777, true)) {
			trigger_error("Unable to create ".$dir, E_USER_NOTICE);
			continue;
		}

		$cmd = "git clone ".escapeshellarg("git@github.com:".$resource->getGithubRepoName())." ".escapeshellarg(REAL_ROOTDIR.$dir);
		$output = [];
		$return = 0;

		logStr("Executing ".$cmd);
		exec($cmd, $output, $return);
		
		logStr("Return code: ".$return);
		array_map("logStr", $output);

		if ($return) {
			trigger_error("Unable to clone ".$resource->getGithubRepoName(), E_USER_NOTICE);
			continue;
		}
	}

	logStr("Pulling ".$resource->getGithubRepoName());

	$cmd = "git ".escapeshellarg("--work-tree=".REAL_ROOTDIR.$dir)." ".escapeshellarg("--git-dir=".REAL_ROOTDIR.$dir."/.git")." pull";
	$output = [];
	$return = 0;

	logStr("Executing ".$cmd);
	exec($cmd, $output, $return);
		
	logStr("Return code: ".$return);
	array_map("logStr", $output);

	if ($return) {
		trigger_error("Unable to pull ".$resource->getGithubRepoName(), E_USER_NOTICE);
		continue;
	}

	logStr("Getting commit history");

	$cmd = "git ".escapeshellarg("--work-tree=".REAL_ROOTDIR.$dir)." ".escapeshellarg("--git-dir=".REAL_ROOTDIR.$dir."/.git")." log --pretty=oneline".($resource->getLatestSource() == "TAG" ? " --no-walk --tags" : "");
	$output = [];
	$return = 0;

	logStr("Executing ".$cmd);
	exec($cmd, $output, $return);
		
	logStr("Return code: ".$return);
	array_map("logStr", $output);

	logStr("Got ".count($output)." commits");
	$numberOutdated = count($output);
	for ($i=0; $i < count($output); $i++) { 
		if (strpos($output[$i], $resource->getCurrentVersion()) !== false) {
			$numberOutdated = $i;
			break;
		}
	}

	logStr("We are ".$numberOutdated." version(s) behind");
	
	$resource->setLatestVersion(substr($output[0], 0, 40));
	if ($i) {
		logStr("Complaining to Discord");
		file_get_contents("https://discordapp.com/api/webhooks/".Secrets::DISCORD_BACKUP_WEBHOOK_TOKEN, false, stream_context_create([
			"http" => [
				"method" => "POST",
				"ignore_errors" => true,
				"header" => "Content-Type: application/x-www-form-urlencoded",
				"content" => json_encode([
					"content" => $resource->getName()." is out of date",
					"embeds" => [
						[
							"title" => "Out of date",
							"fields" => [
								[
									"name" => 'Latest version',
									"value" => $output[0],
								],
								[
									"name" => 'Current version',
									"value" => $resource->getCurrentVersion(),
								],
								[
									"name" => 'Last updated',
									"value" => $resource->getDateOfLatestUpgrade()->format("Y-m-d"),
								],
								[
									"name" => 'Changelog',
									"value" => $resource->getChangelogUrl(),
								],
							],
							"description" => "Please see the embed fields"
						]
					]
				])
			]
		]));
	}
}

$cmd = "composer ".escapeshellarg("--working-dir=".REAL_ROOTDIR."src")." outdated --direct";
$output = [];
$return = 0;

logStr("Executing ".$cmd);
exec($cmd, $output, $return);
	
logStr("Return code: ".$return);
array_map("logStr", $output);

if (!empty(array_filter($output))) {
	logStr("Complaining to discord about composer");
	file_get_contents("https://discordapp.com/api/webhooks/".Secrets::DISCORD_BACKUP_WEBHOOK_TOKEN, false, stream_context_create([
		"http" => [
			"method" => "POST",
			"ignore_errors" => true,
			"header" => "Content-Type: application/x-www-form-urlencoded",
			"content" => json_encode([
				"content" => "Composer is out of date",
				"embeds" => [
					[
						"title" => "Out of date",
						"fields" => [
							[
								"name" => 'Raw output',
								"value" => implode($output, "\r\n"),
							],
						],
						"description" => "Please see the embed fields"
					]
				]
			])
		]
	]));
}
