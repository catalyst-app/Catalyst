<?php

if (php_sapi_name() !== 'cli') {
	die("No");
}

define("ROOTDIR", "/var/www/beta.catalystapp.co/");
define("REAL_ROOTDIR", "/var/www/beta.catalystapp.co/");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Database\Database;
use \Catalyst\Email\Email;
use \Catalyst\Images\Folders;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Secrets;
use \Ifsnop\Mysqldump\Mysqldump;
use \phpseclib\Crypt\RSA;
use \phpseclib\Net\{SCP,SSH2};

define('NET_SSH2_LOGGING', SSH2::LOG_COMPLEX);

$fullLog = [];
$logStart = date("r");
$logStartMs = microtime(true);

/**
 * Log a line of data to STDOUT and to flush buffer to e-mail
 *   of if $forceSend is true
 *
 * @param string $in Line to be logged
 * @param bool $forceSend Force flushing the buffer to e-mail
 */
function logLine(string $in, bool $forceSend=false) : void {
	global $fullLog;
	global $logStart;
	global $logStartMs;
	global $scp;
	global $folder;
	global $j;
	global $imageNames;

	$fullLog[] = rtrim($in);
	if ($forceSend) {
		$fullLog[] = "Maximum memory usage: ".memory_get_usage();

		$fullLog[] = "Writing log...";

		Email::sendEmail(
			[["error_logs@catalystapp.co","Error Log"]],
			"Backup log started at ".$logStart." to ".date("r"),
			'<pre>'.htmlspecialchars(implode("\n", $fullLog)).'</pre>',
			implode("\n", $fullLog),
			Email::ERROR_LOG_EMAIL,
			Email::ERROR_LOG_PASSWORD,
			Email::ERROR_LOG_SMIME_PATH,
			Email::ERROR_LOG_SMIME_PASSWORD
		);

		file_put_contents("/var/www/backups/".$folder."/logs/backup.log", implode("\n", $fullLog));
		echo "Uploading log...\n";
		$scpTarget=filesize("/var/www/backups/".$folder."/logs/backup.log");$scp->put("/home/catalyst-backups/".$folder."/logs/backup.log", "/var/www/backups/".$folder."/logs/backup.log", SCP::SOURCE_LOCAL_FILE, "scpCallback");

		file_get_contents("https://discordapp.com/api/webhooks/".Secrets::DISCORD_BACKUP_WEBHOOK_TOKEN, false, stream_context_create([
			"http" => [
				"method" => "POST",
				"ignore_errors" => true,
				"header" => "Content-Type: application/x-www-form-urlencoded",
				"content" => json_encode([
					"content" => $folder." backup completed in ".(microtime(true)-$logStartMs)." seconds",
					"embeds" => [
						[
							"title" => "Data",
							"fields" => [
								[
									"name" => 'Number of images',
									"value" => count($imageNames),
								],
								[
									"name" => 'Number of image archives',
									"value" => $j,
								],
							],
							"description" => "Please see the embed fields"
						]
					]
				])
			]
		]));

		$fullLog = [];
	}
	echo $in.(strpos($in, "\r") !== false ? "" : "\n");
}

register_shutdown_function(function() : void {
	logLine("Finished backing up", true);
});

function scpCallback($sent) {
	global $scpTarget;

	$w = 60-2;

	$barWidth = round($w*($sent/$scpTarget));

	logLine("\r[".str_repeat("=", max(0,$barWidth-1)).">".str_repeat(" ", $w-$barWidth)."] (".number_format(100*$sent/$scpTarget, 2)."%)...");

	if ($sent == $scpTarget) {
		logLine("");
	}
}

logLine("Starting backup process at ".$logStart);
logLine("Backup user: ".`whoami`);
logLine("Backup directory: ".REAL_ROOTDIR." (script in ".__DIR__.")");
logLine("Process ID: ".getmypid());

logLine("--------------------------------------------");

logLine("Generating folder to save backup data...");

$folder = date("Y-m-d");

logLine("Saving to /var/www/backups/".$folder."/");
logLine("  and catalyst-backups@home.ncovercash.dev:/home/catalyst-backups/".$folder."/");

if (file_exists("/var/www/backups/".$folder."/")) {
	logLine("/var/www/backups/".$folder."/ EXISTS!!  ABORTING");

	throw new Exception("/var/www/backups/".$folder."/ already exists, aborting backup");
}

logLine("Connecting to remote backup server...");

$ssh = new SSH2("home.ncovercash.dev");

logLine("Authenticating with SSH server");

$key = new RSA();
$key->loadKey(file_get_contents("/var/www/.ssh/id_rsa"));

if (!$ssh->login("catalyst-backups", $key)) {
	throw new Exception("SSH authentication failed");
}

$scp = new SCP($ssh);

logLine("Testing for remote backup folder");

$existingBackupTestStr = 'if test -d '.escapeshellarg("/home/catalyst-backups/".$folder."/").'; then echo true; else echo false; fi';

$result = $ssh->exec($existingBackupTestStr);

logLine("SSH: CLIENT: ".$existingBackupTestStr);
logLine("SSH: SERVER: ".trim($result));

if (trim($result) == "true") {
	throw new Exception("home.ncovercash.dev:/home/catalyst-backups/".$folder."/ already exists, aborting backup");
}

logLine("Creating remote backup folders");

foreach (["", "sql", "diffs", "logs", "images"] as $subfolder) {
	logLine("Creating local backup folder /var/www/backups/".$folder."/".$subfolder);
	if (!mkdir("/var/www/backups/".$folder."/".$subfolder, 0755, true)) {
		throw new Exception("Unable to create local backup folder /var/www/backups/".$folder."/".$subfolder);
	}

	logLine("Creating remote backup folder /home/catalyst-backups/".$folder."/".$subfolder);

	$remoteMkdir = "mkdir -p ".escapeshellarg("/home/catalyst-backups/".$folder."/".$subfolder);
	
	logLine("SSH: CLIENT: ".$remoteMkdir);
	logLine("SSH: SERVER: ".$ssh->exec($remoteMkdir));
}

logLine("--------------------------------------------");
logLine("  DUMPING DATABASES");
logLine("--------------------------------------------");

$dump = new Mysqldump(
	Database::getDataSourceName(), 
	Database::DB_USER, 
	Database::DB_PASSWORD,
	[
		"add-drop-database" => true,
		"complete-insert" => true,
		"default-character-set" => "utf8mb4",
		"extended-insert" => false,
		"hex-blob" => true,
		"add-locks" => false,
	],
	Database::getPdoAttributes()
);

logLine("Writing database dump to "."/var/www/backups/".$folder."/sql/catalyst.sql");
$dump->start("/var/www/backups/".$folder."/sql/catalyst.sql");

logLine("Copying ".UniversalFunctions::humanize(filesize("/var/www/backups/".$folder."/sql/catalyst.sql"))." to remote server");

$scpTarget=filesize("/var/www/backups/".$folder."/sql/catalyst.sql");$scp->put("/home/catalyst-backups/".$folder."/sql/catalyst.sql", "/var/www/backups/".$folder."/sql/catalyst.sql", SCP::SOURCE_LOCAL_FILE, "scpCallback");

logLine("--------------------------------------------");
logLine("  AGGREGATING IMAGES");
logLine("--------------------------------------------");

$imageFolders = [
	Folders::PROFILE_PHOTO,
	Folders::CHARACTER_IMAGE,
	Folders::ARTIST_IMAGE,
	Folders::COMMISSION_TYPE_IMAGE,
];

logLine(count($imageFolders)." folders known: ".json_encode($imageFolders));

$imageNames = [];

logLine("Image filenames must be at least 10 characters long, not a folder, and not contain \"_thumb\" or \"default.\"");

foreach ($imageFolders as $imageFolder) {
	logLine("\nGetting images in ".$imageFolder);

	foreach (scandir(REAL_ROOTDIR.$imageFolder) as $file) {
		logLine("\r".REAL_ROOTDIR.$imageFolder."/".$file."              ");

		if (is_dir(REAL_ROOTDIR.$imageFolder."/".$file)) {
			continue;
		}

		if (strlen($file) < 10) {
			continue;
		}

		if (strpos($file, "_thumb") !== false) {
			continue;
		}

		if (strpos($file, "default.") !== false) {
			continue;
		}

		$imageNames[] = $imageFolder."/".$file;
	}
}

$imageNames = array_filter($imageNames);

sort($imageNames);

logLine("\nWriting manifest of ".count($imageNames)." images");

file_put_contents("/var/www/backups/".$folder."/images/manifest", implode("\n", $imageNames));

logLine("Uploading manifest (".UniversalFunctions::humanize(filesize("/var/www/backups/".$folder."/images/manifest")).") to remote server");

$scpTarget=filesize("/var/www/backups/".$folder."/images/manifest");$scp->put("/home/catalyst-backups/".$folder."/images/manifest", "/var/www/backups/".$folder."/images/manifest", SCP::SOURCE_LOCAL_FILE, "scpCallback");

logLine("Compressing ".count($imageNames)." images into a ZIP(s)");

$zip = new ZipArchive();

$i=1;

if ($zip->open("/var/www/backups/".$folder."/images/images.zip.".$i++, ZipArchive::CREATE) !== true) {
	throw new Exception("Could not create ZIP file");
}

$totalImagesSize = 0;

foreach ($imageNames as $image) {
	if ($totalImagesSize + filesize(REAL_ROOTDIR.$image) >= UniversalFunctions::dehumanize("25MB")) {
		logLine("\nMaking next ZIP file (previous one contained ".UniversalFunctions::humanize($totalImagesSize).")");

		$zip->close();

		$zip = new ZipArchive();

		if ($zip->open("/var/www/backups/".$folder."/images/images.zip.".$i++, ZipArchive::CREATE) !== true) {
			throw new Exception("Could not create ZIP file");
		}

		$totalImagesSize = 0;
	}

	$totalImagesSize += filesize(REAL_ROOTDIR.$image);

	logLine("\rCompressing ".$image." (".UniversalFunctions::humanize(filesize(REAL_ROOTDIR.$image)).")        ");
	$zip->addFile(REAL_ROOTDIR.$image, $image);
}

logLine("\nLast ZIP file created with ".UniversalFunctions::humanize($totalImagesSize)." of images.  Saving...");

$zip->close();

for ($j=1; $j < $i; $j++) { 
	logLine("Uploading ZIP archive ".$j." (".UniversalFunctions::humanize(filesize("/var/www/backups/".$folder."/images/images.zip.".$j)).")");
	$scpTarget=filesize("/var/www/backups/".$folder."/images/images.zip.".$j);$scp->put("/home/catalyst-backups/".$folder."/images/images.zip.".$j, "/var/www/backups/".$folder."/images/images.zip.".$j, SCP::SOURCE_LOCAL_FILE, "scpCallback");
}

logLine("Finished backing up all images");
