<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::SETTINGS[0]);
define("PAGE_TITLE", Values::createTitle(Values::SETTINGS[1], ["name" => (isset($_SESSION["user"]) ? $_SESSION["user"]->getNickname() : "Logged Out")]));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColorHex());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("2FA Settings");

if (!User::isLoggedIn()):
	echo User::getNotLoggedInHtml();
elseif (!$_SESSION["user"]->isTotpEnabled()):
?>
	<div class="section">
		<p class="flow-text">Two factor authentication is disabled.  Enable it <a href="<?= ROOTDIR ?>Settings">here</a>.</p>
	</div>
<?php else: ?>
	<div class="section">
		<div class="row">
			<p class="flow-text col s12">Use the following QR code or manual key below to add Catalyst to your authenticator app</p>
			<div class="col s12 m4 center">
<?php

$binaryKey = $_SESSION["user"]->getTotpKey();

$map = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','2','3','4','5','6','7','='];

$binaryKey = str_split($binaryKey);
$binaryString = "";
$inputCount = count($binaryKey);
for ($i = 0; $i < $inputCount; $i++) {
	$binaryString .= str_pad(base_convert(ord($binaryKey[$i]), 10, 2), 8, '0', STR_PAD_LEFT);
}
$fiveBitBinaryArray = str_split($binaryString, 5);
$key = "";
$i = 0;
$fiveCount = count($fiveBitBinaryArray);
while ($i < $fiveCount) {
	$key .= $map[base_convert(str_pad($fiveBitBinaryArray[$i], 5, '0'), 2, 10)];
	$i++;
}

$tmpfname = tempnam(sys_get_temp_dir(), 'FOO');

\QRcode::png('otpauth://totp/Catalyst:'.$_SESSION["user"]->getNickname().'?secret='.$key.'&issuer=Catalyst&digits=6&period=30', $tmpfname, QR_ECLEVEL_L, 10, 0);

$qr = base64_encode(file_get_contents($tmpfname));

unlink($tmpfname);
?>
				<img class="col s12 render-pixelated" src="data:image/png;base64,<?= $qr ?>">
				<p class="code col s12 flow-text"><?= $key ?></h4>
			</div>
			<div class="col s12 m8">
				<p class="flow-text">If you lose access to your two-factor authentication app, you will need the following recovery key.</p>
				<p class="flow-text"><strong>Without it you will not be able to access your account if you lose access to your authentication app</strong></p>
				<h4 class="code"><?= $_SESSION["user"]->getTotpResetToken() ?></h4>
				<p class="flow-text">Your current code is: <span class="code totp-preview" data-secret="<?= $key ?>">......</span></p>
			</div>
		</div>
	</div>
	<div class="divider"></div>
	<div class="section">
		<p class="flow-text">You may disable two factor authentication <a href="<?= ROOTDIR ?>Settings">here</a>.</p>
	</div>
<?php
endif;

require_once Values::FOOTER_INC;
