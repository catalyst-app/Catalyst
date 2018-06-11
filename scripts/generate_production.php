<?php

if (php_sapi_name() !== 'cli') {
	die("No");
}

// register with the internal api
define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\Endpoint;
use \Catalyst\Page\Resources;
use \tubalmartin\CssMin\Minifier;
use \JSMin\JSMin;

Endpoint::init(true, Endpoint::AUTH_REQUIRE_NONE);

$scripts = Resources::SCRIPTS;

$aggregated = '';
$totalUnminified = 0;

foreach ($scripts as $script) {
	if ($script[0] === Resources::DEVEL) {
		if (strpos($script[1], "js/modules/")) {
			$parameterPosition = strpos($script[1], "?");
			$withoutParameters = substr($script[1], 0, $parameterPosition);
			if (empty($withoutParameters)) {
				$withoutParameters = $script[1];
			}

			$folderEndPos = strpos(($withoutParameters), "js/modules/")+11;
			$withoutFolder = substr(($withoutParameters), $folderEndPos);

			$output = '';
			$returnCode = 0;

			echo "Executing ".'cd '.REAL_ROOTDIR.'js/modules/ && php '.$withoutFolder."\n";

			exec('cd '.REAL_ROOTDIR.'js/modules/ && php '.$withoutFolder, $output, $returnCode);

			if ($returnCode) {
				throw new LogicException($withoutFolder." failed with exit code ".$returnCode);
			}

			$output = implode("\n", $output);

			$totalUnminified += strlen($output);

			echo "Got ".strlen($output)." bytes of JS\n";

			$minified = JSMin::minify($output.";");

			echo "Minified into ".strlen($minified)." bytes\n";

			$aggregated .= $minified.";";
		}
	}
}

$aggregated = trim(<<<PHP_HEADER_FOR_MINIFIED_JS
<?php header("Content-Type: application/javascript; charset=UTF-8", true);header("Cache-Control: max-age=86400", true);?>
PHP_HEADER_FOR_MINIFIED_JS
).$aggregated;

echo "Final JS minified ".$totalUnminified." -> ".strlen($aggregated)." bytes\n";

if (!file_exists(REAL_ROOTDIR."js/dist/")) {
	mkdir(REAL_ROOTDIR."js/dist/");
}

file_put_contents(REAL_ROOTDIR."js/dist/packed.min.js", $aggregated);

echo "Stored minified JS in js/dist/packed.min.js\n";

$output = '';
$returnCode = 0;

exec('cd '.REAL_ROOTDIR.'css/ && php overall.css', $output, $returnCode);

$output = implode("\n", $output);

if ($returnCode) {
	throw new LogicException("overall.css failed with exit code ".$returnCode);
}

$minifiedCss = (new Minifier())->run($output);

$minifiedCss = trim(<<<PHP_HEADER_FOR_MINIFIED_CSS
<?php header("Content-Type: text/css; charset=UTF-8", true);header("Cache-Control: max-age=86400", true);?>
PHP_HEADER_FOR_MINIFIED_CSS
).$minifiedCss;

echo "Minified overall.css (".strlen($output).") -> overall.min.css (".strlen($minifiedCss).")\n";

file_put_contents(REAL_ROOTDIR."css/overall.min.css", $minifiedCss);

echo "Done!\n";
