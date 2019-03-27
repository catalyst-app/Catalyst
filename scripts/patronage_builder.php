<?php

if (php_sapi_name() !== 'cli') {
	die("No");
}

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\Endpoint;
use \Catalyst\Email\Email;
use \Catalyst\Images\{Folders,Image,MIMEType};
use \Catalyst\Secrets;
use \Catalyst\User\Patron;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_NONE);

$fullLog = [];

function logStr(string $message, bool $forceSend=false) : void {
	global $fullLog;

	echo ($fullLog[] = $message)."\n";

	if ($forceSend) {
		$fullLog[] = "Maximum memory usage: ".memory_get_usage();

		Email::sendEmail(
			[["error_logs@catalystapp.co","Error Log"]],
			"Patron aggregation log",
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

logStr("Starting update of Patreon information.");

$data = json_decode(file_get_contents(
	"https://www.patreon.com/api/oauth2/v2/campaigns/1463061/members?".urlencode("fields[member]")."=full_name,is_follower,last_charge_date,last_charge_status,lifetime_support_cents,currently_entitled_amount_cents,patron_status&".urlencode("fields[tier]")."=amount_cents,created_at,description,discord_role_ids,edited_at,patron_count,published,published_at,title,url",
	false,
	stream_context_create([
		"http" => [
			"header" => "Authorization: Bearer ".Secrets::PATREON_ACCESS_TOKEN."\n",
		]
	])
));

foreach (glob(REAL_ROOTDIR.Folders::PATRON_ICONS."/*") as $file) {
	logStr("Deleting ".$file);
	unlink($file);
}

$patrons = Patron::getAll();

foreach ($data->data as $member) {
	logStr("ID ".$member->id.": ".json_encode($member));
	if (is_null($member->attributes->patron_status)) {
		logStr("Skipping ".$member->id." due to null status.");
		continue;
	}

	$patronExists = false;
	foreach ($patrons as $patronObj) {
		if ($patronObj->getPatreonId() == $member->id) {
			$patronExists = true;
			break;
		}
	}

	logStr("Getting ".$member->id." data");

	$user = json_decode(file_get_contents(
		"https://www.patreon.com/api/oauth2/v2/members/".urlencode($member->id)."?".urlencode("fields[member]")."=full_name,pledge_relationship_start,is_follower,email,last_charge_date,last_charge_status,lifetime_support_cents,patron_status,currently_entitled_amount_cents,will_pay_amount_cents&fields%5Btier%5D=title&fields%5Buser%5D=full_name,image_url&include=user",
		false,
		stream_context_create([
			"http" => [
				"header" => "Authorization: Bearer ".Secrets::PATREON_ACCESS_TOKEN."\n",
			]
		])
	));

	logStr("User data: ".json_encode($user));

	if ($patronExists) {
		logStr("Patron ".$member->id." found in database");
	} else {
		logStr("Adding patron ".$member->id." to database");

		$patronObj = Patron::create([
			"PATREON_ID" => $member->id,
			"NAME" => $user->data->attributes->full_name,
			"CURRENT" => $user->data->attributes->patron_status == "active_patron",
			"PLEDGED_CENTS" => $user->data->attributes->will_pay_amount_cents,
			"TOTAL_CENTS" => $user->data->attributes->lifetime_support_cents,
			"SINCE" => (is_null($user->data->attributes->pledge_relationship_start) || $user->data->attributes->patron_status != "active_patron") ? null : date_create($user->data->attributes->pledge_relationship_start)->format("Y-m-d"),
			"DESCRIPTION" => "",
			"SOCIAL_CHIPS" => "[]",
			"IMAGE_LOC" => "",
		]);
	}

	$image = $user->included[0]->attributes->image_url;

	logStr("Downloading image ".$user->included[0]->attributes->image_url);

	$out = REAL_ROOTDIR.Folders::PATRON_ICONS."/".$member->id;
	copy($user->included[0]->attributes->image_url, $out);

	$ext = MIMEType::getExtensionFromMime(MIMEType::getFilepathMimeType($out));

	rename($out, $out.".".$ext);

	logStr("Saved to ".$out.".".$ext);

	logStr("Executing "."convert ".escapeshellarg($out.".".$ext)." -verbose -coalesce -fx intensity +repage ".escapeshellarg($out."-gray.".$ext));
	
	$output = [];
	$return = 0;
	exec("convert ".escapeshellarg($out.".".$ext)." -verbose -coalesce -fx intensity +repage ".escapeshellarg($out."-gray.".$ext), $output, $return);

	logStr("Returned ".$return);
	foreach ($output as $l) {
		logStr("Output: ".$l);
	}

	logStr("Made ".$out."-gray.".$ext);

	(new Image(Folders::PATRON_ICONS,"",substr($out, strlen(REAL_ROOTDIR.Folders::PATRON_ICONS."/")).".".$ext))->queueForThumbnailing();
	(new Image(Folders::PATRON_ICONS,"",substr($out, strlen(REAL_ROOTDIR.Folders::PATRON_ICONS."/"))."-gray.".$ext))->queueForThumbnailing();

	logStr("Queued for thumbnailing.");

	$patronObj->setCurrent($user->data->attributes->patron_status == "active_patron");
	$patronObj->setPledgedCents($user->data->attributes->will_pay_amount_cents);
	$patronObj->setTotalCents($user->data->attributes->lifetime_support_cents);
	$patronObj->setSince((is_null($user->data->attributes->pledge_relationship_start) || $user->data->attributes->patron_status != "active_patron") ? null : date_create($user->data->attributes->pledge_relationship_start));
	$patronObj->setImageLoc(substr($out, strlen(REAL_ROOTDIR.Folders::PATRON_ICONS."/")).($patronObj->isCurrent() ? "" : "-gray").".".$ext);
}

logStr("All done!", true);
