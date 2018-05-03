<?php

if (php_sapi_name() !== 'cli') {
	die("No");
}

// register with the internal api
define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\Endpoint;
use \Catalyst\Page\Header\Header;
use \tubalmartin\CssMin\Minifier;
use \JSMin\JSMin;

Endpoint::init(true, 0);

$scripts = Header::SCRIPTS;

$aggregated = '';
$totalUnminified = 0;

foreach ($scripts as $script) {
	if ($script[0] === Header::DEVEL) {
		if (strpos($script[1], "js/modules/")) {
			$parameterPosition = strpos($script[1], "?");
			$withoutParameters = substr($script[1], 0, $parameterPosition);
			if (empty($withoutParameters)) {
				$withoutParameters = $script[1];
			}

			$folderEndPos = strpos(($withoutParameters), "js/modules/")+11;
			$withoutFolder = substr(($withoutParameters), $folderEndPos);

			$output;
			$returnCode;

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

echo "Final JS minified ".$totalUnminified." -> ".strlen($aggregated)." bytes\n";

if (!file_exists(REAL_ROOTDIR."js/dist/")) {
	mkdir(REAL_ROOTDIR."js/dist/");
}

file_put_contents(REAL_ROOTDIR."js/dist/packed.min.js", $aggregated);

echo "Stored minified JS in js/dist/packed.min.js\n";

$output;
$returnCode;

exec('cd '.REAL_ROOTDIR.'css/ && php overall.css', $output, $returnCode);

$output = implode("\n", $output);

if ($returnCode) {
	throw new LogicException("overall.css failed with exit code ".$returnCode);
}

$minifiedCss = (new Minifier())->run($output);

$minifiedCss = trim(<<<PHP_HEADER_FOR_MINIFIED_CSS
<?php header("Content-Type: text/css; charset=UTF-8");header("Cache-Control: max-age=86400");__halt_compiler();?>
PHP_HEADER_FOR_MINIFIED_CSS
).$minifiedCss;

echo "Minified overall.css (".strlen($output).") -> overall.min.css (".strlen($minifiedCss).")\n";

file_put_contents(REAL_ROOTDIR."css/overall.min.css", $minifiedCss);

echo "Done!\n";
