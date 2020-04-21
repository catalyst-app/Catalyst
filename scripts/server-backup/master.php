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

echo "Starting backup process at ".date("r")."\n";
echo "Backup user: ".`whoami`."\n";
echo "Backup directory: ".REAL_ROOTDIR." (script in ".__DIR__.")"."\n";
echo "Process ID: ".getmypid()."\n";

echo "--------------------------------------------"."\n";

echo "Setting environment variables..."."\n";

echo "-BACKUP_DIR=/var/www/backups/"."\n";
putenv("BACKUP_DIR=/var/www/backups/");

echo "Generating folder to save backup data..."."\n";

echo "Saving to /var/www/backups/"."\n";

echo "--------------------------------------------"."\n";
echo "  DUMPING DATABASES"."\n";
echo "--------------------------------------------"."\n";

echo "  Generating temporary config file for auth"."\n";

$dbAuthFile = tempnam(sys_get_temp_dir(), ".catalyst-backup-my-cnf");
file_put_contents($dbAuthFile, sprintf(<<<'DB_AUTH_FILE'
	[mysqldump]
	user=%s
	password=%s
	DB_AUTH_FILE
	, Database::DB_USER, Database::DB_PASSWORD)
);

putenv("DB_NAME=".Database::DB_NAME);

echo "  Running: ./database-backup ".escapeshellarg($dbAuthFile)."\n";

$output = [];
$returnCode = 0;
exec("./database-backup ".escapeshellarg($dbAuthFile), $output, $returnCode);

echo "  Generated a ".UniversalFunctions::humanize(filesize("/var/www/backups/database.sql"))." database backup file"."\n";

if ($returnCode) {
	trigger_error("mysqldump in database-backup returned a non-zero exit code")
}

echo "Ouput (".$returnCode.")"."\n";
foreach ($output as $row) {
	echo $row."\n";
}

echo "  Deleting mysqldump auth file "."\n";

unlink($dbAuthFile);

echo "--------------------------------------------"."\n";
echo "  DUMPING IMAGES"."\n";
echo "--------------------------------------------"."\n";

$imageFolders = [
	Folders::PROFILE_PHOTO,
	Folders::CHARACTER_IMAGE,
	Folders::ARTIST_IMAGE,
	Folders::COMMISSION_TYPE_IMAGE,
];

echo count($imageFolders)." folders known: ".json_encode($imageFolders)."\n";

foreach ($imageFolders as $imageFolder) {
	echo "  Running ./image-folder-backup ".REAL_ROOTDIR.$imageFolder." ".$imageFolder;

	$output = [];
	$returnCode = 0;
	exec("./image-folder-backup ".escapeshellarg(REAL_ROOTDIR.$imageFolder)." ".escapeshellarg($imageFolder), $output, $returnCode);

	if ($returnCode) {
		trigger_error("image-folder-backup in database-backup returned a non-zero exit code")
	}

	echo "Ouput (".$returnCode.")"."\n";
	foreach ($output as $row) {
		echo $row."\n";
	}
}

file_get_contents("https://discordapp.com/api/webhooks/".Secrets::DISCORD_BACKUP_WEBHOOK_TOKEN, false, stream_context_create([
	"http" => [
		"method" => "POST",
		"ignore_errors" => true,
		"header" => "Content-Type: application/x-www-form-urlencoded",
		"content" => json_encode([
			"content" => $date." backup completed in ".(microtime(true)-$logStartMs)." seconds",
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
