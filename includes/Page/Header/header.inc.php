<!DOCTYPE html>
<html data-rootdir="<?= ROOTDIR ?>">
	<head>
		<title>
			<?= PAGE_TITLE ?> | <?= \Catalyst\Page\Values::ROOT_TITLE ?> 
		</title>

<?php foreach (\Catalyst\Page\Header\Header::SCRIPTS as $script): ?>
		<script src="<?= $script[0] ?>" <?= trim(" ".implode(" ", array_slice($script, 1))) ?>></script>
<?php endforeach; ?>

<?php foreach (\Catalyst\Page\Header\Header::STYLES as $style): ?>
		<link href="<?= $style ?>" rel="stylesheet" />
<?php endforeach; ?>

		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<meta content="width=device-width, initial-scale=1, maximum-scale=1.0" name="viewport" />
		<meta charset="utf-8" />
	</head>
	<body>
<?php require_once REAL_ROOTDIR."includes/Page/Navigation/navbar.inc.php"; ?> 
		<div class="container">
<?php if (PAGE_TITLE != \Catalyst\Page\Values::EMAIL_VERIFICATION[1] && isset($_SESSION["user"]) && !$_SESSION["user"]->emailIsVerified()): ?>
			<div class="warning">
				<p class="warning-subitem no-margin flow-text">
					Please verify your email <strong><?= htmlspecialchars($_SESSION["user"]->getEmail()) ?></strong>.
				</p>
				<p class="warning-subitem no-margin">
					Click the link in your verification email.  If you have not received the email or the email is incorrect, please go <a href="<?=ROOTDIR?>EmailVerification">here</a>.
				</p>
			</div>
<?php endif; ?>
