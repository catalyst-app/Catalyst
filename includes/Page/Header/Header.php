<?php

namespace Catalyst\Page\Header;

class Header {
	const SCRIPTS = [
		["https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"],
		["https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.js"],
		["https://cdnjs.cloudflare.com/ajax/libs/jquery-color/2.1.2/jquery.color.min.js", "defer"],
		["https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.3/draggable.bundle.min.js"],
		["https://cdn.rawgit.com/namuol/cheet.js/master/cheet.min.js", "defer"],
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

	const LOGO_HTML = '
<img alt="logo" class="hide-on-small-only" height="60px" src="'.ROOTDIR.'img/logo_square_white.png" style="margin-top: 2px;"/>
<img alt="logo" class="hide-on-med-and-up" height="54px" src="'.ROOTDIR.'img/logo_square_white.png" style="margin-top: 1px;"/>
';
}
