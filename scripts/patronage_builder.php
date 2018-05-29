<?php

if (php_sapi_name() !== 'cli') {
	die("No");
}

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\Endpoint;
use \Catalyst\Integrations\SocialMedia;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_NONE);

define("RANK_ATTRS", [
	"titanium" => [
		"cost" => 500,
		"color" => "424242",
		"open_tag" => '<h1 class="no-bottom-margin">',
		"close_tag" => '</h1>',
	],
	"gold" => [
		"cost" => 100,
		"color" => "ffa000",
		"open_tag" => '<h5 class="no-bottom-margin">',
		"close_tag" => '</h5>',
	],
	"silver" => [
		"cost" => 50,
		"color" => "757575",
		"open_tag" => '<h5 class="no-bottom-margin">',
		"close_tag" => '</h5>',
	],
	"bronze" => [
		"cost" => 10,
		"color" => "b1560f",
		"open_tag" => '<span class="flow-text no-bottom-margin"><strong>',
		"close_tag" => '</strong></span>',
	],
	"patron" => [
		"cost" => 1,
		"color" => "f44336",
		"open_tag" => '<span class="flow-text no-bottom-margin">',
		"close_tag" => '</span>',
	],
]);

// hash (for privacy reasons) -> social media arr
define("PATRON_ATTRS", [
	"1d0c3a9874c9ee8236a080ba1dfd6285bc386ed2a1faad2475eeb2757e37f317" => [
		"color" => "e91e63",
		"name" => "SINNERSCOUT",
		"social" => [
			[
				"NETWORK" => "FURAFFINITY",
				"SERVICE_URL" => "https://www.furaffinity.net/user/sinnerscout/",
				"DISP_NAME" => "~sinnerscout",
			],
			[
				"NETWORK" => "INSTAGRAM",
				"SERVICE_URL" => "https://www.instagram.com/sinnerscout/",
				"DISP_NAME" => "@sinnerscout",
			],
			[
				"NETWORK" => "TELEGRAM",
				"SERVICE_URL" => "https://telegram.dog/sinnerscout",
				"DISP_NAME" => "@sinnerscout",
			],
			[
				"NETWORK" => "DISCORD",
				"SERVICE_URL" => null,
				"DISP_NAME" => "SINNERSCOUT#1276",
			],
		],
	],
	"bf43c9ef9709c2b9cf8eb661bd39db9bf8804463372ebf24ba55e052f5e9cfdd" => [
		"color" => "ffeb3b",
		"name" => "Coyote-Lovely",
		"social" => [
			[
				"NETWORK" => "TWITTER",
				"SERVICE_URL" => "https://twitter.com/CoyoteLovelyDA/",
				"DISP_NAME" => "@CoyoteLovelyDA",
			],
			[
				"NETWORK" => "INSTAGRAM",
				"SERVICE_URL" => "https://www.instagram.com/CoyoteLovelyDA/",
				"DISP_NAME" => "@CoyoteLovelyDA",
			],
			[
				"NETWORK" => "DISCORD",
				"SERVICE_URL" => null,
				"DISP_NAME" => "Coyote-Lovely#2810",
			],
			[
				"NETWORK" => "AMINO",
				"SERVICE_URL" => "https://aminoapps.com/c/furry-amino/page/user/coyote-lovely/2vKW_21gi6fxgP1Z23WPn4ba2WbRYXJPeNW",
				"DISP_NAME" => "Coyote-Lovely"
			]
		],
	],
	"7687e9e26e45238c9f064914b45fac707b6439f2383b94d74ed91f5d161ab4f2" => [
		"color" => "673ab7",
		"name" => "King Amdusias",
		"social" => [
			[
				"NETWORK" => "DISCORD",
				"SERVICE_URL" => null,
				"DISP_NAME" => "King Amdusias#0276",
			],
			[
				"NETWORK" => "PATREON",
				"SERVICE_URL" => "https://www.patreon.com/KingAmdusias",
				"DISP_NAME" => "KingAmdusias",
			],
			[
				"NETWORK" => "INSTAGRAM",
				"SERVICE_URL" => "https://instagram.com/amdusias.png",
				"DISP_NAME" => "@amdusias.png",
			],
			[
				"NETWORK" => "TWITTER",
				"SERVICE_URL" => "https://twitter.com/kingamdusias",
				"DISP_NAME" => "@KingAmdusias",
			],
		],
	],
	"f4b602c57de2f4947891924598af3c3b87f3092ad9536b135ab2cf4551126f73" => [
		"color" => "f44336",
		"name" => "Styx Y. Renegade",
		"social" => [
			[
				"NETWORK" => "DISCORD",
				"SERVICE_URL" => null,
				"DISP_NAME" => "@Styx Y. Renegade#5836",
			],
			[
				"NETWORK" => "FURAFFINITY",
				"SERVICE_URL" => "https://www.furaffinity.net/user/eonlover380/",
				"DISP_NAME" => "~EonLover380",
			],
			[
				"NETWORK" => "YOUTUBE",
				"SERVICE_URL" => "https://www.youtube.com/channel/UCXhmlLoK6clgNGrZ-02tsYg",
				"DISP_NAME" => "Styx Y. Renegade",
			],
			[
				"NETWORK" => "REDDIT",
				"SERVICE_URL" => "https://www.reddit.com/user/Styx_Renegade/",
				"DISP_NAME" => "/u/Styx_Renegade",
			],
		],
	],
	"101855c365fa7db6d73986996d8c814b82c5058cc8b0131d66e553e2d5e7ae30" => [
		"color" => "009688",
		"name" => "nyawenyye",
		"social" => [
			[
				"NETWORK" => "DISCORD",
				"SERVICE_URL" => null,
				"DISP_NAME" => "@nyawenyye#8583",
			],
		],
	],
	"ce993c42af2dd183cbc98a1eb7de3878e2c5a12fc2306efa9cbfd4f5a381dbeb" => [
		"color" => "c09a69",
		"name" => "Lykai 💖",
		"social" => [
			[
				"NETWORK" => "FURAFFINITY",
				"SERVICE_URL" => "http://www.furaffinity.net/user/lykai/",
				"DISP_NAME" => "~Lykai",
			],
			[
				"NETWORK" => "TELEGRAM",
				"SERVICE_URL" => "https://t.me/Lykai",
				"DISP_NAME" => "@Lykai",
			],
			[
				"NETWORK" => "DISCORD",
				"SERVICE_URL" => null,
				"DISP_NAME" => "@Lykai#2495",
			],
			[
				"NETWORK" => "TWITTER",
				"SERVICE_URL" => "https://twitter.com/GoldDingus",
				"DISP_NAME" => "@GoldDingus",
			],
			[
				"NETWORK" => "SELF",
				"SERVICE_URL" => "https://beta.catalystapp.co/Character/View/Lykai",
				"DISP_NAME" => "Reference images",
			],
			[
				"NETWORK" => "EMAIL",
				"SERVICE_URL" => "mailto:lykai@catalystapp.co",
				"DISP_NAME" => "lykai@catalystapp.co",
			],
		],
	],
	"62001cc63c2d2fbc88849b506487f3d00222f073fd0ec2390a0b799b89ea924f" => [
		"color" => "f44336",
		"name" => "AnalogHorse",
		"social" => [
			[
				"NETWORK" => "DISCORD",
				"SERVICE_URL" => null,
				"DISP_NAME" => "@AnalogHorse#0621",
			],
		],
	],
	"10d7870f25e9a18b24bb4fb9ce829102a3f507359a81dd05db95219d51d138df" => [
		"color" => "36393e",
		"name" => "RD",
		"social" => [
			[
				"NETWORK" => "DISCORD",
				"SERVICE_URL" => null,
				"DISP_NAME" => "@RD#8935",
			],
			[
				"NETWORK" => "EMAIL",
				"SERVICE_URL" => "mailto:readrdo@gmail.com",
				"DISP_NAME" => "readrdo@gmail.com",
			],
		],
	],
	"46d46ce6e94f807bc535faf153fa7f96edf563d93718bed42858bdeb95ea43fe" => [
		"color" => "db395b",
		"name" => "Keeri",
		"social" => [
			[
				"NETWORK" => "FURAFFINITY",
				"SERVICE_URL" => "https://www.furaffinity.net/user/keeri/",
				"DISP_NAME" => "~keeri",
			],
			[
				"NETWORK" => "TUMBLR",
				"SERVICE_URL" => "https://keerifox.tumblr.com/",
				"DISP_NAME" => "keerifox",
			],
			[
				"NETWORK" => "STEAM",
				"SERVICE_URL" => "https://steamcommunity.com/id/keeri",
				"DISP_NAME" => "keeri",
			],
			[
				"NETWORK" => "DISCORD",
				"SERVICE_URL" => null,
				"DISP_NAME" => "@keeri#4538",
			],
		],
	],
]);

function logStr(string $section, string $message) : void {
	echo "[".$section."] - ".$message."\n";
}

logStr("main", "Searching for \"PatronReport*.csv\" in current directory...");

$reports = glob("PatronReport*.csv");
sort($reports);

logStr("main", "Found ".json_encode($reports));

logStr("main", "Parsing ".count($reports)." CSVs");

$data = [];

foreach ($reports as $report) {
	logStr($report, "Parsing...");

	$csv = array_map('str_getcsv', file($report));

	for ($i=0; $i<count($csv); $i++) {
		if (in_array(strtoupper($csv[$i][5]), ["PENDING", "OK", "PROCESSED", "DECLINED"])) {
			logStr($report, "Allowing [".$i."] (".$csv[$i][0].") - Status is ".json_encode($csv[$i][5]));
			$data[] = array_merge($csv[$i], [$report]);
		} else {
			logStr($report, "Filtering [".$i."] (".$csv[$i][0].") - Status is ".json_encode($csv[$i][5]));
		}
	}
}

logStr("main", "Objectifying");

$objectedData = [];

for ($i=0; $i<count($data); $i++) {
	logStr("objectification", "Creating object for [".$i."] (".$data[$i][0].")");

	$object = (object)[];

	[
		$object->firstName,
		$object->lastName,
		$object->email,
		$object->pledge,
		$object->lifetime,
		$object->status,
		, // twitter
		, // street
		, // city
		, // state
		, // zip
		, // country
		$object->start,
		$object->maxAmount,
		, // complete
		$object->src,
	] = $data[$i];

	$objectedData[] = $object;
}

logStr("main", "Aggregating by email");

$byEmail = [];

$i=0;

foreach ($objectedData as $entry) {
	logStr($entry->email, "Row ".$i++." encountered");
	if (!array_key_exists($entry->email, $byEmail)) {
		logStr($entry->email, "Adding new key to \$byEmail");
		$byEmail[$entry->email] = [];
	}
	$byEmail[$entry->email][] = $entry;
}

logStr("main", "Aggregated into ".count($byEmail)." unique patron emails:");

foreach ($byEmail as $key => $value) {
	logStr($key, "hash: ".substr(hash("sha256", $key), 0, 14)."...");
	if (array_key_exists(hash("sha256", $key), PATRON_ATTRS)) {
		logStr($key, PATRON_ATTRS[hash("sha256", $key)]["name"]);
		logStr($key, "color #".PATRON_ATTRS[hash("sha256", $key)]["color"]);
		logStr($key, count(PATRON_ATTRS[hash("sha256", $key)]["social"])." social medias");
	} else {
		logStr($key, "METADATA NOT FOUND!  QUITTING!");
		throw new InvalidArgumentException("Metadata not foud for ".$key.".  Add it as hash `".hash("sha256", $key)."`");
	}
	foreach ($value as $row) {
		logStr($key, json_encode($row));
	}
}

logStr("main", "Compacting aggregations");

$finalList = [];

foreach ($byEmail as $key => $value) {
	$maxPledge = $value[0]->maxAmount;
	$lifetime = $value[0]->lifetime;
	$since = $value[0]->start;
	$currentPledge = 0;

	$newestSrc = $value[0]->src;

	$entryForLatestMonth = false;

	foreach ($value as $row) {
		$maxPledge = max($maxPledge, $row->maxAmount);
		$lifetime = max($lifetime, $row->lifetime);
		$since = min($since, $row->start);

		$newestSrc = max($newestSrc, $row->src);

		if ($row->src == $reports[count($reports)-1]) {
			$currentPledge = $row->pledge;
			if (strtoupper($row->status) != "DECLINED") {
				$entryForLatestMonth = true;
			} else {
				logStr($key, "Latest month was declined");
			}
		}
	}

	$since = new DateTime($since);

	logStr($key, "Patron since: ".$since->format("F Y"));
	logStr($key, "Lifetime: ".$lifetime);
	logStr($key, "Maximum pledge: ".$maxPledge);
	logStr($key, "Current pledge: ".($currentPledge ? $currentPledge : "None"));
	logStr($key, "Good standing: ".json_encode($entryForLatestMonth));

	$object = (object)[];

	if ($maxPledge >= 500) {
		$object->rank = "titanium";
	} else if ($maxPledge >= 100) {
		$object->rank = "gold";
	} else if ($maxPledge >= 50) {
		$object->rank = "silver";
	} else if ($maxPledge >= 10) {
		$object->rank = "bronze";
	} else {
		$object->rank = "patron";
	}

	logStr($key, "Rank: ".$object->rank);

	$object->name = PATRON_ATTRS[hash("sha256", $key)]["name"];
	$object->social = PATRON_ATTRS[hash("sha256", $key)]["social"];
	$object->color = PATRON_ATTRS[hash("sha256", $key)]["color"];
	$object->currentPledge = $currentPledge;
	$object->lifetime = $lifetime;
	$object->maxPledge = $maxPledge;
	$object->since = $since->format("F Y");
	$object->sinceObj = $since;
	$object->goodStanding = $entryForLatestMonth;
	
	$object->mostRecentReport = $newestSrc;

	$finalList[] = $object;
}

/*
Sort by:
	active
		start date A
			lifetime A
				patronage A
				patronage B (B < A)
			lifetime B (B < A)
		start date B (B newer than A)
			lifetime ...
	bad standing
for each rank
 */

logStr("main", "Grouping by rank");

$byRank = array_flip(array_keys(RANK_ATTRS));

// initialize keys in correct order
foreach ($byRank as $key => &$value) {
	$value = [];
}
unset($value);

foreach ($finalList as $patron) {
	$byRank[$patron->rank][] = $patron;
}

logStr("main", "Sorted patrons by rank");

foreach ($byRank as $key => $value) {
	logStr($key, count($value)." patrons");
}

logStr("main", "Sectioning by alive/dead");

$aliveDead = [];

foreach ($byRank as $key => $value) {
	$aliveDead[$key] = [
		"alive" => [],
		"dead" => [],
	];
	foreach ($value as $patron) {
		$aliveDead[$key][$patron->goodStanding ? "alive" : "dead"][] = $patron;
	}
}

logStr("main", "Sectioning by start date");

foreach ($aliveDead as $key => &$value) {
	foreach ($value as &$subArr) {
		$originalArr = $subArr;
		$subArr = [];

		foreach ($originalArr as $patron) {
			if (!array_key_exists($patron->sinceObj->format("Y-m"), $subArr)) {
				$subArr[$patron->sinceObj->format("Y-m")] = [];
			}
			$subArr[$patron->sinceObj->format("Y-m")][] = $patron;
		}
		ksort($subArr);
	}
	unset($subArr);
}
unset($value);

logStr("main", "Sectioning by lifetime patronage");

foreach ($aliveDead as $key => &$value) {
	foreach ($value as &$subArr) {
		foreach ($subArr as &$dateArr) {
			$originalArr = $dateArr;
			$dateArr = [];

			foreach ($originalArr as $patron) {
				if (!array_key_exists($patron->lifetime, $subArr)) {
					$dateArr[$patron->lifetime] = [];
				}
				$dateArr[$patron->lifetime][] = $patron;
			}
			krsort($dateArr);
		}
		unset($dateArr);
	}
	unset($subArr);
}
unset($value);

logStr("main", "Sectioning by current patronage");

foreach ($aliveDead as $key => &$value) {
	foreach ($value as &$subArr) {
		foreach ($subArr as &$dateArr) {
			foreach ($dateArr as &$lifetimeArr) {
				$originalArr = $lifetimeArr;
				$lifetimeArr = [];

				foreach ($originalArr as $patron) {
					if (!array_key_exists($patron->currentPledge, $subArr)) {
						$lifetimeArr[$patron->currentPledge] = [];
					}
					$lifetimeArr[$patron->currentPledge][] = $patron;
				}
				krsort($lifetimeArr);
			}
			unset($lifetimeArr);
		}
		unset($dateArr);
	}
	unset($subArr);
}
unset($value);

logStr("main", "Dumping current state for debug (without socials)");

function printPatronRecursive($in, $key, $indent) {
	if (is_array($in)) {
		logStr("debug", $indent.$key." {");
		foreach ($in as $key => $value) {
			printPatronRecursive($value, $key, $indent."    ");
		}
		logStr("debug", $indent."}");
	} else {
		$originalSocial = $in->social;
		unset($in->social);
		$originalSinceObj = $in->sinceObj;
		unset($in->sinceObj);

		logStr("debug", $indent."    ".json_encode($in));

		$in->social = $originalSocial;
		$in->sinceObj = $originalSinceObj;
	}
}

foreach ($aliveDead as $key => $value) {
	printPatronRecursive($value, $key, "");
}

logStr("main", "Generating HTML...");

$patreonHtml = "";

foreach ($aliveDead as $key => $value) {
	logStr($key, "Rank ".$key." (alive: ".count($value["alive"]).", dead: ".count($value["dead"]).")");
	if (array_sum(array_map("count", $value)) == 0) {
		logStr($key, "No items.  `continue`ing to the next rank.");
		continue;
	}
	$label = strtoupper(substr($key, 0, 1)).strtolower(substr($key, 1))." ($".RANK_ATTRS[$key]["cost"]."+):";

	logStr($key, "Generating <h5> header with color ".RANK_ATTRS[$key]["color"].", label \"".$label.'"');

	$patreonHtml .= '<h5';
	$patreonHtml .= ' style="color: #'.RANK_ATTRS[$key]["color"].'"';
	$patreonHtml .= '>';

	$patreonHtml .= htmlspecialchars($label);

	$patreonHtml .= '</h5>';

	$patreonHtml .= '<ul';
	$patreonHtml .= ' class="browser-default"';
	$patreonHtml .= '>';

	logStr($key, "Generating <ul> of patrons");

	$allPatronsInRank = [];

	array_walk_recursive($value, function(&$v) use (&$allPatronsInRank) { $allPatronsInRank[] = $v; });

	foreach ($allPatronsInRank as $patron) {
		$originalSocial = $patron->social;
		unset($patron->social);
		$originalSinceObj = $patron->sinceObj;
		unset($patron->sinceObj);

		$patron->social = $originalSocial;
		$patron->sinceObj = $originalSinceObj;

		$patreonHtml .= '<li';
		$patreonHtml .= ' class="no-bottom-margin"';
		$patreonHtml .= ' style="color: #'.$patron->color.'"';
		$patreonHtml .= '>';

		$patreonHtml .= RANK_ATTRS[$key]["open_tag"];

		if (!$patron->goodStanding) {
			$patreonHtml .= '<s>';
		}

		$patreonHtml .= htmlspecialchars($patron->name);

		if (!$patron->goodStanding) {
			$patreonHtml .= '</s>';
		}

		$patreonHtml .= RANK_ATTRS[$key]["close_tag"];

		$patreonHtml .= '<p class="no-margin">';
		$patreonHtml .= '<em>';

		if (!$patron->goodStanding) {
			$patreonHtml .= '<s>';
		}

		if ($patron->goodStanding) {
			$patreonHtml .= htmlspecialchars("Since ".$patron->since);
		} else {
			$lastDateTime = "unknown";
			if (preg_match('/\d{4}-\d{2}-\d{2}/', $patron->mostRecentReport, $out)) {
				$lastDateTime = (new DateTime($out[0]))->format("F Y");
			}
			$patreonHtml .= htmlspecialchars($patron->since." to ".$lastDateTime);
		}

		if ($patron->currentPledge == $patron->maxPledge) {
			$patreonHtml .= htmlspecialchars(", $".$patron->currentPledge." pledged");
		} else {
			$patreonHtml .= htmlspecialchars(", $".$patron->currentPledge." currently pledged ($".$patron->maxPledge." maximum)");
		}

		$patreonHtml .= ", $".$patron->lifetime." lifetime";

		if (!$patron->goodStanding) {
			$patreonHtml .= '</s>';
		}

		$patreonHtml .= '</em>';
		$patreonHtml .= '</p>';

		if ($patron->goodStanding) {
			$patreonHtml .= '<div';
			$patreonHtml .= ' class="social-chips">';
			$patreonHtml .= SocialMedia::getChipHtml(SocialMedia::getChipArray($patron->social));
			$patreonHtml .= '</div>';
		}

		$patreonHtml .= '</li>';

		$patreonHtml .= '<br>';
	}

	$patreonHtml .= '</ul>';
}

logStr("main", "Generated ".strlen($patreonHtml)." bytes of HTML");
logStr("main", "Outputting to About/patreon.inc.php");

file_put_contents(REAL_ROOTDIR."About/patreon.inc.php", $patreonHtml);
