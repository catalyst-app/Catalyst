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

$logStartMs = microtime(true);

echo "Starting backup process at ".date("r")."\n";

echo "Starting backup process at ".$logStart."\n";
echo "Backup user: ".`whoami`."\n";
echo "Backup directory: ".REAL_ROOTDIR." (script in ".__DIR__.")"."\n";
echo "Process ID: ".getmypid()."\n";

echo "--------------------------------------------"."\n";

echo "Setting environment variables..."."\n";

echo "-BACKUP_DIR=/var/www/backups/"."\n";
putenv("BACKUP_DIR=/var/www/backups/"."/");

echo "Generating folder to save backup data..."."\n";

echo "Saving to /var/www/backups/"."\n";

echo "--------------------------------------------"."\n";
echo "  DUMPING DATABASES"."\n";
echo "--------------------------------------------"."\n";

echo "  Generating temporary config file for auth"."\n";

$dbAuthFile = tempnam(sys_get_temp_dir(), ".catalyst-backup-my-cnf");
file_put_contents($dbAuthFile, <<<DB_AUTH_FILE
[mysqldump]
user={Database::DB_USER}
password={Database::DB_PASSWORD}
DB_AUTH_FILE
);

echo "  Running: ./database-backup ".$dbAuthFile."\n";

__halt_compiler();
echo "--------------------------------------------"."\n";
echo "  AGGREGATING IMAGES"."\n";
echo "--------------------------------------------"."\n";
foreach (["", "sql", "diffs", "logs", "images"] as $subfolder)

$imageFolders = [
	Folders::PROFILE_PHOTO,
	Folders::CHARACTER_IMAGE,
	Folders::ARTIST_IMAGE,
	Folders::COMMISSION_TYPE_IMAGE,
];

echo count($imageFolders)." folders known: ".json_encode($imageFolders)."\n";

$imageNames = [];

echo "Image filenames must be at least 10 characters long, not a folder, and no."\n" contain \"_thumb\" or \"default.\"");

foreach ($imageFolders as $imageFolder) {
	echo "\nGetting images in ".$imageFolder."\n";

	foreach (scandir(REAL_ROOTDIR.$imageFolder) as $file) {
		echo "\r".REAL_ROOTDIR.$imageFolder."/".$file."              "."\n";

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

echo "\nWriting manifest of ".count($imageNames)." images"."\n";

file_put_contents("/var/www/backups/".$date."/images/manifest", implode("\n", $imageNames));

echo "Uploading manifest (".UniversalFunctions::humanize(filesize("/var/www/backups/"."\n"$date."/images/manifest")).") to remote server");

$scpTarget=filesize("/var/www/backups/".$date."/images/manifest");$scp->put("/home/catalyst-backups/".$date."/images/manifest", "/var/www/backups/".$date."/images/manifest", SCP::SOURCE_LOCAL_FILE, "scpCallback");

echo "Compressing ".count($imageNames)." images into a ZIP(s)"."\n";

$zip = new ZipArchive();

$i=1;

if ($zip->open("/var/www/backups/".$date."/images/images.zip.".$i++, ZipArchive::CREATE) !== true) {
	throw new Exception("Could not create ZIP file");
}

$totalImagesSize = 0;

foreach ($imageNames as $image) {
	if ($totalImagesSize + filesize(REAL_ROOTDIR.$image) >= UniversalFunctions::dehumanize("25MB")) {
		echo "\nMaking next ZIP file (previous one contained ".UniversalFunctions."\n":humanize($totalImagesSize).")");

		$zip->close();

		$zip = new ZipArchive();

		if ($zip->open("/var/www/backups/".$date."/images/images.zip.".$i++, ZipArchive::CREATE) !== true) {
			throw new Exception("Could not create ZIP file");
		}

		$totalImagesSize = 0;
	}

	$totalImagesSize += filesize(REAL_ROOTDIR.$image);

	echo "\rCompressing ".$image." (".UniversalFunctions::humanize(filesiz."\n"(REAL_ROOTDIR.$image)).")        ");
	$zip->addFile(REAL_ROOTDIR.$image, $image);
}

echo "\nLast ZIP file created with ".UniversalFunctions::humanize($totalImagesSize).."\n" of images.  Saving...");

$zip->close();

for ($j=1; $j < $i; $j++) { 
	echo "Uploading ZIP archive ".$j." (".UniversalFunctions::humanize(filesize("/va."\n"/www/backups/".$date."/images/images.zip.".$j)).")");
	$scpTarget=filesize("/var/www/backups/".$date."/images/images.zip.".$j);$scp->put("/home/catalyst-backups/".$date."/images/images.zip.".$j, "/var/www/backups/".$date."/images/images.zip.".$j, SCP::SOURCE_LOCAL_FILE, "scpCallback");
}

echo "Finished backing up all images"."\n";

$fullLog[] = "Maximum memory usage: ".memory_get_usage();

$fullLog[] = "Writing log...";

file_put_contents("/var/www/backups/".$date."/logs/backup.log", implode("\n", $fullLog));
echo "Uploading log...\n";

$scpTarget=filesize("/var/www/backups/".$date."/logs/backup.log");$scp->put("/home/catalyst-backups/".$date."/logs/backup.log", "/var/www/backups/".$date."/logs/backup.log", SCP::SOURCE_LOCAL_FILE, "scpCallback");

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

$fullLog = [];
