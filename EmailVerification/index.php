<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Form\FormRepository;
use \Catalyst\{Email, HTTPCode};
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::EMAIL_VERIFICATION[0]);
define("PAGE_TITLE", Values::createTitle(Values::EMAIL_VERIFICATION[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

if (User::isLoggedIn() && $_SESSION["user"]->emailIsVerified()) {
	HTTPCode::set(400);
} else if (!User::isLoggedIn()) {
	HTTPCode::set(401);
}

if (isset($_GET["token"])) {
	$_SESSION["email_token"] = $_GET["token"];
}

if (isset($_GET["resend"]) && $_GET["resend"] && User::isLoggedIn() && !$_SESSION["user"]->emailIsVerified()) {
	$_SESSION["user"]->sendVerificationEmail();
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Email Verification");

?>
<?php if (!User::isLoggedIn()): ?>
<?= User::getNotLoggedInHtml() ?>
<?php elseif ($_SESSION["user"]->emailIsVerified()): ?>
			<div class="section">
				<p class="flow-text">Your email has been verified.  Return <a href="<?=ROOTDIR?>">home</a>?</p>
			</div>
<?php else: ?>
			<div class="section">
				<p class="flow-text">An email has been sent to <strong><?= htmlspecialchars($_SESSION["user"]->getEmail()) ?></strong>.</p>
				<p class="flow-text">Please follow the link inside or enter the token in the box below to validate your account.</p>
<?= FormRepository::getEmailVerificationForm()->getHtml(); ?>
				<p class="flow-text">Didn't recieve an email?</p>
				<p>Try the following:</p>
				<blockquote>
					<p class="flow-text">Check your spam folder</p>
					<p class="flow-text">Ensure you can receive emails from <strong><?= Email::NO_REPLY_EMAIL[0] ?></strong></p>
					<p class="flow-text">Make sure you typed your email correctly: <strong><?= htmlspecialchars($_SESSION["user"]->getEmail()) ?></strong></p>
					<p>You may change your email <a href="<?= ROOTDIR ?>Settings">here</a> if it is incorrect</p>
					<p class="flow-text">Try <a href="?resend=true">resending</a> the email</p>
					<p class="flow-text">If all else fails, contact support</p>
				</blockquote>
			</div>
<?php endif; ?>
<?php
require_once Values::FOOTER_INC;
