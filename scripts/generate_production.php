<?php

if (php_sapi_name() !== 'cli') {
	die("No");
}

// register with the internal api
define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/php/initializer.php";
use \Catalyst\API\Endpoint;
use \Catalyst\Page\Resource;
use \tubalmartin\CssMin\Minifier;
use \JSMin\JSMin;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_NONE);

$aggregated = '';
$totalUnminified = 0;

$resources = Resource::getAllForEnvironment(true);

foreach ($resources as $resource) {
	if (!$resource->isScript()) {
		continue;
	}
	if (!$resource->isLocal()) {
		continue;
	}
	$parameterPosition = strpos($resource->getName(), "?");
	$withoutParameters = substr($resource->getName(), 0, $parameterPosition);
	if (empty($withoutParameters)) {
		$withoutParameters = $resource->getName();
	}

	$folderEndPos = strpos(($withoutParameters), "modules/")+8;
	$withoutFolder = substr(($withoutParameters), $folderEndPos);

	$output = '';
	$returnCode = 0;

	echo "Executing ".'cd '.REAL_ROOTDIR.'src/js/modules/ && php '.$withoutFolder."\n";

	exec('cd '.REAL_ROOTDIR.'src/js/modules/ && php '.$withoutFolder, $output, $returnCode);

	if ($returnCode) {
		throw new LogicException($withoutFolder." failed with exit code ".$returnCode);
	}

	$output = implode("\n", $output).";";

	$totalUnminified += strlen($output);

	echo "Got ".strlen($output)." bytes of JS\n";

	$minified = JSMin::minify($output);

	echo "Minified into ".strlen($minified)." bytes\n";

	$aggregated .= $minified;
}

echo "Final JS minified ".$totalUnminified." -> ".strlen($aggregated)." bytes\n";

if (!file_exists(REAL_ROOTDIR."src/js/dist/")) {
	mkdir(REAL_ROOTDIR."src/js/dist/");
}

file_put_contents(REAL_ROOTDIR."src/js/dist/packed.min.js", $aggregated);

echo "Stored minified JS in src/js/dist/packed.min.js\n";

$output = '';
$returnCode = 0;

exec('cd '.REAL_ROOTDIR.'src/css/ && php overall.css', $output, $returnCode);

$output = implode("\n", $output);

if ($returnCode) {
	throw new LogicException("overall.css failed with exit code ".$returnCode);
}

$minifiedCss = (new Minifier())->run($output);

echo "Minified overall.css (".strlen($output).") -> overall.min.css (".strlen($minifiedCss).")\n";

file_put_contents(REAL_ROOTDIR."src/css/overall.min.css", $minifiedCss);

echo "Done!\n";
