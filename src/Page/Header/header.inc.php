<?php
use \Catalyst\Controller;
use \Catalyst\Page\{Resources, UniversalFunctions, Values};

Resources::pushPageResources();
?>
<!DOCTYPE html>
<html data-rootdir="<?= ROOTDIR ?>" lang="en">
	<head>
		<meta charset="utf-8" /><!-- must be in the first 1024 bytes yada yada -->

		<title>
			<?= htmlspecialchars(PAGE_TITLE) ?> | <?= Values::ROOT_TITLE ?> 
		</title>

<?php foreach (Resources::getStyles() as $style): ?>
		<link href="<?= $style[0] ?>" <?= trim(" ".implode(" ", array_slice($style, 1))) ?> rel="stylesheet" />
<?php endforeach; ?>

		<meta content="width=device-width, initial-scale=1, maximum-scale=1.0" name="viewport" />
		<meta name="description" content="Catalyst serves to facilitate the process of commissioning through a simple, unified, and mobile-friendly way for artists to easily list their prices, receive and track commissions, and much more."/>
		<meta name="keywords" content="Catalyst"/>
		<meta name="subject" content="Art, furry, commissions, catalyst"/>
		<meta name="copyright" content="Catalyst"/>
		<meta name="language" content="EN"/>
		<meta name="robots" content="index,follow"/>
		<meta name="Classification" content="Business"/>
		<meta name="author" content="Catalyst, catalyst@catalystapp.co"/>
		<meta name="designer" content="Fauxil Fox"/>
		<meta name="reply-to" content="catalyst@catalystapp.co"/>
		<meta name="theme-color" content="#<?= PAGE_COLOR ?>"/>

		<!-- Apple -->
		<meta name="apple-mobile-web-app-title" content="Catalyst"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name="apple-touch-fullscreen" content="yes"/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<link href="https://catalystapp.co/img/logo/solid/logo.png" rel="apple-touch-icon" type="image/png"/>
		<link href="https://catalystapp.co/img/logo/solid/logo.png" rel="apple-touch-icon-precomposed" type="image/png"/>
		<link href="https://catalystapp.co/img/logo/solid/logo.png" rel="apple-touch-icon" type="image/png"/>
		<link href="https://catalystapp.co/img/logo/solid/logo.png" rel="apple-touch-icon-precomposed" type="image/png"/>
		<link rel="mask-icon" href="https://catalystapp.co/img/logo/mask.svg" color="#1b5e20"/>

		<!-- IE -->
		<meta name="msapplication-tooltip" content="Catalyst - Facilitating Commissions"/>
		<meta name="mssmarttagspreventparsing" content="true"/>
		<meta name="msapplication-starturl" content="https://catalystapp.co/"/>
		<meta name="msapplication-window" content="width=800;height=600"/>
		<meta name="msapplication-navbutton-color" content="green"/>
		<meta name="application-name" content="Catalyst"/>

		<!-- win 8+ -->
		<meta name="application-name" content="Catalyst"/>
		<meta name="msapplication-TileColor" content="#<?= PAGE_COLOR ?>"/>
		<meta name="msapplication-square70x70logo" content="<?= ROOTDIR ?>img/logo/solid/logo.png"/>

		<!-- opengraph -->
		<meta property="og:title" content="<?= htmlspecialchars(PAGE_TITLE) ?>"/>
		<meta property="og:type" content="business.business"/>
		<meta property="og:url" content="<?= htmlspecialchars(UniversalFunctions::getCanonicalRequestUrl()) ?>"/>
		<?php if (defined("PAGE_IMAGE")): ?>
			<meta property="og:image" content="<?= phpUri::parse(UniversalFunctions::getRequestUrl())->join(PAGE_IMAGE) ?>"/>
			<meta property="og:image:url" content="<?= phpUri::parse(UniversalFunctions::getRequestUrl())->join(PAGE_IMAGE) ?>"/>
		<?php else: ?>
			<meta property="og:image" content="https://catalystapp.co/img/banners/sammy/usage/main.png"/>
			<meta property="og:image:url" content="https://catalystapp.co/img/banners/sammy/usage/main.png"/>
		<?php endif; ?>
		<meta property="og:description" content="Catalyst serves to facilitate the process of commissioning through a simple, unified, and mobile-friendly way for artists to easily list their prices, receive and track commissions, and much more."/>
		<meta property="og:site_name" content="Catalyst"/>
		<meta property="og:locale" content="en_US"/>

		<!-- twitter -->
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:site" content="<?= htmlspecialchars(UniversalFunctions::getCanonicalRequestUrl()) ?>" />
		<meta name="twitter:title" content="<?= htmlspecialchars(PAGE_TITLE) ?> | Catalyst" />
		<meta name="twitter:description" content="Catalyst serves to facilitate the process of commissioning through a simple, unified, and mobile-friendly way for artists to easily list their prices, receive and track commissions, and much more." />
		<?php if (defined("PAGE_IMAGE")): ?>
			<meta property="twitter:image" content="<?= phpUri::parse(UniversalFunctions::getRequestUrl())->join(PAGE_IMAGE) ?>"/>
		<?php else: ?>
			<meta property="twitter:image" content="https://catalystapp.co/img/banners/sammy/usage/main.png"/>
		<?php endif; ?>

		<!-- link tags -->
		<link rel='shortcut icon' type='image/png' href='https://catalystapp.co/img/logo/icon/logo.png'/>
		<link rel='fluid-icon' type='image/png' href='https://catalystapp.co/img/logo/icon/logo.png'/>
		<link rel="canonical" href="<?= htmlspecialchars(UniversalFunctions::getCanonicalRequestUrl()) ?>"/>
		<link rel='publisher' href="https://plus.google.com/102762464787584663279/"/>
		<link rel="image_src" href="https://catalystapp.co/img/logo/white/logo.png" type="image/png"/>
	</head>
	<body>
		<?php require REAL_ROOTDIR."src/Page/Navigation/navbar.inc.php"; ?> 
		<div class="container">
			<?php if (Controller::isDevelMode() && !array_key_exists("last_news", $_COOKIE) || $_COOKIE["last_news"] != Values::NEWEST_NEWS_ID): ?>
				<div class="news">
					<p class="no-margin">
						<span class="flow-text">
							<strong><?= htmlspecialchars(Values::NEWEST_NEWS_DATE) ?> News: <?= htmlspecialchars(Values::NEWEST_NEWS_LABEL) ?></strong>
						</span>
						<a href="#" class="right green-text text-darken-4" data-cookie-val="<?= Values::NEWEST_NEWS_ID ?>" id="hide-news-button">hide</a>
					</p>
					<p class="no-margin">
						<?= Values::NEWEST_NEWS_DESC ?> (read more at our <a href="<?= ROOTDIR ?>Blog" class="green-text text-darken-4">blog</a>).
					</p>
				</div>
			<?php endif; ?>
			<?php if (PAGE_TITLE != Values::EMAIL_VERIFICATION[1] && isset($_SESSION["user"]) && !$_SESSION["user"]->isEmailVerified() && !is_null($_SESSION["user"]->getEmail())): ?>
				<div class="warning">
					<p class="no-margin flow-text">
						Please verify your email <strong><?= htmlspecialchars($_SESSION["user"]->getEmail()) ?></strong>.
					</p>
					<p class="no-margin">
						Click the link in your verification email.  If you have not received the email or the email is incorrect, please go <a href="<?=ROOTDIR?>EmailVerification" class="yellow-text text-darken-4">here</a>.
					</p>
				</div>
			<?php endif; ?>
