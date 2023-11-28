<?php
use \Catalyst\Controller;
use \Catalyst\Page\{Resource, UniversalFunctions, Values};

Resource::pushPageResources();
?>
<!DOCTYPE html>
<html data-rootdir="<?= ROOTDIR ?>" lang="en">

<head>
	<meta charset="utf-8" /><!-- must be in the first 1024 bytes yada yada -->

	<title>
		<?= htmlspecialchars(PAGE_TITLE) ?> |
		<?= Values::ROOT_TITLE ?>
	</title>

	<?php foreach (Resource::getStyles() as $style): ?>
		<?= $style->getTag() ?>
	<?php endforeach; ?>

	<meta content="width=device-width, initial-scale=1, maximum-scale=1.0" name="viewport" />
	<meta name="description"
		content="Catalyst serves to facilitate the process of commissioning through a simple, unified, and mobile-friendly way for artists to easily list their prices, receive and track commissions, and much more." />
	<meta name="keywords" content="Catalyst" />
	<meta name="subject" content="Art, furry, commissions, catalyst" />
	<meta name="copyright" content="Catalyst" />
	<meta name="language" content="EN" />
	<meta name="robots" content="none" />
	<meta name="Classification" content="Business" />
	<meta name="author" content="Catalyst, catalyst@catalystapp.co" />
	<meta name="designer" content="Fauxil Fox" />
	<meta name="reply-to" content="catalyst@catalystapp.co" />
	<meta name="theme-color" content="#<?= PAGE_COLOR ?>" />

	<!-- Apple -->
	<meta name="apple-mobile-web-app-title" content="Catalyst" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-touch-fullscreen" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no" />
	<link href="https://catalystapp.co/internal_assets/logo/solid/logo.png" rel="apple-touch-icon" type="image/png" />
	<link href="https://catalystapp.co/internal_assets/logo/solid/logo.png" rel="apple-touch-icon-precomposed"
		type="image/png" />
	<link href="https://catalystapp.co/internal_assets/logo/solid/logo.png" rel="apple-touch-icon" type="image/png" />
	<link href="https://catalystapp.co/internal_assets/logo/solid/logo.png" rel="apple-touch-icon-precomposed"
		type="image/png" />
	<link rel="mask-icon" href="https://catalystapp.co/internal_assets/logo/mask.svg" color="#<?= PAGE_COLOR ?>" />

	<!-- IE -->
	<meta name="msapplication-tooltip" content="Catalyst - Facilitating Commissions" />
	<meta name="mssmarttagspreventparsing" content="true" />
	<meta name="msapplication-starturl" content="https://catalystapp.co/" />
	<meta name="msapplication-window" content="width=800;height=600" />
	<meta name="msapplication-navbutton-color" content="green" />
	<meta name="application-name" content="Catalyst" />

	<!-- win 8+ -->
	<meta name="application-name" content="Catalyst" />
	<meta name="msapplication-TileColor" content="#<?= PAGE_COLOR ?>" />
	<meta name="msapplication-square70x70logo" content="<?= ROOTDIR ?>internal_assets/logo/solid/logo.png" />

	<!-- opengraph -->
	<meta property="og:title" content="<?= htmlspecialchars(PAGE_TITLE) ?>" />
	<meta property="og:type" content="business.business" />
	<meta property="og:url" content="<?= htmlspecialchars(UniversalFunctions::getCanonicalRequestUrl()) ?>" />
	<?php if (defined("PAGE_IMAGE")): ?>
		<meta property="og:image" content="<?= PAGE_IMAGE ?>" />
		<meta property="og:image:url" content="<?= PAGE_IMAGE ?>" />
	<?php else: ?>
		<meta property="og:image" content="https://catalystapp.co/internal_assets/banners/sammy/usage/main.png" />
		<meta property="og:image:url" content="https://catalystapp.co/internal_assets/banners/sammy/usage/main.png" />
	<?php endif; ?>
	<meta property="og:description"
		content="Catalyst serves to facilitate the process of commissioning through a simple, unified, and mobile-friendly way for artists to easily list their prices, receive and track commissions, and much more." />
	<meta property="og:site_name" content="Catalyst" />
	<meta property="og:locale" content="en_US" />

	<!-- twitter -->
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="<?= htmlspecialchars(UniversalFunctions::getCanonicalRequestUrl()) ?>" />
	<meta name="twitter:title" content="<?= htmlspecialchars(PAGE_TITLE) ?> | Catalyst" />
	<meta name="twitter:description"
		content="Catalyst serves to facilitate the process of commissioning through a simple, unified, and mobile-friendly way for artists to easily list their prices, receive and track commissions, and much more." />
	<?php if (defined("PAGE_IMAGE")): ?>
		<meta property="twitter:image" content="<?= PAGE_IMAGE ?>" />
	<?php else: ?>
		<meta property="twitter:image" content="https://catalystapp.co/internal_assets/banners/sammy/usage/main.png" />
	<?php endif; ?>

	<!-- link tags -->
	<link rel='shortcut icon' type='image/png' href='https://catalystapp.co/internal_assets/logo/icon/logo.png' />
	<link rel='fluid-icon' type='image/png' href='https://catalystapp.co/internal_assets/logo/icon/logo.png' />
	<link rel="canonical" href="<?= htmlspecialchars(UniversalFunctions::getCanonicalRequestUrl()) ?>" />
	<link rel="image_src" href="https://catalystapp.co/internal_assets/logo/white/logo.png" type="image/png" />

	<script type="text/javascript">
		window.devMode = <?= json_encode(Controller::isDevelMode()) ?>;
	</script>
</head>

<body>
	<?php require REAL_ROOTDIR . "src/php/Page/Navigation/navbar.inc.php"; ?>
	<div class="container">
