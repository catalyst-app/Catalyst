<?php

namespace Catalyst\Page\Header;

class Header {
	const SCRIPTS = [
		["https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js", 'crossorigin="anonymous"'],
		["https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.js", 'crossorigin="anonymous"'],
		["https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.3/draggable.bundle.min.js", 'crossorigin="anonymous"'],
		["https://cdn.rawgit.com/namuol/cheet.js/master/cheet.min.js", "defer", 'crossorigin="anonymous"'],
		["https://www.google.com/recaptcha/api.js", "defer"],
		[ROOTDIR."js/markdown-it.js", "defer"],
		[ROOTDIR."js/jq-ajax-progress.js", "defer"],
		[ROOTDIR."js/main.js.php"],
	];

	const STYLES = [
		"https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.css",
		"https://fonts.googleapis.com/icon?family=Material+Icons",
		ROOTDIR."css/overall.css",
		ROOTDIR."css/color.css.php?hex=".PAGE_COLOR,
	];
}
