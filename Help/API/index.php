<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/initializer.php";
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::API_DOCS[0]);
define("PAGE_TITLE", Values::createTitle(Values::API_DOCS[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("API Documentation");
?>
			<div class="row"><div class="col s12 m9 l10">
			<div class="section hide-on-med-and-up">
<?= Values::createInlineTOC([
	["intro", "Introduction"],
	["keys", "API Keys"],
	["response-codes", "Response Codes"],
	["get-user", "User: get"],
]) ?>
			</div>
			<div class="divider hide-on-med-and-up"></div>
			<div class="section" id="intro">
				<h4>Introduction</h4>
				<p class="flow-text">This page tells you how to use Catalyst's API to do awesome things!</p>
				<p class="flow-text">Catalyst's API is based around a HTTPS/REST API, and operates through GET and POST operations.  Your client must support both of these methods as well as TLS v1.2.</p>
				<p class="flow-text">All API endpoints are based off of our base URL: <span class="code">https://catalystapp.co/api/</span>.  From there, endpoints are separated by scope (user, artist, commission, etc.) then method (create, delete, reorder, etc.), then further parameters if required.</p>
				<p class="flow-text">All requests should contain your authorization headers (see below), as well as a JSON payload which contains parameters</p>
				<p class="flow-text">A <span class="code">?</span> before a parameter name means that it is optional, and a <span class="code">?</span> before a reponse parameter name means that it may be omitted from the response.  The <span class="code">?</span> is NOT part of the parameter.</p>
				<p class="flow-text">A <span class="code">?</span> before a type indicates that it may be null.</p>
				<p class="flow-text">All proper endpoints (not 301, 404, or similar) will return a JSON object.  This object will contain the following keys:</p>
				<ul class="browser-default">
					<li class="flow-text"><span class="code">error</span>: a boolean which states whether or not the request succeeded.</li>
					<li class="flow-text"><span class="code">http_code</span>: the HTTP code returned (see <a href="#response-codes">Response Codes</a>)</li>
					<li class="flow-text"><span class="code">error_code</span>: an error code specific to the endpoint.  Zero on success.</li>
					<li class="flow-text"><span class="code">message</span>: a message containing more information ("Success", "Invalid Password")</li>
					<li class="flow-text"><span class="code">data</span>: an object which contains any applicable return data</li>
					<li class="flow-text"><span class="code">?_debug</span>: debug information which we use to squish bugs</li>
				</ul>
				<p class="flow-text">Generic error codes</p>
				<ul class="browser-default">
					<li class="flow-text"><strong>10001</strong>: Endpoint not found</li>
					<li class="flow-text"><strong>10002</strong>: Invalid method ("GET", "POST", etc)</li>
					<p>We have many internal error codes which may mean different things, however 999999 is likely the only one you will see.</p>
					<li class="flow-text"><strong>99990</strong>: An internal error occured.  Please contact <span class="code">bugs@catalystapp.co</span> with the timestamp, what you were doing, and an explaination of the issue.</li>
					<li class="flow-text"><strong>99991</strong>: An internal error occured.  Please contact <span class="code">bugs@catalystapp.co</span> with the timestamp, what you were doing, and an explaination of the issue.</li>
					<li class="flow-text"><strong>99992</strong>: An internal error occured.  Please contact <span class="code">bugs@catalystapp.co</span> with the timestamp, what you were doing, and an explaination of the issue.</li>
					<li class="flow-text"><strong>99993</strong>: An internal error occured.  Please contact <span class="code">bugs@catalystapp.co</span> with the timestamp, what you were doing, and an explaination of the issue.</li>
					<li class="flow-text"><strong>99994</strong>: An internal error occured.  Please contact <span class="code">bugs@catalystapp.co</span> with the timestamp, what you were doing, and an explaination of the issue.</li>
					<li class="flow-text"><strong>99995</strong>: An internal error occured.  Please contact <span class="code">bugs@catalystapp.co</span> with the timestamp, what you were doing, and an explaination of the issue.</li>
					<li class="flow-text"><strong>99996</strong>: An internal error occured.  Please contact <span class="code">bugs@catalystapp.co</span> with the timestamp, what you were doing, and an explaination of the issue.</li>
					<li class="flow-text"><strong>99997</strong>: An internal error occured.  Please contact <span class="code">bugs@catalystapp.co</span> with the timestamp, what you were doing, and an explaination of the issue.</li>
					<li class="flow-text"><strong>99998</strong>: An internal error occured.  Please contact <span class="code">bugs@catalystapp.co</span> with the timestamp, what you were doing, and an explaination of the issue.</li>
					<li class="flow-text"><strong>99999</strong>: An internal error occured.  Please contact <span class="code">bugs@catalystapp.co</span> with the timestamp, what you were doing, and an explaination of the issue.</li>
				</ul>
			</div>
			<div class="divider"></div>
			<div class="section" id="keys">
				<h4>API Keys</h4>
				<p class="flow-text">In order to use our API, contact us at <span class="code">api@catalystapp.co</span> for credentials.  Please include your Catalyst username, name of your app, and a description of what you intend to do.</p>
				<p class="flow-text">Requests may take up to one week to be processed.</p>
				<p class="flow-text">Upon creation of your app, you will be assigned a set of four tokens: a client ID which identifies your app, a client secret which verifies ownership of the app, an access token which authenticates a user (the one we provide will be your own), and an access secret (additional verification)</p>
				<p class="flow-text">All requests to our API must contain the following headers:</p>
				<ul class="browser-default">
					<li class="flow-text"><strong>Client</strong>: A string which contains your client ID and secret, separated by a comma.</li>
					<p class="no-top-margin"><strong>Example: </strong><span class="code">Client: v8ayeztxskdm8x0sm,xm0xzvm3jncdjsm1iejasjkfv8mkktbyrmzakegcwnc9pmw107fbmy3zbwls</span></p>
					<li class="flow-text"><strong>User</strong>: A string which contains your user's access token and secret, separated by a comma.</li>
					<p class="no-top-margin"><strong>Example: </strong><span class="code">User: 4yt43e1wbgzt1397wcbpv249v51vroh2doc8uhte,2s9wc0nr9d7z17hh6943d66e5br06pnrpt6f3noz42mc9vsep43rg7nf7xai</span></p>
				</ul>
				<p class="flow-text">Error codes</p>
				<ul class="browser-default">
					<li class="flow-text"><strong>11001</strong>: Client header not passed</li>
					<li class="flow-text"><strong>11002</strong>: User header not passed</li>
					<li class="flow-text"><strong>11003</strong>: Client header is invalid</li>
					<li class="flow-text"><strong>11004</strong>: User header is invalid</li>
					<li class="flow-text"><strong>11005</strong>: Client does not exist (was your API access revoked?)</li>
					<li class="flow-text"><strong>11006</strong>: User tokens are invalid (did the user revoke your API access to their account?)</li>
				</ul>
			</div>
			<div class="divider"></div>
			<div class="section" id="response-codes">
				<h4>Response Codes</h4>
				<p class="flow-text">Our API can return a lot of different response codes, depending on what happened.</p>
				<ul class="browser-default">
					<li class="flow-text"><strong>200 (OK)</strong>: The request was successfully completed.</li>
					<li class="flow-text"><strong>201 (CREATED)</strong>: The request was successfully completed, and something was created as a result.</li>
					<li class="flow-text"><strong>202 (DEFERRED)</strong>: The request was recognized, but will be completed later (sending emails for example).</li>
					<li class="flow-text"><strong>301 (MOVED)</strong>: Send the request to the location provided in the <span class="code">Location</span> header.  Only used if you did not include a trailing slash.</li>
					<li class="flow-text"><strong>304 (NOT_MODIFIED)</strong>: The request was recieved, but nothing changed.</li>
					<li class="flow-text"><strong>400 (BAD_REQUEST)</strong>: The request was recieved, but it was invalid.</li>
					<li class="flow-text"><strong>401 (UNAUTHORIZED)</strong>: The <span class="code">Client</span> or <span class="code">User</span> headers are missing or invalid.</li>
					<li class="flow-text"><strong>403 (FORBIDDEN)</strong>: The provided tokens do not have access to the specified resource.</li>
					<li class="flow-text"><strong>404 (NOT_FOUND)</strong>: You tried to access something which does not exist.</li>
					<li class="flow-text"><strong>405 (BAD_METHOD)</strong>: The HTTP method was invalid for this location.</li>
					<li class="flow-text"><strong>418 (TEAPOT)</strong>: I'm a teapot? <a href="https://tools.ietf.org/html/rfc7168">Background</a></li>
					<li class="flow-text"><strong>429 (RATE_LIMITED)</strong>: You've made too many requests.  Chill out for a bit.  If this error persists, contact support.</li>
					<li class="flow-text"><strong>500 (ERROR)</strong>: We broke something.  If you recieve this error, please email <span class="code">bugs@catalystapp.co</span> with the timestamp and an explaination of the issue.</li>
					<li class="flow-text"><strong>501 (UNIMPLEMENTED)</strong>: We haven't made this yet.</li>
					<li class="flow-text"><strong>503 (MAINTENANCE)</strong>: Catalyst is down, likely for upgrades.</li>
					<li class="flow-text"><strong>5xx (???)</strong>: Something is very broken.  Please contact <span class="code">bugs@catalystapp.co</span> with the timestamp and an explaination of the issue.</li>
				</ul>
			</div>
			<div class="divider"></div>
			<div class="section" id="response-codes">
				<h4>User: get</h4>
				<p class="flow-text code">https://catalystapp.co/api/user/get/</p>
				<p class="flow-text">Gets either the current user or one specified by their username</p>
				<p class="flow-text"><strong>GET</strong> Parameters</p>
				<ul class="browser-default">
					<li class="flow-text"><span class="code">?name</span> <span class="code">string</span> Username to get data for.  Omit for current user</li>
				</ul>
				<p class="flow-text">Response</p>
				<ul class="browser-default">
					<li class="flow-text"><span class="code">username</span> <span class="code">string</span> The User's username.</li>
					<li class="flow-text"><span class="code">?email</span> <span class="code">?string</span> The User's email; only provided for logged-in user.</li>
					<li class="flow-text"><span class="code">?email_verified</span> <span class="code">bool</span> If the user has verified their email; only provided for logged-in user.  Always returns true if the user's email is not set</li>
					<li class="flow-text"><span class="code">artist_page_url</span> <span class="code">?string</span> If the user has an artist page, this field will contain their URL</li>
					<li class="flow-text"><span class="code">picture_loc</span> <span class="code">string</span> This contains the path for the user's profile picture, or <span class="code">profile_pictures/default.png</span> if none is set.  Append this path to <span class="code">https://catalystapp.co/</span>.</li>
					<li class="flow-text"><span class="code">picture_nsfw</span> <span class="code">bool</span> If the profile picture is NSFW.  Always false for default image.</li>
					<li class="flow-text"><span class="code">?nsfw</span> <span class="code">bool</span> If the user has NSFW access; only provided for logged-in user.</li>
					<li class="flow-text"><span class="code">color</span> <span class="code">string(6)</span> The user's color of preference, as a 6-character hex.</li>
				</ul>
				<p class="flow-text">Error codes</p>
				<ul class="browser-default">
					<li class="flow-text"><strong>20001</strong>: User account does not exist, has been suspended, or is deactivated</li>
				</ul>
			</div>
			</div>
			<div class="col s12 m3 l2 hide-on-small-only">
<?= Values::createTOC([
	["intro", "Introduction"],
	["keys", "API Keys"],
	["response-codes", "Response Codes"],
	["get-user", "User: get"],
]) ?>
			</div>
		</div>
<?php
require_once Values::FOOTER_INC;


